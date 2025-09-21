<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;

class FacultyController extends Controller
{
    public function index()
    {
        $faculty = Faculty::paginate(20);

        return view('admin.faculty.index', [
            'faculty' => $faculty
        ]);
    }

    /**
     * Display faculty members on the public user page with pagination
     */
    public function userShow()
    {
        $faculty = Faculty::paginate(20);

        return view('userfacultyshow', [
            'faculty' => $faculty
        ]);
    }

    /**
     * Advanced search for faculty with multiple filters and keyword support
     * Handles: ID (exact match), name, position, phone, email, department searches
     * Department is handled as string field (no relationship)
     * Supports multiple keywords separated by spaces using OR logic
     */
    public function search(Request $request)
    {
        $searchQuery = $request->input('search');
        $departmentId = $request->input('department_id');
        $filters = $request->input('filter', ['all']); // Accept array of filters

        if (!is_array($filters)) {
            $filters = [$filters];
        }

        $query = Faculty::query();

        // Filter by department_id if provided
        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        // Apply search filters
        if ($searchQuery) {
            $keywords = array_filter(explode(' ', trim($searchQuery)));
            $keywordCount = count($keywords);

            $query->where(function ($q) use ($searchQuery, $keywords, $filters, $keywordCount) {
                if ($keywordCount === 1) {
                    $keyword = strtolower($keywords[0]);

                    $q->where(function ($subQuery) use ($keyword, $filters) {
                        if (in_array('all', $filters)) {
                            $subQuery->whereRaw('LOWER(id) = ?', [$keyword])
                                    ->orWhereRaw('LOWER(name) LIKE ?', ['%' . $keyword . '%'])
                                    ->orWhereRaw('LOWER(position) LIKE ?', ['%' . $keyword . '%'])
                                    ->orWhereRaw('LOWER(phone_number) LIKE ?', ['%' . $keyword . '%'])
                                    ->orWhereRaw('LOWER(email) LIKE ?', ['%' . $keyword . '%'])
                                    ->orWhereRaw('LOWER(department) LIKE ?', ['%' . $keyword . '%']);
                        } else {
                            $hasCondition = false;

                            if (in_array('id', $filters)) {
                                $subQuery->whereRaw('LOWER(id) = ?', [$keyword]);
                                $hasCondition = true;
                            }

                            if (in_array('name', $filters)) {
                                $method = $hasCondition ? 'orWhereRaw' : 'whereRaw';
                                $subQuery->$method('LOWER(name) LIKE ?', ['%' . $keyword . '%']);
                                $hasCondition = true;
                            }

                            if (in_array('position', $filters)) {
                                $method = $hasCondition ? 'orWhereRaw' : 'whereRaw';
                                $subQuery->$method('LOWER(position) LIKE ?', ['%' . $keyword . '%']);
                                $hasCondition = true;
                            }

                            if (in_array('phone', $filters)) {
                                $method = $hasCondition ? 'orWhereRaw' : 'whereRaw';
                                $subQuery->$method('LOWER(phone_number) LIKE ?', ['%' . $keyword . '%']);
                                $hasCondition = true;
                            }

                            if (in_array('email', $filters)) {
                                $method = $hasCondition ? 'orWhereRaw' : 'whereRaw';
                                $subQuery->$method('LOWER(email) LIKE ?', ['%' . $keyword . '%']);
                                $hasCondition = true;
                            }

                            if (in_array('department', $filters)) {
                                $method = $hasCondition ? 'orWhereRaw' : 'whereRaw';
                                $subQuery->$method('LOWER(department) LIKE ?', ['%' . $keyword . '%']);
                            }
                        }
                    });
                } else {
                    // Multi-word: exact name match
                    if (in_array('all', $filters) || in_array('name', $filters)) {
                        $q->whereRaw('LOWER(name) = ?', [strtolower($searchQuery)]);
                    } else {
                        foreach ($keywords as $index => $keyword) {
                            if (empty($keyword)) continue;
                            $method = $index === 0 ? 'where' : 'orWhere';

                            $q->$method(function ($subQuery) use ($keyword, $filters) {
                                $keyword = strtolower($keyword);
                                $hasCondition = false;

                                if (in_array('id', $filters)) {
                                    $subQuery->whereRaw('LOWER(id) = ?', [$keyword]);
                                    $hasCondition = true;
                                }

                                if (in_array('name', $filters)) {
                                    $method = $hasCondition ? 'orWhereRaw' : 'whereRaw';
                                    $subQuery->$method('LOWER(name) LIKE ?', ['%' . $keyword . '%']);
                                    $hasCondition = true;
                                }

                                if (in_array('position', $filters)) {
                                    $method = $hasCondition ? 'orWhereRaw' : 'whereRaw';
                                    $subQuery->$method('LOWER(position) LIKE ?', ['%' . $keyword . '%']);
                                    $hasCondition = true;
                                }

                                if (in_array('phone', $filters)) {
                                    $method = $hasCondition ? 'orWhereRaw' : 'whereRaw';
                                    $subQuery->$method('LOWER(phone_number) LIKE ?', ['%' . $keyword . '%']);
                                    $hasCondition = true;
                                }

                                if (in_array('email', $filters)) {
                                    $method = $hasCondition ? 'orWhereRaw' : 'whereRaw';
                                    $subQuery->$method('LOWER(email) LIKE ?', ['%' . $keyword . '%']);
                                    $hasCondition = true;
                                }

                                if (in_array('department', $filters)) {
                                    $method = $hasCondition ? 'orWhereRaw' : 'whereRaw';
                                    $subQuery->$method('LOWER(department) LIKE ?', ['%' . $keyword . '%']);
                                }
                            });
                        }
                    }
                }
            });
        }

        $faculty = $query->get();

        return response()->json($faculty);
    }


