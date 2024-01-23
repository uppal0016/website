<?php

namespace App\Http\Controllers;

use App\Department;
use App\Helpers\Helper;
use App\Reference;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReferenceController extends Controller
{
    public function __construct()
    {
        $this->title = "Rapper";
    }

    public function index(Request $request, $user_type = 'employee')
    {
        if ($request->ajax()) {
            $filters = [
                'start_date' => $request->query('start_date') ?? null,
                'end_date' => $request->query('end_date') ?? null,
                'name_search' => $request->query('name_search') ?? null,
                'technology_search' => $request->query('technology_search') ?? null,
            ];
    
            if (Auth::user()->email == 'chandni.rana@talentelgia.in' || Auth::user()->email == 'chahat.malhotra@talentelgia.in' || Auth::user()->email == 'nisha.kaur@talentelgia.in') {
                $reference = $this->getReference($filters, 'HR');
                return view('reference.search', compact('reference'));
            } else {
                $reference = $this->getReference($filters, 'employee');
                return view('reference.search', compact('reference'));
            }
        } else {
            $filters = [
                'start_date' => $request->query('start_date') ?? null,
                'end_date' => $request->query('end_date') ?? null,
                'name_search' => $request->query('name_search') ?? null,
                'technology_search' => $request->query('technology_search') ?? null,
            ];
    
            if (Auth::user()->email == 'chandni.rana@talentelgia.in' || Auth::user()->email == 'chahat.malhotra@talentelgia.in' || Auth::user()->email == 'nisha.kaur@talentelgia.in') {
                $reference = $this->getReference($filters, 'HR');
                if (isset($reference)) {
                    return view('reference.index', compact('reference'));
                } else {
                    $reference = $this->getReference([], 'HR');
                    return view('reference.index', compact('reference'));
                }
            } else {
                $reference = $this->getReference($filters, 'employee');
                if (isset($reference)) {
                    return view('reference.index', compact('reference'));
                } else {
                    $reference = $this->getReference([], 'employee');
                    return view('reference.index', compact('reference'));
                }
            }
        }
    }

    private function getReference($filters = [], $user_type = 'employee')
    {
        $reference = Reference::query();
        if (isset($filters['name_search'])) {
            $users = Reference::whereraw("reference_name LIKE ?", ["%" . $filters['name_search'] . "%"])->get();
            if ($users->count() > 0) {
                $reference->whereIn('id', $users->pluck('id'));
            } else {
                $reference->where('id',  null);
            }
        }
    
        if (isset($filters['technology_search'])) {
            $technology_search = $filters['technology_search'];
            $departmentIds = $reference->pluck('department');
            $departmentNames = Department::whereIn('id', $departmentIds)->pluck('name');
    
            $departments = $departmentNames->filter(function ($name) use ($technology_search) {
                return strpos(strtolower($name), strtolower($technology_search)) !== false;
            });
    
            if ($departments->count() > 0) {
                $departmentIds = Department::whereIn('name', $departments)->pluck('id');
                $reference->whereIn('department', $departmentIds);
            } else {
                $reference->where('id', null);
            }
        }
    
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $start_date = $filters['start_date'] . ' 00:00:00';
            $end_date = $filters['end_date'] . ' 23:59:59';
            $reference->whereBetween('created_at', [$start_date, $end_date]);
        }
    
        $reference = $reference->where('employee_id', Auth::user()->id)->latest()->paginate(10);
    
        foreach ($reference as $value) {
            $value->department = Department::where('id', $value->department)->value('name');
            $user = User::where('id', $value->employee_id)->first();
            $value->emp_name = $user->first_name . ' ' . $user->last_name;
            $value->email = User::where('id', $value->employee_id)->value('email');
            $value->employee_code = User::where('id', $value->employee_id)->value('employee_code');
            $value->department_name = Department::where('id', $user->department_id)->value('name');
        }
        return $reference;
    }

    public function add()
    {
        $dept = Department::get();
        return view('reference.create', compact('dept'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'mobile_number' => 'required|numeric|min:10',
            'department' => 'required',
            'experience' => 'required',
            'resume' => 'required',
            'interview_status' => "Pending",
            'reference_platform' => 'required',
        ]);
        $fullName = $request->input('first_name') . ' ' . $request->input('last_name');

        $originalFilename = $request->file('resume')->getClientOriginalName();
        $filename = str_replace(' ', '_', $originalFilename);
        $resumePath = $request->file('resume')->storeAs('public/resumes', $filename);

        $resumeUrl = asset('storage/resumes/' . $filename);

        $reference = new Reference([
            'employee_id' => Auth::user()->id,
            'reference_name' => $fullName,
            'mobile_number' =>  $request->input('mobile_number'),
            'department' =>  $request->input('department'),
            'experience' =>  $request->input('experience'),
            'resume' => $filename,
            'resume_url' => $resumeUrl,
            'reference_platform' => $request->input('reference_platform'),
            'rejection_reason' => $request->input('rejection_reason', null),
            'rejected_employee_id' => null
        ]);
        $reference->save();
        $employeeCode = User::where('id', $reference->employee_id)->pluck('employee_code')->first();
        $reference->employee_code = $employeeCode;        

        if(env('SYNC_RAPPER') == true){   
            Helper::rapper();
        }
        return redirect('/reference/list')->with("flash_message", "Rapper Added Successfully !");
    }

    public function edit(Request $request)
    {
        $id = $request->route('id');
        $reference = new Reference();
        $reference = $reference->where('id', $id)->first();
        $dept = Department::get();

        // to get first name and last name different from the full name
        $reference_name = $reference->reference_name;
        $nameArray = explode(" ", $reference_name);
        $reference->last_name = array_pop($nameArray);
        $reference->first_name = implode(" ", $nameArray);
        return view('reference.edit', ['reference' => $reference, 'dept' => $dept]);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'mobile_number' => 'required|numeric|min:10',
            'department' => 'required',
            'experience' => 'required',
            'interview_status' => "Pending",
            'reference_platform' => 'required',
        ]);

        $id = $request->input('reference_id');
        $reference = Reference::find($id);
        $fullName = $request->input('first_name') . ' ' . $request->input('last_name');

        $reference->reference_name = $fullName;
        $reference->mobile_number = $request->input('mobile_number');
        $reference->department = $request->input('department');
        $reference->experience = $request->input('experience');

        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $originalFilename = $file->getClientOriginalName();
            $filename = str_replace(' ', '_', $originalFilename);
            $file->move(public_path('storage/resumes'), $filename);
            $reference->resume = $filename;
        }
        $reference->reference_platform = $request->input('reference_platform');
        $reference->save();
        $employeeCode = User::where('id', $reference->employee_id)->pluck('employee_code')->first();
        $reference->employee_code = $employeeCode;

        if(env('SYNC_RAPPER') == true){   
            Helper::rapper();
        }
        return redirect('/reference/list')->with("flash_message", "Rapper updated successfully!");
    }

    public function delete(Request $request)
    {
        $reference_id = $request->route('id');
        $reference = new Reference;
        $reference = $reference->where('id', $reference_id)->first();
        if ($reference->delete()) {
            return redirect('/reference/list')->with("flash_message", "Rapper deleted successfullly!");
        } else {
            return redirect('/reference/list')->with("error", "Something went wrong. Please try again.");
        }
    }

    public function getCommentsById(Request $request, $id)
    {
        try {
            $reference = Reference::find($id);
            if (!$reference) {
                return response()->json(['error' => 'Reference not found'], 404);
            }
            
            return response()->json(['reference' => $reference], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        $reference = Reference::find($id);        
    }

    public function storeComment(Request $request)
    {
        try {
            $referenceId = $request->input('referenceId');
            $comment = $request->input('comment');
    
            // Retrieve the existing reference by ID
            $existingReference = Reference::find($referenceId);
    
            if (!$existingReference) {
                return response()->json(['error' => 'Reference not found'], 404);
            }
    
            // Update the comment and save
            $existingReference->rejection_reason = $comment;
            $existingReference->rejected_employee_id = Auth::id();
            $existingReference->interview_status = "Rejected";
            $existingReference->save();
    
            // return redirect('/reference/list')->with("flash_message", "Review created successfully!");
            return response()->json([
                'message' => 'Rejection reason added successfully!',
                'redirect' => '/reference/list' // Specify the redirect URL
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }   

    public function rejectionReason($id)
    {
        $reference = Reference::findOrFail($id);
        $first_name = User::where('id', $reference->rejected_employee_id)->value('first_name');
        $last_name = User::where('id', $reference->rejected_employee_id)->value('last_name');
        $reference->rejected_employee_id = $first_name . ' ' . $last_name;
        return response()->json([
            'rejection_reason' => $reference->rejection_reason,
            'rejected_employee_id' => $reference->rejected_employee_id,
        ]);
    }

    public function getEmployeeById($id)
    {
        $reference = Reference::find($id);
        if (!$reference) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        $user = User::where('id', $reference->employee_id)->first();
        $reference->emp_name = $user->first_name . ' ' . $user->last_name;
        $reference->employee_code = User::where('id', $reference->employee_id)->value('employee_code');
        $reference->email = User::where('id', $reference->employee_id)->value('email');
        $reference->department_name = Department::where('id', $user->department_id)->value('name');

        return response()->json(['reference' => $reference], 200);
    }

    // for api calling
    public function rapper_candidate(Request $request)
    {
        $id = $request->input('mySqlId');

        $rapperCandidate = Reference::find($id);

        if (!$rapperCandidate) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $rapperCandidate->id = request('mySqlId');
        $rapperCandidate->rounds = request('rounds');
        if (request('interview_status') === "Scheduled"){
            $rapperCandidate->interview_status = "Scheduled and Ongoing";
        }
        $rapperCandidate->recommendation = request('recommendation');
        if(request('recommendation') === 'Candidate_not_available'){
            $rapperCandidate->recommendation = 'Candidate not available';
        }

        if (request('rejection_reason') !== null){
            $rapperCandidate->interview_status = "Rejected";
            $rapperCandidate->rejected_employee_id = request('rejected_employee_id');
            $rapperCandidate->rejection_reason = request('rejection_reason');
        }

        if (request('cancel_reason') !== null){
            $rapperCandidate->interview_status = "Cancelled";
            $rapperCandidate->cancel_employee_id = request('cancel_employee_id');
            $rapperCandidate->cancel_reason = request('cancel_reason');
        }

        $rapperCandidate->update([$rapperCandidate->id, $rapperCandidate->rounds, $rapperCandidate->interview_status, $rapperCandidate->recommendation, $rapperCandidate->rejected_employee_id, $rapperCandidate->rejection_reason, $rapperCandidate->cancel_employee_id, $rapperCandidate->cancel_reason]);
        return response()->json(['message' => 'Record updated successfully']);
    }

    public function cancelReason($id)
    {
        $reference = Reference::findOrFail($id);
        $first_name = User::where('id', $reference->cancel_employee_id)->value('first_name');
        $last_name = User::where('id', $reference->cancel_employee_id)->value('last_name');
        $reference->cancel_employee_id = $first_name . ' ' . $last_name;
        return response()->json([
            'cancel_reason' => $reference->cancel_reason,
            'cancel_employee_id' => $reference->cancel_employee_id,
        ]);
    }
}
