<?php

namespace App\Http\Controllers\Admin;

use App\BirthdayCard;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class BirthdayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->isMethod('post')){
            $keyword = $request->get('search');
            if(!empty($keyword)){
                $users = User::whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%".$keyword."%"])->pluck('id')->toArray();
                $birthday_cards = BirthdayCard::whereIn('user_id',$users)->with('user');
            }else{
                $birthday_cards = BirthdayCard::with('user');
            }
            $view = 'admin.birthdays.search';
        }else{
            $birthday_cards = BirthdayCard::with('user');
            $view = 'admin.birthdays.index';
        }
        $birthday_cards = $birthday_cards->paginate(10);
        return view($view, compact('birthday_cards'));
    }

    public function birthdayCardList(Request $request)
    {
        $columns = array(
            0 => 'employee_name',
            1 => 'birthday_date',
            2 => 'birthday_card',
            3 => 'status',
            4 => 'action'
        );
        $order_by = '';
        $start_date = '';
        $end_date = '';
        $search = '';
        $limit = $request->input('length');
        $start = $request->input('start');
        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
        }
        $data = array();

        $cards = BirthdayCard::with('user');
        if($request->input('search.value')){
            $users = User::whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%".$search."%"])->pluck('id')->toArray();
            $cards = BirthdayCard::whereIn('user_id',$users)->with('user');
        }
        $base_url = env('APP_URL');
        $totalData = 0;
        $totalFiltered = 0;
        $totalData = $cards->count();
        $totalFiltered = $totalData;
        $birthday_cards = $cards->offset($start)->limit($limit)->orderBy('id', 'DESC')->get();

        if($birthday_cards->count() > 0){
            foreach ($birthday_cards as $key => $value) {
                $nested_data['employee_name'] = $value->user->first_name.' '.$value->user->last_name ;
                $nested_data['birthday_date'] = Carbon::parse($value->birthday_date)->format('d-m-Y') ;
                $nested_data['birthday_card'] = '<img src="'.$base_url.'/images/birthday_cards/'.$value->birthday_card.'" width="100" height="100">';
                if($value->status == 1){
                    $status = 'Active';
                    $change_status = 0;
                    $icon = 'fa fa-times text-danger';
                    $title = 'Block Card';
                    $class = 'text-success';
                }
                else{
                    $change_status = 1;
                    $status = 'Block';
                    $icon = 'fa fa-check text-success';
                    $title = 'Activate Card';
                    $class = 'text-danger';
                }
                $nested_data['status'] = $status;
                $nested_data['action'] = '<a href="'.$base_url.'/admin/birthday/'.encrypt($value->id).'"/edit" title="Edit Card"><i class="fa fa-edit"></i>'.
                                        '</a> &nbsp'.
                                        '<a href="'.$base_url.'/admin/birthday/destroy/'.encrypt($value->id).'" title="Delete Card" onclick="return confirm();"><i class="fa fa-trash"></i>'.
                                        '</a> &nbsp'.
                                        '<a href="'.$base_url.'/admin/birthday/status/'.encrypt($value->id).'/'.$change_status.'" title="'.$title.'" ><i class="'.$icon.'"></i>'.
                                        '</a>';
                $data[] = $nested_data;
            }
        }
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );
        return response()->json($json_data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('is_deleted','=',0)->where('status','=',1)->get();
        return view('admin.birthdays.create', compact('users'));
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
        $validator = Validator::make($data, BirthdayCard::saveCard());
        $data = [
            'user_id' => $request->employee_id,
            'birthday_date' => Carbon::parse($request->birthday_date)->format('Y-m-d')
        ];
        if ($request->hasFile('birthday_card')) {
            $photo = $request->file('birthday_card');
            $imageName = $request->employee_id.'_birthday_' . time() . '.' . $photo->getClientOriginalExtension();
            $request->file('birthday_card')->move(public_path('images/birthday_cards/'), $imageName);
            $data['birthday_card'] = $imageName;
        }
        BirthdayCard::create($data);
        return redirect('/admin/birthday')->with('flash_message', 'Card added successfully!');
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
        $card_details = BirthdayCard::where('id','=',$card_id)->first();
        $users = User::where('is_deleted','=',0)->where('status','=',1)->get();
        return view('admin.birthdays.edit', compact('card_details','users'));
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
        $card_id = decrypt($id);
        $data = [
            'user_id' => $request->employee_id,
            'birthday_date' => Carbon::parse($request->birthday_date)->format('Y-m-d')
        ];
        if ($request->hasFile('birthday_card')) {
            $photo = $request->file('birthday_card');
            $imageName = $request->employee_id.'_birthday_' . time() . '.' . $photo->getClientOriginalExtension();
            $request->file('birthday_card')->move(public_path('images/birthday_cards/'), $imageName);
            $data['birthday_card'] = $imageName;
        }
        BirthdayCard::where('id',$card_id)->update($data);
        return redirect('/admin/birthday')->with('flash_message', 'Card updated successfully!');
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
        BirthdayCard::where('id','=',$card_id)->delete();
        return redirect('/admin/birthday')->with('flash_message', 'Birthday Card deleted successfully!');
    }

    public function changeStatus($id, $status)
    {
        $card_id = decrypt($id);
        BirthdayCard::where('id','=',$card_id)->update(['status' => $status]);
        $msg = 'Birthday Card Activated Successfully';
        if($status == 0){
            $msg = 'Birthday Card Blocked Successfully';
        }
        return redirect('/admin/birthday')->with('flash_message', $msg);
    }

}
