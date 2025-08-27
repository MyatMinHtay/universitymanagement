<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('department')->paginate(10);
        $studentcounts = Student::count();

        return view('admin.student.index', [
            'students' => $students,
            'studentcounts' => $studentcounts
        ]);
    }

    /**
     * Display students on the public user page with pagination and advanced search functionality
     * Supports boolean operators: AND, OR, NOT
     * Examples: "john AND computer", "NOT physics", "john OR jane AND year:2023"
     */
    public function userShow(Request $request){
        $searchQuery = $request->input('search');
        $filters = $request->input('filter');
        
        // Convert comma-separated filters to array
        if (is_string($filters)) {
            $filters = array_filter(explode(',', $filters));
        }
        
        if (empty($filters)) {
            $filters = ['all'];
        }

        $query = Student::with('department');
        
        // Apply advanced search if provided
        if ($searchQuery) {
            // If 'all' is selected, convert it to all available filter types
            if (in_array('all', $filters)) {
                $filters = ['id', 'name', 'year', 'roll_number', 'phone', 'email', 'department', 'gender', 'date_of_birth'];
            }

            $query->where(function ($q) use ($searchQuery, $filters) {
                $this->parseAdvancedSearch($q, $searchQuery, $filters);
            });
        }

        $students = $query->paginate(20)->withQueryString();
        $studentcounts = $query->count();

        return view('userstudentshow', [
            'students' => $students,
            'studentcounts' => $studentcounts
        ]);
    }

    /**
     * Parse advanced search query with boolean operators (AND, OR, NOT)
     * Supports field-specific searches like "name:john", "year:2023"
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
            case 'year':
                if ($isNegated) {
                    $query->whereNot(function ($q) use ($value) {
                        $q->whereRaw('LOWER(year) LIKE ?', ['%' . $value . '%']);
                    });
                } else {
                    $query->whereRaw('LOWER(year) LIKE ?', ['%' . $value . '%']);
                }
                break;
            case 'roll_number':
            case 'roll':
                if ($isNegated) {
                    $query->whereNot(function ($q) use ($value) {
                        $q->whereRaw('LOWER(roll_number) LIKE ?', ['%' . $value . '%']);
                    });
                } else {
                    $query->whereRaw('LOWER(roll_number) LIKE ?', ['%' . $value . '%']);
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
        
        // Apply conditions with OR logic (any field can match)
        if ($isNegated) {
            $query->whereNot(function ($notQuery) use ($term, $filters) {
                $this->buildGeneralSearchConditions($notQuery, $term, $filters);
            });
        } else {
            $this->buildGeneralSearchConditions($query, $term, $filters);
        }
    }
    
    /**
     * Build general search conditions for all selected filters
     */
    private function buildGeneralSearchConditions($query, $term, $filters)
    {
        $isFirst = true;
        
        if (in_array('id', $filters)) {
            $method = $isFirst ? 'whereRaw' : 'orWhereRaw';
            $query->$method('LOWER(id) = ?', [$term]);
            $isFirst = false;
        }
        
        if (in_array('name', $filters)) {
            $method = $isFirst ? 'whereRaw' : 'orWhereRaw';
            $query->$method('LOWER(name) LIKE ?', ['%' . $term . '%']);
            $isFirst = false;
        }
        
        if (in_array('year', $filters)) {
            $method = $isFirst ? 'whereRaw' : 'orWhereRaw';
            $query->$method('LOWER(year) LIKE ?', ['%' . $term . '%']);
            $isFirst = false;
        }
        
        if (in_array('roll_number', $filters)) {
            $method = $isFirst ? 'whereRaw' : 'orWhereRaw';
            $query->$method('LOWER(roll_number) LIKE ?', ['%' . $term . '%']);
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
     * Advanced search for students with multiple filters and keyword support
     * Handles: ID (exact match), name, year, roll_number, phone, email, department searches
     * Supports multiple keywords separated by spaces using OR logic
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

        $query = Student::with('department');

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($searchQuery) {
            // If 'all' is selected, convert it to all available filter types
            if (in_array('all', $filters)) {
                $filters = ['id', 'name', 'year', 'roll_number', 'phone', 'email', 'department', 'gender', 'date_of_birth'];
            }

            $query->where(function ($q) use ($searchQuery, $filters) {
                $searchTerm = strtolower(trim($searchQuery));
                
                // Build search conditions based on selected filters
                $q->where(function ($subQuery) use ($searchTerm, $filters) {
                    $conditions = [];
                    
                    // Collect all conditions first
                    if (in_array('id', $filters)) {
                        $conditions[] = ['type' => 'where', 'field' => 'id', 'operator' => '=', 'value' => $searchTerm];
                    }
                    
                    if (in_array('name', $filters)) {
                        $conditions[] = ['type' => 'where', 'field' => 'name', 'operator' => 'LIKE', 'value' => '%' . $searchTerm . '%'];
                    }
                    
                    if (in_array('year', $filters)) {
                        $conditions[] = ['type' => 'where', 'field' => 'year', 'operator' => '=', 'value' => $searchTerm];
                    }
                    
                    if (in_array('roll_number', $filters)) {
                        $conditions[] = ['type' => 'where', 'field' => 'roll_number', 'operator' => 'LIKE', 'value' => '%' . $searchTerm . '%'];
                    }
                    
                    if (in_array('phone', $filters)) {
                        $conditions[] = ['type' => 'where', 'field' => 'phone_number', 'operator' => 'LIKE', 'value' => '%' . $searchTerm . '%'];
                    }
                    
                    if (in_array('email', $filters)) {
                        $conditions[] = ['type' => 'where', 'field' => 'email', 'operator' => 'LIKE', 'value' => '%' . $searchTerm . '%'];
                    }
                    
                    if (in_array('department', $filters)) {
                        $conditions[] = ['type' => 'whereHas', 'relation' => 'department'];
                    }
                    
                    if (in_array('gender', $filters)) {
                        $conditions[] = ['type' => 'where', 'field' => 'gender', 'operator' => 'LIKE', 'value' => '%' . $searchTerm . '%'];
                    }
                    
                    if (in_array('date_of_birth', $filters)) {
                        $conditions[] = ['type' => 'where', 'field' => 'date_of_birth', 'operator' => 'LIKE', 'value' => '%' . $searchTerm . '%'];
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

        $students = $query->get();
        return response()->json($students);
    }



    public function create()
    {
        $departments = Department::all();

        return view('admin.student.create', [
            'departments' => $departments,
        ]);
    }

    public function show(Student $student)
    {
        return view('admin.student.show', [
            'student' => $student,
        ]);
    }

    /**
     * Display individual student details on public user page
     */
    public function showStudents(Student $student)
    {
        return view('admin.student.userstushow', [
            'student' => $student,
        ]);
    }

    /**
     * Create new student with image upload and organized file storage
     * Images are stored in assets/students/{roll_number}/ directory
     */
    public function store(Request $request)
    {
        $formData = $request->validate([
            'name' => 'required|string',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'year' => 'required|string',
            'roll_number' => 'required|string',
            'phone_number' => 'nullable|string|unique:students,phone_number',
            'email' => 'nullable|email|unique:students,email',
            'image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $cleanName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', preg_replace('/\s+/', '_', $originalName));
            $fileName = time() . '_' . $cleanName;

            $uploadPath = public_path("assets/students/{$formData['roll_number']}");
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $file->move($uploadPath, $fileName);
            $formData['image'] = "assets/students/{$formData['roll_number']}/$fileName";
        }else{
            $formData['image'] = "assets/students/profile.png";
        }

        try {
            Student::create($formData);
        } catch (QueryException $e) {

            if($e->getCode() == 23000){
                return back()->with('error', 'Roll number and year combination already exists.')->withInput();
            }
            return back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('students')->with('success', 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        $departments = Department::all();

        return view('admin.student.edit', [
            'student' => $student,
            'departments' => $departments,
        ]);
    }

    /**
     * Update student with image handling and file cleanup
     * Removes old image and creates new organized directory structure
     */
    public function update(Request $request, Student $student)
    {
        $formData = $request->validate([
            'name' => 'required|string',
            'gender' => 'nullable|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'year' => 'required|string',
            'roll_number' => 'required|string',
            'phone_number' => [
                'nullable',
                'string',
                Rule::unique('students')->ignore($student->id),
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('students')->ignore($student->id),
            ],
            'image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($request->hasFile('image')) {
            if (!empty($student->image)) {
                $oldImagePath = public_path($student->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $cleanName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', preg_replace('/\s+/', '_', $originalName));
            $fileName = time() . '_' . $cleanName;

            $uploadPath = public_path("assets/students/{$formData['roll_number']}");
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $file->move($uploadPath, $fileName);
            $formData['image'] = "assets/students/{$formData['roll_number']}/$fileName";
        } else {
            $formData['image'] = $student->image;
        }

        try {
            $student->update($formData);
        } catch (QueryException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('students')->with('success', 'Student updated successfully.');
    }

    /**
     * Delete student and cleanup associated files/directories
     * Removes entire student directory from assets/students/{roll_number}
     */
    public function destroy(Student $student)
    {
        try {
            $folderPath = public_path("assets/students/{$student->roll_number}");

            if (File::exists($folderPath)) {
                File::deleteDirectory($folderPath);
            }

            $student->delete();
        } catch (QueryException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('students')->with('success', 'Student deleted successfully.');
    }

    /**
     * Export student search results to PDF with support for both simple and advanced search
     */
    public function exportSearchPDF(Request $request)
    {
        $searchQuery = $request->input('search');
        $departmentId = $request->input('department_id');
        $filters = $request->input('filter');

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

        $query = Student::with('department');

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        // Apply advanced search if provided
        if ($searchQuery) {
            // If 'all' is selected, convert it to all available filter types
            if (in_array('all', $filters)) {
                $filters = ['id', 'name', 'year', 'roll_number', 'phone', 'email', 'department', 'gender', 'date_of_birth'];
            }

            $query->where(function ($q) use ($searchQuery, $filters) {
                $this->parseAdvancedSearch($q, $searchQuery, $filters);
            });
        }

        $students = $query->get(); // Get all results for PDF
        $studentcounts = $students->count();
        $exportDate = now()->format('Y-m-d H:i:s');

        $pdf = PDF::loadView('admin.student.students-search-pdf', compact('students', 'studentcounts', 'searchQuery', 'exportDate'));
        return $pdf->download('students-search-results.pdf');
    }

    /**
     * Helper method to build search query from visual query builder arrays
     */
    private function buildSearchQueryFromArrays($fieldArray, $valueArray, $operatorArray)
    {
        $queryParts = [];
        
        for ($i = 0; $i < count($fieldArray); $i++) {
            if (!empty($valueArray[$i])) {
                $field = $fieldArray[$i];
                $value = $valueArray[$i];
                
                if ($field === 'all') {
                    $queryParts[] = $value;
                } else {
                    $queryParts[] = $field . ':' . $value;
                }
                
                // Add operator for next iteration (if not last)
                if ($i < count($fieldArray) - 1 && !empty($valueArray[$i + 1])) {
                    $operator = $operatorArray[$i] ?? 'AND';
                    $queryParts[] = $operator;
                }
            }
        }
        
        return implode(' ', $queryParts);
    }
}

    