    public function show(Faculty $faculty)
    {
        return view('admin.faculty.show', [
            'faculty' => $faculty
        ]);
    }

    /**
     * Display individual faculty member details on public user page
     */
    public function showFaculty(Faculty $faculty)
    {
        return view('userfacultydetail', [
            'faculty' => $faculty
        ]);
    }

    public function create()
    {
        $departments = Department::all();

        return view('admin.faculty.create', [
            'departments' => $departments
        ]);
    }

    /**
     * Create new faculty member with image upload and organized file storage
     * Images are stored in assets/faculty/{phone_number}/ directory
     */
    public function store(Request $request)
    {
        $formData = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'phone_number' => 'required|string|unique:faculty,phone_number|max:20',
            'email' => 'required|email|unique:faculty,email|max:255',
            'department_id' => 'required|exists:departments,id',
            'image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $cleanName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', preg_replace('/\s+/', '_', $originalName));
            $fileName = time() . '_' . $cleanName;

            $uploadPath = public_path("assets/faculty/{$formData['phone_number']}");
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $file->move($uploadPath, $fileName);
            $formData['image'] = "assets/faculty/{$formData['phone_number']}/{$fileName}";
        } else {
            $formData['image'] = "assets/faculty/profile.png";
        }

        try {
            Faculty::create($formData);
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Failed to create faculty: ' . $e->getMessage()])->withInput();
        }

        return redirect()->route('faculty')->with('success', 'Faculty member created successfully.');
    }

    public function edit(Faculty $faculty)
    {
        $departments = Department::all();

        return view('admin.faculty.edit', [
            'faculty' => $faculty,
            'departments' => $departments
        ]);
    }

    /**
     * Update faculty member with image handling and file cleanup
     * Removes old image and creates new organized directory structure
     */
    public function update(Request $request, Faculty $faculty)
    {
        $formData = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'phone_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('faculty')->ignore($faculty->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('faculty')->ignore($faculty->id),
            ],
            // 'department_id' => 'required|exists:departments,id'
        ]);

       

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if (!empty($faculty->image) && $faculty->image !== 'assets/faculty/profile.png') {
                $oldImagePath = public_path($faculty->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $file = $request->file('image');
            $originalName = $file->getClientOriginalName();
            $cleanName = preg_replace('/[^A-Za-z0-9_\-\.]/', '', preg_replace('/\s+/', '_', $originalName));
            $fileName = time() . '_' . $cleanName;

            $uploadPath = public_path("assets/faculty/{$formData['phone_number']}");
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $file->move($uploadPath, $fileName);
            $formData['image'] = "assets/faculty/{$formData['phone_number']}/{$fileName}";
        } else {
            $formData['image'] = $faculty->image;
        }

        try {
            
            $faculty->update($formData);
            
        } catch (QueryException $e) {
           
            return back()->withErrors(['error' => 'Failed to update faculty: ' . $e->getMessage()]);
        }
        
            return redirect()->route('faculty')->with('success', 'Faculty member updated successfully.');
        
    }

    /**
     * Delete faculty member and cleanup associated files/directories
     * Removes entire faculty directory from assets/faculty/{phone_number}
     */
    public function destroy(Faculty $faculty)
    {
        try {
            // Delete faculty folder with images
            $folderPath = public_path("assets/faculty/{$faculty->phone_number}");
            if (File::exists($folderPath)) {
                File::deleteDirectory($folderPath);
            }

            $faculty->delete();
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Failed to delete faculty: ' . $e->getMessage()]);
        }

        return redirect()->route('faculty')->with('success', 'Faculty member deleted successfully.');
    }

    /**
     * Export faculty search results to PDF
     * Supports search functionality with filters
     */
    // Change the method name from exportPDF to exportSearchPDF (around line 333)
    public function exportSearchPDF(Request $request)
    {
        
        $searchQuery = $request->input('search');
        $departmentId = $request->input('department_id');
        $filters = $request->input('filter', ['all']);

        // Convert comma-separated filters to array
        if (is_string($filters)) {
            $filters = array_filter(explode(',', $filters));
        }

        if (empty($filters)) {
            $filters = ['all'];
        }

        $query = Faculty::query();

        // Filter by department_id if provided
        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        // Apply search filters using the same logic as the search method
        if ($searchQuery) {
            $keywords = array_filter(explode(' ', trim($searchQuery)));
            $keywordCount = count($keywords);

            $query->where(function ($q) use ($searchQuery, $keywords, $filters, $keywordCount) {
                if ($keywordCount === 1) {
                    $keyword = strtolower($keywords[0]);

                    $q->where(function ($subQuery) use ($keyword, $filters) {
                        if (in_array('all', $filters)) {
                            $subQuery->whereRaw('LOWER(id) = ?', [$keyword])
                                    ->orWhereRaw('LOWER(name) LIKE ?', ['%' . $keyword . '%'])
                                    ->orWhereRaw('LOWER(position) LIKE ?', ['%' . $keyword . '%'])
                                    ->orWhereRaw('LOWER(phone_number) LIKE ?', ['%' . $keyword . '%'])
                                    ->orWhereRaw('LOWER(email) LIKE ?', ['%' . $keyword . '%'])
                                    ->orWhereRaw('LOWER(department) LIKE ?', ['%' . $keyword . '%']);
                        } else {
                            $hasCondition = false;

                            if (in_array('id', $filters)) {
                                $subQuery->whereRaw('LOWER(id) = ?', [$keyword]);
                                $hasCondition = true;
                            }

                            if (in_array('name', $filters)) {
                                $method = $hasCondition ? 'orWhereRaw' : 'whereRaw';
                                $subQuery->$method('LOWER(name) LIKE ?', ['%' . $keyword . '%']);
                                $hasCondition = true;
                            }

                            if (in_array('position', $filters)) {
                                $method = $hasCondition ? 'orWhereRaw' : 'whereRaw';
                                $subQuery->$method('LOWER(position) LIKE ?', ['%' . $keyword . '%']);
                                $hasCondition = true;
                            }

                            if (in_array('phone', $filters)) {
                                $method = $hasCondition ? 'orWhereRaw' : 'whereRaw';
                                $subQuery->$method('LOWER(phone_number) LIKE ?', ['%' . $keyword . '%']);
                                $hasCondition = true;
                            }

                            if (in_array('email', $filters)) {
                                $method = $hasCondition ? 'orWhereRaw' : 'whereRaw';
                                $subQuery->$method('LOWER(email) LIKE ?', ['%' . $keyword . '%']);
                                $hasCondition = true;
                            }

                            if (in_array('department', $filters)) {
                                $method = $hasCondition ? 'orWhereRaw' : 'whereRaw';
                                $subQuery->$method('LOWER(department) LIKE ?', ['%' . $keyword . '%']);
                            }
                        }
                    });
                } else {
                    // Multi-word: exact name match
                    if (in_array('all', $filters) || in_array('name', $filters)) {
                        $q->whereRaw('LOWER(name) = ?', [strtolower($searchQuery)]);
                    } else {
                        foreach ($keywords as $index => $keyword) {
                            if (empty($keyword)) continue;
                            $method = $index === 0 ? 'where' : 'orWhere';

                            $q->$method(function ($subQuery) use ($keyword, $filters) {
                                $keyword = strtolower($keyword);
                                $hasCondition = false;

                                if (in_array('id', $filters)) {
                                    $subQuery->whereRaw('LOWER(id) = ?', [$keyword]);
                                    $hasCondition = true;
                                }

                                if (in_array('name', $filters)) {
                                    $method = $hasCondition ? 'orWhereRaw' : 'whereRaw';
                                    $subQuery->$method('LOWER(name) LIKE ?', ['%' . $keyword . '%']);
                                    $hasCondition = true;
                                }

                                if (in_array('position', $filters)) {
                                    $method = $hasCondition ? 'orWhereRaw' : 'whereRaw';
                                    $subQuery->$method('LOWER(position) LIKE ?', ['%' . $keyword . '%']);
                                    $hasCondition = true;
                                }

                                if (in_array('phone', $filters)) {
                                    $method = $hasCondition ? 'orWhereRaw' : 'whereRaw';
                                    $subQuery->$method('LOWER(phone_number) LIKE ?', ['%' . $keyword . '%']);
                                    $hasCondition = true;
                                }

                                if (in_array('email', $filters)) {
                                    $method = $hasCondition ? 'orWhereRaw' : 'whereRaw';
                                    $subQuery->$method('LOWER(email) LIKE ?', ['%' . $keyword . '%']);
                                    $hasCondition = true;
                                }

                                if (in_array('department', $filters)) {
                                    $method = $hasCondition ? 'orWhereRaw' : 'whereRaw';
                                    $subQuery->$method('LOWER(department) LIKE ?', ['%' . $keyword . '%']);
                                }
                            });
                        }
                    }
                }
            });
        }

        $faculty = $query->get();
        $searchTerm = $searchQuery ?? 'All Faculty';
        $exportDate = now()->format('Y-m-d H:i:s');
        $totalResults = $faculty->count();

        // Determine search type for display
        $searchType = 'Simple Search';
        if ($searchQuery && (strpos($searchQuery, 'AND') !== false || strpos($searchQuery, 'OR') !== false || strpos($searchQuery, 'NOT') !== false)) {
            $searchType = 'Advanced Search (Boolean)';
        }

        // Generate PDF
        $pdf = Pdf::loadView('admin.faculty.faculty-search-pdf', compact(
            'faculty', 
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
        
        $filename = 'faculty-search-results-' . date('Y-m-d-H-i-s') . '.pdf';
        
        return $pdf->download($filename);
    }
}


    