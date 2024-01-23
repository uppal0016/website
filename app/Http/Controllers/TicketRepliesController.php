<?php

namespace App\Http\Controllers;

use App\AttachedFile;
use App\TicketReply;
use App\Ticket;
use App\ItTicket;
use App\User;
use App\ITTicketHistory;
use App\TicketHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use RahulHaque\Filepond\Facades\Filepond;
use App\Jobs\SendItTicketStatusJob;

class TicketRepliesController extends Controller
{
    public function store(Request $request){

        $this->validate($request, [
            'reply' => 'required',
            'change_status' => 'required'
        ]);


        $reply = new TicketReply;
        $ticket = new Ticket ; 
        $ticket_history = new ITTicketHistory;
        $ticket_history_harmony = new TicketHistory;


        $reply->reply = $request->reply;
        $reply->ticket_id = $request->ticket_id;
        $reply->user_id = Auth::user()->id;

        if ($request->has('it_ticket')) {
            $ticket_detail = ItTicket::where('ticket_id', $request->ticket_id)->first();

            $user = User::where('id', $ticket_detail->user_id)->first();

            if ($ticket_detail->status == "Open" && Auth::user()->email !== $user->email) {
                $ticket_detail->status = $request->change_status;
                $ticket_detail->save();
            } else if ($request->change_status == "Reopen") {
                $ticket_detail->reopen_time = Carbon::now();
                $ticket_detail->turnaround_time = null;
                $ticket_detail->status = "Reopen";
                $ticket_detail->save();
            } elseif ($request->change_status == "Closed") {
    
                if ($ticket_detail->status !== "Reopen") {
                    $diff = $ticket_detail->created_at->diff(Carbon::now());
                    $ticket_detail->turnaround_time = $diff->format('%H:%I:%S');
                    $ticket_detail->status = "Closed";
                    $ticket_detail->save();
                } else if ($ticket_detail->status == "Reopen") {
                    $carbon_reopen_time = Carbon::parse($ticket_detail->reopen_time);
                    $diff_reopen_time =  $carbon_reopen_time->diff(Carbon::now());
                    $ticket_detail->turnaround_time = $diff_reopen_time->format('%H:%I:%S');
                    $ticket_detail->status = "Closed";
                    $ticket_detail->save();
                }
            } else {
                $ticket_detail->status = $request->change_status;
                $ticket_detail->save();
            }
        } else {
            $ticket_detail = Ticket::where('ticket_id', $request->ticket_id)->first();
            $user = User::where('id',$ticket_detail->user_id)->first();
          
            if($ticket_detail->status == "Open" && Auth::user()->email !== $user->email){
                $ticket_detail->status = $request->change_status;
                $ticket_detail->save();
            }else {
                $ticket_detail->status = $request->change_status;
                $ticket_detail->save();
            }
        }

        $reply->reply = Crypt::encrypt($reply->reply);
        $reply->ticket_status = $request->change_status;

        if ($request->gallery) {
            $it_tickets_attachments = 'it_tickets_replies_attachments-' . time();
            $fileInfos = Filepond::field($request->gallery)
            ->moveTo('it_tickets_replies_attachments/' . $it_tickets_attachments);
        }

 
        $reply->ticket_status = $request->change_status;
        

        if($reply->save()) {
     
            if(isset($fileInfos)){
                foreach($fileInfos as $file){
                    $attached_files = new AttachedFile([
                        'user_id' => Auth::user()->id,
                        'reply_id' =>  $reply->id,
                        'basename' => $file['basename'],
                        'dirname' => $file['dirname'],
                        'extension' => $file['extension'],
                        'url' => $file['url']
                    ]);  
                    $attached_files->save();
            }
            }

            $columnName = \DB::table('ticket_replies')
                ->where('ticket_id', $ticket_detail->ticket_id)
                ->latest()
                ->value('ticket_id');
                $columnName = substr($columnName, 0, 7);
                if($columnName == "TLGT-HC"){
                    $ticket_history_harmony->ticket_id = $ticket_detail->id;
                    $ticket_history_harmony->user_id = Auth::user()->id;
                    $ticket_history_harmony->reply_id = $reply->id;
                    $ticket_history_harmony->ticket_status = $request->change_status;
                    $ticket_history_harmony->save();
                    $emails = [$user->email,'harmony@talentelgia.in'];
                    try {
                        foreach ($emails as $key => $email) {
                            Mail::send('mails.ticket_status_mail', [
                                'ticketStatus' => $request->change_status,
                                'userName' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                                'ticketNumber' => $ticket_detail->ticket_id,
                            ], function ($message) use ($request, $email, $ticket_detail) {
                                $message->to($email);
                                $message->subject($ticket_detail->ticket_id . ' : ' . $request->change_status);
                            });
                        }
                      
                    } catch (\Exception $e) {
                    }
                }else{
                    $ticket_history->ticket_id = $ticket_detail->id;
                    $ticket_history->user_id = Auth::user()->id;
                    $ticket_history->reply_id = $reply->id;
                    $ticket_history->ticket_status = $request->change_status;
                    $ticket_history->save();
                    $emails = [$user->email,'gautam.uppal@talentelgia.in' , 'rohit.gupta@talentelgia.in'];
                    $user_name = Auth::user()->first_name . ' ' . Auth::user()->last_name ;
                    dispatch(new SendItTicketStatusJob($emails, $request->change_status , $ticket_detail->ticket_id ,  $user_name));  
                }
            if($request->change_status !== "Archive"){
                return redirect()->back()->with("flash_message", "Reply added successfully");
            }else {
                return redirect()->back()->with("flash_message", "A ticket with ID: #$ticket_detail->ticket_id has been Archived.");
            }   

        } else {
            return redirect()->back()->with("success", "Something went wrong.Please try again later");
        }
    }

