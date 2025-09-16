<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('department')->paginate(20);
        $teachercounts = Teacher::count();

        return view('admin.teacher.index', [
            'teachers' => $teachers,
            'teachercounts' => $teachercounts
        ]);
    }

    /**
     * Display teachers on the public user page with pagination and advanced search functionality
     * Supports boolean operators: AND, OR, NOT
     * Examples: "john AND computer", "NOT physics", "john OR jane AND position:professor"
     */
    public function userShow(Request $request){
        $searchQuery = $request->input('search');
        $filters = $request->input('filter');
        
        // Handle visual query builder parameters
        $fieldArray = $request->input('field', []);
        $valueArray = $request->input('value', []);
        $operatorArray = $request->input('operator', []);
        
        // Convert visual query builder to search string if provided
        if (!empty($fieldArray) && !empty($valueArray)) {
            $searchQuery = $this->buildSearchQueryFromArrays($fieldArray, $valueArray, $operatorArray);
        }
        
        // Convert comma-separated filters to array
        if (is_string($filters)) {
            $filters = array_filter(explode(',', $filters));
        }
        
        if (empty($filters)) {
            $filters = ['all'];
        }

        $query = Teacher::with('department');
        
        // Apply advanced search if provided
        if ($searchQuery) {
            // If 'all' is selected, convert it to all available filter types
            if (in_array('all', $filters)) {
                $filters = ['id', 'name', 'position', 'phone', 'email', 'department', 'gender', 'date_of_birth'];
            }

            $query->where(function ($q) use ($searchQuery, $filters) {
                $this->parseAdvancedSearch($q, $searchQuery, $filters);
            });
        }

        $teachers = $query->paginate(20)->withQueryString();
        $teachercounts = Teacher::count();
        $queryteachercounts = $query->count();
        return view('userteachershow', [
            'teachers' => $teachers,
            'teachercounts' => $teachercounts,
            'queryteachercounts' => $queryteachercounts
        ]);
    }

    /**
     * Build search query string from visual query builder arrays
     */
    private function buildSearchQueryFromArrays($fieldArray, $valueArray, $operatorArray)
    {
        $searchParts = [];
        
        for ($i = 0; $i < count($valueArray); $i++) {
            $value = trim($valueArray[$i]);
            if (empty($value)) {
                continue;
            }
            
            // Add the current value
            $searchParts[] = $value;
            
            // Add connecting operator for next term (if not the last term)
            if ($i < count($valueArray) - 1 && $i < count($operatorArray)) {
                $operator = strtoupper($operatorArray[$i]);
                if (!empty($operator)) {
                    $searchParts[] = $operator;
                }
            }
        }
        
        return implode(' ', $searchParts);
    }

    /**
     * Parse advanced search query with boolean operators (AND, OR, NOT)
     * Supports field-specific searches like "name:john", "position:professor"
     * Examples:
     * - "john AND computer" - both terms must be found
     * - "john OR jane" - either term can be found
     * - "NOT physics" - exclude results containing physics
     * - "name:john AND department:computer" - field-specific search
     */
    private function parseAdvancedSearch($query, $searchQuery, $filters)
    {
       
        // Clean and normalize the search query
        $searchQuery = trim($searchQuery);
        
        // Handle parentheses for complex queries
        if (strpos($searchQuery, '(') !== false) {
           
            $this->parseComplexQuery($query, $searchQuery, $filters);
            return;
        }
        
        // Split by AND/OR operators while preserving them
        $tokens = $this->tokenizeQuery($searchQuery);
        
        if (empty($tokens)) {
            return;
        }
        
        $this->buildQueryFromTokens($query, $tokens, $filters);
    }
    
    /**
     * Tokenize the search query into terms and operators
     */
    private function tokenizeQuery($searchQuery)
    {

        
        // First handle NOT operator - replace "NOT term" with "|NOT term|"
        // Updated regex to properly handle multi-word terms with spaces
        $searchQuery = preg_replace('/\s+NOT\s+([^|]+?)(?=\s+(?:AND|OR)\s+|$)/i', '|NOT $1|', $searchQuery);
       
        // Then handle AND/OR operators
        $searchQuery = preg_replace('/\s+(AND|OR)\s+/i', '|$1|', $searchQuery);
        
        // Split by delimiters
        $parts = explode('|', $searchQuery);
         
        $tokens = [];
        foreach ($parts as $part) {
            $part = trim($part);
            if (!empty($part)) {
                $tokens[] = $part;
            }
        }
        
        return $tokens;
    }
    
    /**
     * Build query from parsed tokens
     */
    private function buildQueryFromTokens($query, $tokens, $filters)
    {

       
        $query->where(function ($outerQuery) use ($tokens, $filters) {
            $currentOperator = 'AND';
            $isFirst = true;

            foreach ($tokens as $i => $token) {
                if (in_array(strtoupper($token), ['AND', 'OR'])) {
                    $currentOperator = strtoupper($token);
                    continue;
                }

                $isNegated = false;
                if (stripos($token, 'NOT ') === 0) {
                    $isNegated = true;
                    $token = trim(substr($token, 4));
                }

                $callback = function ($subQuery) use ($token, $filters, $isNegated) {
                    $this->applySearchCondition($subQuery, $token, $filters, $isNegated);
                };

                if ($isFirst) {
                    $outerQuery->where($callback);
                    $isFirst = false;
                } else {
                    if ($currentOperator === 'AND') {
                        $outerQuery->where($callback);
                    } else { // OR
                        $outerQuery->orWhere($callback);
                    }
                }
            }
        });
    }

    
    /**
     * Apply search condition for a single term
     */
    private function applySearchCondition($query, $term, $filters, $isNegated = false)
    {
        // Check if it's a field-specific search (e.g., "name:john")
        if (strpos($term, ':') !== false) {
            list($field, $value) = explode(':', $term, 2);
            $field = trim($field);
            $value = trim($value);
            $this->applyFieldSpecificSearch($query, $field, $value, $isNegated);
        } else {
            $this->applyGeneralSearch($query, $term, $filters, $isNegated);
        }
    }
    
    /**
     * Apply field-specific search
     */
    private function applyFieldSpecificSearch($query, $field, $value, $isNegated = false)
    {
        $value = strtolower($value);
        
        switch ($field) {
            case 'id':
                if ($isNegated) {
                    $query->whereNot(function ($q) use ($value) {
                        $q->whereRaw('LOWER(id) = ?', [$value]);
                    });
                } else {
                    $query->whereRaw('LOWER(id) = ?', [$value]);
                }
                break;
            case 'name':
                if ($isNegated) {
                    $query->whereNot(function ($q) use ($value) {
                        $q->whereRaw('LOWER(name) LIKE ?', ['%' . $value . '%']);
                    });
                } else {
                    $query->whereRaw('LOWER(name) LIKE ?', ['%' . $value . '%']);
                }
                break;
            case 'position':
                if ($isNegated) {
                    $query->whereNot(function ($q) use ($value) {
                        $q->whereRaw('LOWER(position) LIKE ?', ['%' . $value . '%']);
                    });
                } else {
                    $query->whereRaw('LOWER(position) LIKE ?', ['%' . $value . '%']);
                }
                break;
            case 'phone':
                if ($isNegated) {
                    $query->whereNot(function ($q) use ($value) {
                        $q->whereRaw('LOWER(phone_number) LIKE ?', ['%' . $value . '%']);
                    });
                } else {
                    $query->whereRaw('LOWER(phone_number) LIKE ?', ['%' . $value . '%']);
                }
                break;
            case 'email':
                if ($isNegated) {
                    $query->whereNot(function ($q) use ($value) {
                        $q->whereRaw('LOWER(email) LIKE ?', ['%' . $value . '%']);
                    });
                } else {
                    $query->whereRaw('LOWER(email) LIKE ?', ['%' . $value . '%']);
                }
                break;
            case 'department':
            case 'dept':
                $value = strtolower(trim(preg_replace('/\s+/', ' ', $value))); // normalize spaces

                if ($isNegated) {
                    $query->whereDoesntHave('department', function ($deptQuery) use ($value) {
                        $deptQuery->where(function ($q) use ($value) {
                            $q->whereRaw('LOWER(fullname) LIKE ?', ['%' . $value . '%'])
                            ->orWhereRaw('LOWER(shortname) LIKE ?', ['%' . $value . '%'])
                            ->orWhereRaw('LOWER(deptCode) LIKE ?', ['%' . $value . '%']);
                        });
                    });
                } else {
                    $query->whereHas('department', function ($deptQuery) use ($value) {
                        $deptQuery->where(function ($q) use ($value) {
                            $q->whereRaw('LOWER(fullname) LIKE ?', ['%' . $value . '%'])
                            ->orWhereRaw('LOWER(shortname) LIKE ?', ['%' . $value . '%'])
                            ->orWhereRaw('LOWER(deptCode) LIKE ?', ['%' . $value . '%']);
                        });
                    });
                }
                break;

            case 'gender':
                if ($isNegated) {
                    $query->whereNot(function ($q) use ($value) {
                        $q->whereRaw('LOWER(gender) LIKE ?', ['%' . $value . '%']);
                    });
                } else {
                    $query->whereRaw('LOWER(gender) LIKE ?', ['%' . $value . '%']);
                }
                break;
            case 'date_of_birth':
            case 'dob':
                if ($isNegated) {
                    $query->whereNot(function ($q) use ($value) {
                        $q->whereRaw('LOWER(date_of_birth) LIKE ?', ['%' . $value . '%']);
                    });
                } else {
                    $query->whereRaw('LOWER(date_of_birth) LIKE ?', ['%' . $value . '%']);
                }
                break;
        }
    }
    
    /**
     * Apply general search across selected filters
     */
    private function applyGeneralSearch($query, $term, $filters, $isNegated = false)
    {
        $term = strtolower(trim($term));
        
        // For NOT operations with department-like terms, be more specific
        if ($isNegated && $this->isDepartmentLikeTerm($term)) {
            // If it looks like a department name, only exclude based on department
            $query->whereDoesntHave('department', function ($deptQuery) use ($term) {
                $deptQuery->where(function ($q) use ($term) {
                    $q->whereRaw('LOWER(fullname) LIKE ?', ['%' . $term . '%'])
                      ->orWhereRaw('LOWER(shortname) LIKE ?', ['%' . $term . '%'])
                      ->orWhereRaw('LOWER(deptCode) LIKE ?', ['%' . $term . '%']);
                });
            });
        } else {
            // Apply conditions with OR logic (any field can match)
            if ($isNegated) {
                // For NOT operations, we need to exclude records that match ANY of the conditions
                // This means: NOT (field1 LIKE term OR field2 LIKE term OR ...)
                $this->buildNegatedGeneralSearchConditions($query, $term, $filters);
            } else {
                $this->buildGeneralSearchConditions($query, $term, $filters);
            }
        }
    }
    
    /**
     * Check if a term looks like a department name
     */
    private function isDepartmentLikeTerm($term)
    {
        // Common department-related keywords
        $deptKeywords = [
            'computer', 'studies', 'science', 'engineering', 'mathematics', 'physics',
            'chemistry', 'biology', 'history', 'english', 'literature', 'economics',
            'business', 'management', 'psychology', 'sociology', 'philosophy',
            'geology', 'geography', 'archaeology', 'botany', 'zoology', 'relations',
            'international', 'oriental', 'department', 'dept', 'faculty'
        ];
        
        $termLower = strtolower($term);
        foreach ($deptKeywords as $keyword) {
            if (strpos($termLower, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Build negated general search conditions for NOT operations
     */
    private function buildNegatedGeneralSearchConditions($query, $term, $filters)
    {
        // Build conditions using raw SQL for proper NOT logic
        $conditions = [];
        $bindings = [];
        
        if (in_array('id', $filters) && is_numeric($term)) {
            $conditions[] = 'id = ?';
            $bindings[] = (int)$term;
        }
        
        if (in_array('name', $filters)) {
            $conditions[] = 'LOWER(name) LIKE ?';
            $bindings[] = '%' . $term . '%';
        }
        
        if (in_array('position', $filters)) {
            $conditions[] = 'LOWER(position) LIKE ?';
            $bindings[] = '%' . $term . '%';
        }
        
        if (in_array('phone', $filters)) {
            $conditions[] = 'LOWER(phone_number) LIKE ?';
            $bindings[] = '%' . $term . '%';
        }
        
        if (in_array('email', $filters)) {
            $conditions[] = 'LOWER(email) LIKE ?';
            $bindings[] = '%' . $term . '%';
        }
        
        if (in_array('gender', $filters)) {
            $conditions[] = 'LOWER(gender) LIKE ?';
            $bindings[] = '%' . $term . '%';
        }
        
        if (in_array('date_of_birth', $filters)) {
            $conditions[] = 'LOWER(date_of_birth) LIKE ?';
            $bindings[] = '%' . $term . '%';
        }
        
        // Handle department separately for NOT operations
        if (in_array('department', $filters)) {
            $query->whereDoesntHave('department', function ($deptQuery) use ($term) {
                $deptQuery->where(function ($q) use ($term) {
                    $q->whereRaw('LOWER(fullname) LIKE ?', ['%' . $term . '%'])
                      ->orWhereRaw('LOWER(shortname) LIKE ?', ['%' . $term . '%'])
                      ->orWhereRaw('LOWER(deptCode) LIKE ?', ['%' . $term . '%']);
                });
            });
        }
        
        // Apply NOT logic to regular fields if any conditions exist
        if (!empty($conditions)) {
            $whereClause = 'NOT (' . implode(' OR ', $conditions) . ')';
            $query->whereRaw($whereClause, $bindings);
        }
    }
    
    /**
     * Build general search conditions for all selected filters
     */
    private function buildGeneralSearchConditions($query, $term, $filters)
    {
        $isFirst = true;
        
        if (in_array('id', $filters)) {
            // Only search ID if the term is numeric
            if (is_numeric($term)) {
                $method = $isFirst ? 'whereRaw' : 'orWhereRaw';
                $query->$method('id = ?', [(int)$term]);
                $isFirst = false;
            }
        }
        
        if (in_array('name', $filters)) {
            $method = $isFirst ? 'whereRaw' : 'orWhereRaw';
            $query->$method('LOWER(name) LIKE ?', ['%' . $term . '%']);
            $isFirst = false;
        }
        
        if (in_array('position', $filters)) {
            $method = $isFirst ? 'whereRaw' : 'orWhereRaw';
            $query->$method('LOWER(position) LIKE ?', ['%' . $term . '%']);
            $isFirst = false;
        }
        
        if (in_array('phone', $filters)) {
            $method = $isFirst ? 'whereRaw' : 'orWhereRaw';
            $query->$method('LOWER(phone_number) LIKE ?', ['%' . $term . '%']);
            $isFirst = false;
        }
        
        if (in_array('email', $filters)) {
            $method = $isFirst ? 'whereRaw' : 'orWhereRaw';
            $query->$method('LOWER(email) LIKE ?', ['%' . $term . '%']);
            $isFirst = false;
        }
        
        if (in_array('department', $filters)) {
            $method = $isFirst ? 'whereHas' : 'orWhereHas';
            $query->$method('department', function ($deptQuery) use ($term) {
                $deptQuery->where(function ($q) use ($term) {
                    $q->whereRaw('LOWER(fullname) LIKE ?', ['%' . $term . '%'])
                      ->orWhereRaw('LOWER(shortname) LIKE ?', ['%' . $term . '%'])
                      ->orWhereRaw('LOWER(deptCode) LIKE ?', ['%' . $term . '%']);
                });
            });
            $isFirst = false;
        }
        
        if (in_array('gender', $filters)) {
            $method = $isFirst ? 'whereRaw' : 'orWhereRaw';
            $query->$method('LOWER(gender) LIKE ?', ['%' . $term . '%']);
            $isFirst = false;
        }
        
        if (in_array('date_of_birth', $filters)) {
            $method = $isFirst ? 'whereRaw' : 'orWhereRaw';
            $query->$method('LOWER(date_of_birth) LIKE ?', ['%' . $term . '%']);
            $isFirst = false;
        }
    }
    
    /**
     * Handle complex queries with parentheses (future enhancement)
     */
    private function parseComplexQuery($query, $searchQuery, $filters)
    {
        // For now, fall back to simple parsing by removing parentheses
        $cleanQuery = str_replace(['(', ')'], '', $searchQuery);
        $tokens = $this->tokenizeQuery($cleanQuery);
        $this->buildQueryFromTokens($query, $tokens, $filters);
    }

    /**
     * Simple AJAX search for teachers with multiple filters
     * Handles: ID (exact match), name, position, phone, email, department searches
     * Uses simple LIKE matching without boolean operators
     */
    public function search(Request $request)
    {
        // Only handle AJAX requests for this method
        if (!$request->ajax()) {
            return response()->json(['error' => 'This endpoint only accepts AJAX requests'], 400);
        }

        $searchQuery = $request->input('search');
        $departmentId = $request->input('department_id');
        $filters = $request->input('filter', ['all']);

        if (!is_array($filters)) {
            $filters = [$filters];
        }

        $query = Teacher::with('department');

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($searchQuery) {
            // If 'all' is selected, convert it to all available filter types
            if (in_array('all', $filters)) {
                $filters = ['id', 'name', 'position', 'phone', 'email', 'department', 'gender', 'date_of_birth'];
            }

            $query->where(function ($q) use ($searchQuery, $filters) {
                $searchTerm = strtolower(trim($searchQuery));

                $q->where(function ($subQuery) use ($searchTerm, $filters) {
                    $conditions = [];

                    if (in_array('id', $filters)) {
                        $conditions[] = ['type' => 'where', 'field' => 'id', 'operator' => '=', 'value' => $searchTerm];
                    }

                    if (in_array('name', $filters)) {
                        $conditions[] = ['type' => 'where', 'field' => 'name', 'operator' => 'LIKE', 'value' => '%' . $searchTerm . '%'];
                    }

                    if (in_array('position', $filters)) {
                        $conditions[] = ['type' => 'where', 'field' => 'position', 'operator' => 'LIKE', 'value' => '%' . $searchTerm . '%'];
                    }

                    if (in_array('phone', $filters)) {
                        $conditions[] = ['type' => 'where', 'field' => 'phone_number', 'operator' => 'LIKE', 'value' => '%' . $searchTerm . '%'];
                    }

                    if (in_array('email', $filters)) {
                        $conditions[] = ['type' => 'where', 'field' => 'email', 'operator' => 'LIKE', 'value' => '%' . $searchTerm . '%'];
                    }

                    if (in_array('gender', $filters)) {
                        $conditions[] = ['type' => 'where', 'field' => 'gender', 'operator' => 'LIKE', 'value' => '%' . $searchTerm . '%'];
                    }

                    if (in_array('date_of_birth', $filters)) {
                        $conditions[] = ['type' => 'where', 'field' => 'date_of_birth', 'operator' => 'LIKE', 'value' => '%' . $searchTerm . '%'];
                    }

                    if (in_array('department', $filters)) {
                        $conditions[] = ['type' => 'whereHas', 'relation' => 'department'];
                    }

                    // Apply conditions with OR logic
                    foreach ($conditions as $index => $condition) {
                        if ($condition['type'] === 'where') {
                            $method = $index === 0 ? 'whereRaw' : 'orWhereRaw';
                            $field = $condition['field'];
                            $operator = $condition['operator'];
                            $value = $condition['value'];

                            if ($operator === '=') {
                                $subQuery->$method("LOWER($field) = ?", [$value]);
                            } else {
                                $subQuery->$method("LOWER($field) LIKE ?", [$value]);
                            }
                        } elseif ($condition['type'] === 'whereHas') {
                            $method = $index === 0 ? 'whereHas' : 'orWhereHas';
                            $subQuery->$method('department', function ($deptQuery) use ($searchTerm) {
                                $deptQuery->whereRaw('LOWER(fullname) LIKE ?', ['%' . $searchTerm . '%'])
                                    ->orWhereRaw('LOWER(shortname) LIKE ?', ['%' . $searchTerm . '%'])
                                    ->orWhereRaw('LOWER(deptCode) LIKE ?', ['%' . $searchTerm . '%']);
                            });
                        }
                    }
                });
            });
        }

        $teachers = $query->get();
        return response()->json($teachers);
    }



    public function show(Teacher $teacher)
    {
        return view('admin.teacher.show', [
            'teacher' => $teacher
        ]);
    }

    /**
     * Display individual teacher details on public user page
     */
    public function showTeachers(Teacher $teacher)
    {
        return view('admin.teacher.userteachershow', [
            'teacher' => $teacher,
        ]);
    }

    public function create()
    {
        $departments = Department::all();

        return view('admin.teacher.create', [
            'departments' => $departments
        ]);
    }

    /**
     * Create new teacher with image upload and organized file storage
     * Images are stored in assets/teachers/{phone_number}/ directory
     */
    public function store(Request $request)
    {
        $formData = $request->validate([
            'name' => 'required|string',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'position' => 'required|string',
            'phone_number' => 'nullable|string|unique:teachers,phone_number',
            'email' => 'nullable|email|unique:teachers,email',
            'image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $cleanName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', preg_replace('/\s+/', '_', $originalName));
            $fileName = time() . '_' . $cleanName;

            $uploadPath = public_path("assets/teachers/{$formData['phone_number']}");
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $file->move($uploadPath, $fileName);
            $formData['image'] = "assets/teachers/{$formData['phone_number']}/$fileName";
        }else{
            $formData['image'] = "assets/teachers/profile.png";
        }

        try {
            Teacher::create($formData);
        } catch (QueryException $e) {
            return back()->with('error', 'Failed to create teacher: ' . $e->getMessage());
        }

        return redirect()->route('teachers')->with('success', 'Teacher created successfully.');
    }

    public function edit(Teacher $teacher)
    {
        $departments = Department::all();

        return view('admin.teacher.edit', [
            'teacher' => $teacher,
            'departments' => $departments
        ]);
    }

    /**
     * Update teacher with image handling and file cleanup
     * Removes old image and creates new organized directory structure
     */
    public function update(Request $request, Teacher $teacher)
    {
        $formData = $request->validate([
            'name' => 'required|string',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'position' => 'required|string',
            'phone_number' => [
                'nullable',
                'string',
                Rule::unique('teachers')->ignore($teacher->id),
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('teachers')->ignore($teacher->id),
            ],
            'image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($request->hasFile('image')) {
            if (!empty($teacher->image)) {
                $oldImagePath = public_path($teacher->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $cleanName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', preg_replace('/\s+/', '_', $originalName));
            $fileName = time() . '_' . $cleanName;

            $uploadPath = public_path("assets/teachers/{$formData['phone_number']}");
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $file->move($uploadPath, $fileName);
            $formData['image'] = "assets/teachers/{$formData['phone_number']}/$fileName";
        } else {
            $formData['image'] = $teacher->image;
        }

        try {
            $teacher->update($formData);
        } catch (QueryException $e) {
            return back()->with('error', 'Failed to update teacher: ' . $e->getMessage());
        }

        return redirect()->route('teachers')->with('success', 'Teacher updated successfully.');
    }

    /**
     * Delete teacher and cleanup associated files/directories
     * Removes entire teacher directory from assets/teachers/{phone_number}
     */
    public function destroy(Teacher $teacher)
    {
        try {
            $folderPath = public_path("assets/teachers/{$teacher->phone_number}");

            if (File::exists($folderPath)) {
                File::deleteDirectory($folderPath);
            }

            $teacher->delete();
        } catch (QueryException $e) {
            return back()->with('error', 'Failed to delete teacher: ' . $e->getMessage());
        }

        return redirect()->route('teachers')->with('success', 'Teacher deleted successfully.');
    }

    /**
     * Export teacher search results to PDF
     * Supports both simple search (AJAX) and advanced search (form-based)
     */
    public function exportSearchPDF(Request $request)
    {
        $searchQuery = $request->input('search');
        $departmentId = $request->input('department_id');
        $filters = $request->input('filter', ['all']);
    
        // Handle visual query builder parameters for advanced search
        $fieldArray = $request->input('field', []);
        $valueArray = $request->input('value', []);
        $operatorArray = $request->input('operator', []);
    
        // Convert visual query builder to search string if provided
        if (!empty($fieldArray) && !empty($valueArray)) {
            $searchQuery = $this->buildSearchQueryFromArrays($fieldArray, $valueArray, $operatorArray);
        }
    
        // Convert comma-separated filters to array
        if (is_string($filters)) {
            $filters = array_filter(explode(',', $filters));
        }
    
        if (empty($filters)) {
            $filters = ['all'];
        }
    
        $query = Teacher::with('department');
    
        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }
    
        if ($searchQuery) {
            // If 'all' is selected, convert it to all available filter types
            if (in_array('all', $filters)) {
                $filters = ['id', 'name', 'position', 'phone', 'email', 'department', 'gender', 'date_of_birth'];
            }
    
            // Use the same advanced search logic as userShow method
            $query->where(function ($q) use ($searchQuery, $filters) {
                $this->parseAdvancedSearch($q, $searchQuery, $filters);
            });
        }
    
        $teachers = $query->get();
        $searchTerm = $searchQuery ?? 'All Teachers';
        $exportDate = now()->format('Y-m-d H:i:s');
        $totalResults = $teachers->count();
    
        // Determine search type for display
        $searchType = 'Simple Search';
        if (!empty($fieldArray) && !empty($valueArray)) {
            $searchType = 'Advanced Search (Visual Query Builder)';
        } elseif ($searchQuery && (strpos($searchQuery, 'AND') !== false || strpos($searchQuery, 'OR') !== false || strpos($searchQuery, 'NOT') !== false)) {
            $searchType = 'Advanced Search (Boolean)';
        }
    
        // Generate PDF
        $pdf = Pdf::loadView('admin.teacher.teachers-search-pdf', compact(
            'teachers', 
            'searchTerm', 
            'exportDate', 
            'totalResults',
            'searchType',
            'filters'
        ));
        
        // Set PDF options
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'Arial'
        ]);
        
        $filename = 'teachers-search-results-' . date('Y-m-d-H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }
}
