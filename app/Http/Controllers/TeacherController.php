<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;

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
        $teachercounts = $query->count();

        return view('userteachershow', [
            'teachers' => $teachers,
            'teachercounts' => $teachercounts
        ]);
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
        // Replace operators with delimiters
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
        $currentOperator = 'AND'; // Default operator
        
        for ($i = 0; $i < count($tokens); $i++) {
            $token = $tokens[$i];
            
            if (strtoupper($token) === 'AND' || strtoupper($token) === 'OR') {
                $currentOperator = strtoupper($token);
                continue;
            }
            
            $isNegated = false;
            if (strtoupper(substr($token, 0, 4)) === 'NOT ') {
                $isNegated = true;
                $token = trim(substr($token, 4));
            }
            
            // Determine if this is the first condition
            $isFirst = ($i === 0) || ($i === 1 && strtoupper($tokens[0]) === 'NOT');
            
            if ($isFirst) {
                if ($isNegated) {
                    $query->whereNot(function ($subQuery) use ($token, $filters) {
                        $this->applySearchCondition($subQuery, $token, $filters);
                    });
                } else {
                    $query->where(function ($subQuery) use ($token, $filters) {
                        $this->applySearchCondition($subQuery, $token, $filters);
                    });
                }
            } else {
                if ($currentOperator === 'AND') {
                    if ($isNegated) {
                        $query->whereNot(function ($subQuery) use ($token, $filters) {
                            $this->applySearchCondition($subQuery, $token, $filters);
                        });
                    } else {
                        $query->where(function ($subQuery) use ($token, $filters) {
                            $this->applySearchCondition($subQuery, $token, $filters);
                        });
                    }
                } else { // OR
                    if ($isNegated) {
                        $query->orWhereNot(function ($subQuery) use ($token, $filters) {
                            $this->applySearchCondition($subQuery, $token, $filters);
                        });
                    } else {
                        $query->orWhere(function ($subQuery) use ($token, $filters) {
                            $this->applySearchCondition($subQuery, $token, $filters);
                        });
                    }
                }
            }
        }
    }
    
    /**
     * Apply search condition for a single term
     */
    private function applySearchCondition($query, $term, $filters)
    {
        // Check if it's a field-specific search (e.g., "name:john")
        if (strpos($term, ':') !== false) {
            list($field, $value) = explode(':', $term, 2);
            $field = trim($field);
            $value = trim($value);
            
            $this->applyFieldSpecificSearch($query, $field, $value);
        } else {
            // General search across all selected filters
            $this->applyGeneralSearch($query, $term, $filters);
        }
    }
    
    /**
     * Apply field-specific search
     */
    private function applyFieldSpecificSearch($query, $field, $value)
    {
        $value = strtolower($value);
        
        switch ($field) {
            case 'id':
                $query->whereRaw('LOWER(id) = ?', [$value]);
                break;
            case 'name':
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . $value . '%']);
                break;
            case 'position':
                $query->whereRaw('LOWER(position) LIKE ?', ['%' . $value . '%']);
                break;
            case 'phone':
                $query->whereRaw('LOWER(phone_number) LIKE ?', ['%' . $value . '%']);
                break;
            case 'email':
                $query->whereRaw('LOWER(email) LIKE ?', ['%' . $value . '%']);
                break;
            case 'department':
            case 'dept':
                $query->whereHas('department', function ($deptQuery) use ($value) {
                    $deptQuery->whereRaw('LOWER(fullname) LIKE ?', ['%' . $value . '%'])
                        ->orWhereRaw('LOWER(shortname) LIKE ?', ['%' . $value . '%'])
                        ->orWhereRaw('LOWER(deptCode) LIKE ?', ['%' . $value . '%']);
                });
                break;
            case 'gender':
                $query->whereRaw('LOWER(gender) LIKE ?', ['%' . $value . '%']);
                break;
            case 'date_of_birth':
            case 'dob':
                $query->whereRaw('LOWER(date_of_birth) LIKE ?', ['%' . $value . '%']);
                break;
        }
    }
    
    /**
     * Apply general search across selected filters
     */
    private function applyGeneralSearch($query, $term, $filters)
    {
        $term = strtolower($term);
        $conditions = [];
        
        // Build conditions based on selected filters
        if (in_array('id', $filters)) {
            $conditions[] = ['type' => 'where', 'field' => 'id', 'operator' => '=', 'value' => $term];
        }
        
        if (in_array('name', $filters)) {
            $conditions[] = ['type' => 'where', 'field' => 'name', 'operator' => 'LIKE', 'value' => '%' . $term . '%'];
        }
        
        if (in_array('position', $filters)) {
            $conditions[] = ['type' => 'where', 'field' => 'position', 'operator' => 'LIKE', 'value' => '%' . $term . '%'];
        }
        
        if (in_array('phone', $filters)) {
            $conditions[] = ['type' => 'where', 'field' => 'phone_number', 'operator' => 'LIKE', 'value' => '%' . $term . '%'];
        }
        
        if (in_array('email', $filters)) {
            $conditions[] = ['type' => 'where', 'field' => 'email', 'operator' => 'LIKE', 'value' => '%' . $term . '%'];
        }
        
        if (in_array('department', $filters)) {
            $conditions[] = ['type' => 'whereHas', 'relation' => 'department'];
        }
        
        if (in_array('gender', $filters)) {
            $conditions[] = ['type' => 'where', 'field' => 'gender', 'operator' => 'LIKE', 'value' => '%' . $term . '%'];
        }
        
        if (in_array('date_of_birth', $filters)) {
            $conditions[] = ['type' => 'where', 'field' => 'date_of_birth', 'operator' => 'LIKE', 'value' => '%' . $term . '%'];
        }
        
        // Apply conditions with OR logic (any field can match)
        foreach ($conditions as $index => $condition) {
            if ($condition['type'] === 'where') {
                $method = $index === 0 ? 'whereRaw' : 'orWhereRaw';
                $field = $condition['field'];
                $operator = $condition['operator'];
                $value = $condition['value'];
                
                if ($operator === '=') {
                    $query->$method("LOWER($field) = ?", [$value]);
                } else {
                    $query->$method("LOWER($field) LIKE ?", [$value]);
                }
            } elseif ($condition['type'] === 'whereHas') {
                $method = $index === 0 ? 'whereHas' : 'orWhereHas';
                $query->$method('department', function ($deptQuery) use ($term) {
                    $deptQuery->whereRaw('LOWER(fullname) LIKE ?', ['%' . $term . '%'])
                        ->orWhereRaw('LOWER(shortname) LIKE ?', ['%' . $term . '%'])
                        ->orWhereRaw('LOWER(deptCode) LIKE ?', ['%' . $term . '%']);
                });
            }
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
     * Advanced search for teachers with multiple filters and keyword support
     * Handles: ID (exact match), name, position, phone, email, department searches
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
}