    public function edit(Request $request)
    {
        
        $this->validate($request, [
            'reply_edit' => 'required|max:500',
        ]);

        $reply = TicketReply::find($request->input('reply_id'));
        $reply->reply = $request->reply_edit;
        $reply->reply = Crypt::encrypt($reply->reply);
    
        if ($request->edit_replygallery) {
        
            $it_tickets_attachments = 'it_tickets_replies_attachments-' . time();

            $edit_gallery = $request->edit_replygallery;
            $fileInfos = Filepond::field($edit_gallery)
            ->moveTo('it_tickets_replies_attachments/' . $it_tickets_attachments);

        }

   
        if( $reply->save()) {
        
            $attached_files = new AttachedFile;

            if(isset($fileInfos)){
                foreach($fileInfos as $file){
                    $attached_files = new AttachedFile([
                        'user_id' => Auth::user()->id,
                        'reply_id' =>  $reply->id,
                        'basename' => $file['basename'],
                        'dirname' => $file['dirname'],
                        'extension' => $file['extension'],
                        'url' => $file['url']
                    ]);  
                    $attached_files->save();
            }
           }
            return redirect()->back()->with("flash_message", "Reply updated successfully");
        } else {
            return redirect()->back()->with("success", "Something went wrong.Please try again later");
        }
    }   


    public function delete(Request $request){
        $id = Crypt::decrypt($request->route('id'));

        $attached_file = new AttachedFile;
        $reply = TicketReply::find($id);
        $reply_id = $reply->id;
        $reply_ticket_id = $reply->ticket_id; 
        $ticket_id = substr($reply->ticket_id, 0, 7);
        $attached_file->where('reply_id',$reply_id)->delete();

        if($reply->delete()) {
            $tickethistory = TicketReply::where('ticket_id', $reply_ticket_id)->latest()->get()->first();
            $prev_status =   $tickethistory ? $tickethistory->ticket_status : 'Open';
            if($ticket_id == "TLGT-IT"){
                $ticket_detail = ItTicket::where('ticket_id', $reply->ticket_id)->first();
                $user = User::where('id',$ticket_detail->user_id)->first();
                $emails = [$user->email,'gautam.uppal@talentelgia.in' , 'rohit.gupta@talentelgia.in'];
                $user_name = Auth::user()->first_name . ' ' . Auth::user()->last_name ;
                dispatch(new SendItTicketStatusJob($emails,$prev_status, $ticket_detail->ticket_id,$user_name));  
    
            }else {
                $ticket_detail = Ticket::where('ticket_id', $reply->ticket_id)->first();
                $user = User::where('id',$ticket_detail->user_id)->first();
                $emails = [$user->email,'harmony@talentelgia.in'];
                try {
                    foreach ($emails as $key => $email) {
                        Mail::send('mails.ticket_status_mail', [
                            'ticketStatus' => $prev_status,
                            'userName' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                            'ticketNumber' => $ticket_detail->ticket_id,
                        ], function ($message) use ($prev_status, $email, $ticket_detail) {
                            $message->to($email);
                            $message->subject($ticket_detail->ticket_id . ' : ' . $prev_status);
                        });
                    }
                  
                } catch (\Exception $e) {
                }
            }
    
            $attached_files = AttachedFile::where('reply_id', $id)->get();
    
            foreach($attached_files as $attach_file){  
                $attach_file->delete();         
            }

           
            if($tickethistory){
                $ticket_detail->status =  $tickethistory->ticket_status;
                $ticket_detail->save();
            }else {
                $ticket_detail->status = 'Open';
                $ticket_detail->save();
            }

            return redirect()->back()->with("flash_message", "Reply deleted successfully");
        }else{
            return redirect()->back()->with("success", "Something went wrong.Please try again later");
        }
    }


    public function deleteAttachment(Request $request){
        $attachment_id = $request->route('attachment_id');
        $attachment = AttachedFile::find($attachment_id);
        if($attachment->delete()){
            $request->session()->flash('flash_message', 'Attachment deleted successfully.');
            return response()->json([
                'status' => 'success',
                'message' => 'Attachment deleted successfully.'
            ]);
        } else {
            return response()->json([
                'status' => 'Error',
                'message' => 'Something went wrong.'
            ]);
        }

    }
}
