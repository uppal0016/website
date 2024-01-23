<?php

namespace App\Http\Controllers\Admin;

use App\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $holidays = Holiday::paginate(10);
        if($request->isMethod('post')){
            $keyword = $request->get('search');
            if(!empty($keyword)){
                $holidays = Holiday::where('title','like','%'.$keyword.'%')->paginate(10);
            }else{
                $holidays = Holiday::paginate(10);
            }
            $view = 'admin.holidays.search';
        }else{
            $holidays = Holiday::paginate(10);
            $view = 'admin.holidays.index';
        }
        return view($view, compact('holidays'));
    }

    public function search(Request $request){
        $keyword = $request->get('search');
        if(!empty($keyword)){
            $holidays = Holiday::where('title','like','%'.$keyword.'%')->paginate(10);
        }else{
            $holidays = Holiday::paginate(10);
        }
        $view = 'admin.holidays.index';
        return view($view, compact('holidays'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.holidays.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, Holiday::saveHoliday());
        if($validator->fails()){
            return back()->with('error_flash_message', 'Holiday added successfully!')->withInput($request->input());    
        }
        Holiday::create([
            'title' => $request->title,
            'date' => Carbon::parse($request->date)->format('Y-m-d')
        ]);
        return redirect('/admin/holiday')->with('flash_message', 'Holiday added successfully!');
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
        $card_id = decrypt($id);
        $holiday = Holiday::where('id','=',$card_id)->first();
        return view('admin.holidays.edit', compact('holiday'));
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
        $data = $request->all();
        $validator = Validator::make($data, Holiday::saveHoliday());
        if($validator->fails()){
            return back()->with('error_flash_message', $validator->errors()->first())->withInput($request->input());    
        }

        $card_id = decrypt($id);
        $data = [
            'title' => $request->title,
            'date' => Carbon::parse($request->date)->format('Y-m-d')
        ];
        Holiday::where('id',$card_id)->update($data);
        return redirect('/admin/holiday')->with('flash_message', 'Holiday updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $card_id = decrypt($id);
        Holiday::where('id','=',$card_id)->delete();
        return redirect('/admin/holiday')->with('flash_message', 'Holiday deleted successfully!');
    }

    public function changeStatus($id, $status)
    {
        $card_id = decrypt($id);
        Holiday::where('id','=',$card_id)->update(['status' => $status]);
        $msg = 'Holiday Activated Successfully';
        if($status == 0){
            $msg = 'Holiday Blocked Successfully';
        }
        return redirect('/admin/holiday')->with('flash_message', $msg);
    }
}
