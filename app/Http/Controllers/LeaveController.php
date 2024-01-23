<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Leave;
use DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\Storage;
use Crypt;
use Carbon\Carbon;
use App\User;
use App\Jobs\SendLeaveEmailJob;
use Illuminate\Support\Facades\Log;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $leave = Leave::whereUserId(Auth::user()->id);
        $status = trim($request->input('status'));
        
        if(!empty($request->input('daterange')))
        {
            $dateRange = explode('-', $request->input('daterange'));
            $start_date = Carbon::parse($dateRange[0])->format('Y-m-d');
            $end_date = Carbon::parse($dateRange[1])->format('Y-m-d');
            $leave = $leave->where('start_date', '>=',$start_date)
                            ->where('end_date','<=', $end_date);
        } 

        if(is_numeric($status)){
            $leave = $leave->where('status',$status);
            
        }
        $leave = $leave->orderBy('id', 'desc')
                        ->paginate(10);

        $leave->getCollection()->transform(function ($value) {
            $value->en_id = Crypt::encrypt($value->id);
            return $value;
        });

        if($request->ajax())
        {
            return view('leave.search', compact('leave'));
        }

        return view('leave.index', compact('leave'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('leave.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try{
            $data = $request->only('title','start_date','end_date', 'type','description','leave_time');
            $validator = Validator::make($data, [
                'title' => 'required',
                'start_date' => 'required',
                'description' => 'required',
                'type' => 'required'
            ]);
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput($request->input());
            }
            $data['user_id'] = Auth::user()->id;
            if ($request->hasFile('attachment')) {
                $attachment = $request->file('attachment');
                $imageName = Auth::user()->id . '_leave' . time() . '.' . $attachment->getClientOriginalExtension();
                $filePath = 'public/leave-attachment/' . $imageName;
                Storage::disk('local')->put($filePath, file_get_contents($attachment), 'public');
                $data['attachment'] = basename($imageName) ;
                $data['attachment_size'] = $attachment->getSize();
                $data['attachment_type'] = $attachment->getClientMimeType();
            }
            $leave = Leave::create($data);
            if($leave){
                $user = User::whereId(Auth::id())->first();

                $details=[
                    "email"   =>  $user->email,
                    "subject" =>  'Leave Applied Notification',
                    "name"    =>  $user->first_name.' '.$user->last_name,
                    "type"    =>  'employee',
                    "view"    =>  'mails.leave-email',
                    "emp_code"     =>  $user->employee_code,
                    'leave' => $leave,
                    'receiver' => $user
                 ];
                dispatch(new SendLeaveEmailJob($details));

                $management = User::whereIn('role_id',['2','3','5'])->get();
                if(!empty($management)){
                    foreach($management  as $manage){
                        Log::info('sending email to management');
                        $details['email'] = $manage->email;
                        $details['type'] = 'manager';
                        $details['receiver'] = $manage;
                        dispatch(new SendLeaveEmailJob($details));
                    }
                }
                return redirect('/leave')->with('flash_message', 'Leave created successfully!');
            }else{
                return redirect()->back()->with('flash_message', 'There is something wrong. Please try again.');
            }
        }catch(\Exception $e){
            dd($e);exit;   
            return redirect()->back()->with('flash_message', $e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dec_id = \Crypt::decrypt($id);
        $data = Leave::findOrFail($dec_id);
        $data['en_id'] =$id;
        return view('leave.add',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $id = \Crypt::decrypt($id);
            $data = $request->only('title','start_date','end_date', 'type','description','leave_time');
            $validator = Validator::make($data, [
                'title' => 'required',
                'start_date' => 'required',
                'description' => 'required',
                'type' => 'required'
            ]);
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput($request->input());
            }
            if ($request->hasFile('attachment')) {
                $attachment = $request->file('attachment');
                $imageName = Auth::user()->id . '_leave' . time() . '.' . $attachment->getClientOriginalExtension();
                $filePath = 'public/leave-attachment/' . $imageName;
                Storage::disk('local')->put($filePath, file_get_contents($attachment), 'public');
                $data['attachment'] = basename($imageName) ;
                $data['attachment_size'] = $attachment->getSize();
                $data['attachment_type'] = $attachment->getClientMimeType();
            }
            $leave = Leave::whereId($id)->update($data);
            if($leave){
                return redirect('/leave')->with('flash_message', 'Leave created successfully!');
            }else{
                return redirect()->back()->with('flash_message', 'There is something wrong. Please try again.');
            }
            }catch(\Exception $e){
                dd($e);exit;   
                return redirect()->back()->with('flash_message', $e);
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cancelStatus($id, $status)
    {
        $id = Crypt::decrypt($id);
        $Leave = Leave::findOrFail($id);
        $curdate=strtotime(new Date());
        $start_date=strtotime($Leave->start_date);
        if($start_date < $curdate){
            $Leave->status=$status;
            $Leave->update();
            return redirect()->back()->with('flash_message', 'Leave status changed successfully!');
        } 
        return redirect()->back()->with('flash_message', 'You are not able to change the leave status');
      
    }

}
