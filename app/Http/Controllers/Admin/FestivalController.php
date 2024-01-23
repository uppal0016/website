<?php

namespace App\Http\Controllers\Admin;

use App\FestivalCard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class FestivalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $festival_cards = FestivalCard::paginate(10);
        if($request->isMethod('post')){
            $keyword = $request->get('search');
            if(!empty($keyword)){
                $festival_cards = FestivalCard::where('title','like','%'.$keyword.'%')->paginate(10);
            }else{
                $festival_cards = FestivalCard::paginate(10);
            }
            $view = 'admin.festivals.search';
        }else{
            $festival_cards = FestivalCard::paginate(10);
            $view = 'admin.festivals.index';
        }
        return view($view, compact('festival_cards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.festivals.create');
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
        $validator = Validator::make($data, FestivalCard::saveCard());
        if ($request->hasFile('festival_card')) {
            $photo = $request->file('festival_card');
            $imageName = $request->title.'_festival_' . time() . '.' . $photo->getClientOriginalExtension();
            $request->file('festival_card')->move(public_path('images/festival_cards/'), $imageName);
            $data['festival_card'] = $imageName;
        }
        FestivalCard::create([
            'title' => $request->title,
            'festival_date' => Carbon::parse($request->festival_date)->format('Y-m-d'),
            'festival_card' => $imageName
        ]);
        return redirect('/admin/festival')->with('flash_message', 'Card added successfully!');
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
        $card_details = FestivalCard::where('id','=',$card_id)->first();
        return view('admin.festivals.edit', compact('card_details'));
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
            'title' => $request->title,
            'festival_date' => Carbon::parse($request->festival_date)->format('Y-m-d')
        ];
        if ($request->hasFile('festival_card')) {
            $photo = $request->file('festival_card');
            $imageName = $request->title.'_festival_' . time() . '.' . $photo->getClientOriginalExtension();
            $request->file('festival_card')->move(public_path('images/festival_cards/'), $imageName);
            $data['festival_card'] = $imageName;
        }
        FestivalCard::where('id',$card_id)->update($data);
        return redirect('/admin/festival')->with('flash_message', 'Card updated successfully!');
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
        FestivalCard::where('id','=',$card_id)->delete();
        return redirect('/admin/festival')->with('flash_message', 'Festival Card deleted successfully!');
    }

    public function changeStatus($id, $status)
    {
        $card_id = decrypt($id);
        FestivalCard::where('id','=',$card_id)->update(['status' => $status]);
        $msg = 'Festival Card Activated Successfully';
        if($status == 0){
            $msg = 'Festival Card Blocked Successfully';
        }
        return redirect('/admin/festival')->with('flash_message', $msg);
    }
}
