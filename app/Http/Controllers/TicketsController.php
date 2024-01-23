<?php

namespace App\Http\Controllers;

use App\AttachedFile;
use Illuminate\Http\Request;
use App\Ticket;
use App\TicketReply;
use App\Mail\ticketCreated;
use App\User;
use App\HarmonyTicketsCategory;
use App\TicketEditHistory;
use App\OtherCategoriesHarmonyTicket;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use RahulHaque\Filepond\Facades\Filepond;

class TicketsController extends Controller
{
    public function __construct()
    {
        $this->title = "Tickets";
    }
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $filters = [
                'status' => $request->query('status') ?? null,
                'date' => $request->query('date') ?? null,
                'category_id' => $request->query('category_id') ?? null,
                'start_date' => $request->query('start_date') ?? null,
                'end_date' => $request->query('end_date') ?? null,
                'search' => $request->query('search') ?? null,
            ];

            if (Auth::user()->email == 'manish.chopra@talentelgia.in' || Auth::user()->email == 'pallavi.ranjan@talentelgia.in' || Auth::user()->email == 'rohit.gupta@talentelgia.in') {
                $tickets = $this->getTickets($filters, 'admin');
                return view('tickets.search', compact('tickets'));
            } else {
                $tickets = $this->getTickets($filters, 'employee');
                return view('tickets.search', compact('tickets'));
            }
        } else {
            $filters = [
                'status' => $request->query('status') ?? null,
                'date' => $request->query('date') ?? null,
                'category_id' => $request->query('category_id') ?? null,
                'start_date' => $request->query('start_date') ?? null,
                'end_date' => $request->query('end_date') ?? null,
                'search' => $request->query('search') ?? null,
            ];

            if (Auth::user()->email == 'manish.chopra@talentelgia.in' || Auth::user()->email == 'pallavi.ranjan@talentelgia.in' || Auth::user()->email == 'rohit.gupta@talentelgia.in') {
                $tickets = $this->getTickets($filters, 'admin');
                if (isset($tickets)) {
                    return view('tickets.index', compact('tickets'));
                } else {
                    $tickets = $this->getTickets([], 'admin');
                    return view('tickets.index', compact('tickets'));
                }
            } else {
                $tickets = $this->getTickets($filters, 'employee');
                if (isset($tickets)) {
                    return view('tickets.index', compact('tickets'));
                } else {
                    $tickets = $this->getTickets([], 'employee');
                    return view('tickets.index', compact('tickets'));
                }
            }
        }
        return view('tickets.index', compact('tickets'));
    }

    private function getTickets($filters = [], $user_type = 'employee')
    {
        $tickets = Ticket::query();
        if (isset($filters['created_at'])) {
            $tickets->whereDate('created_at', '>=', $filters['created_at'][0])
                ->whereDate('created_at', '<=', $filters['created_at'][1]);
        }

        if (isset($filters['category_id'])) {
            if ($filters['category_id'] !== "All") {
                $tickets->where('category_id', $filters['category_id']);
            }
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $start_date = $filters['start_date'] . ' 00:00:00';
            $end_date = $filters['end_date'] . ' 23:59:59';
            $tickets->whereBetween('created_at', [$start_date, $end_date]);
        }

        if (isset($filters['search'])) {
            $users = User::whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%" . $filters['search'] . "%"])->first();
            if ($users) {
                $tickets->where('user_id',  $users->id);
            } else {
                $tickets->where('user_id',  null);
            }
        }

        if (isset($filters['status'])) {
            if ($filters['status'] !== "All") {
                $tickets->where('status', $filters['status']);
            }
        }

        if ($user_type == 'admin') {
            $tickets = $tickets->latest()->paginate(10);
        } else {
            $tickets = $tickets->where('user_id', Auth::user()->id)->latest()->paginate(10);
        }

        foreach ($tickets as $ticket) {
            $ticket->reply = TicketReply::where('ticket_id', $ticket->ticket_id)->latest()->value('reply');
            $ticket->category = HarmonyTicketsCategory::where('id', $ticket->category_id)->value('name');
            $other_category_name = OtherCategoriesHarmonyTicket::where('harmony_ticket_id', $ticket->ticket_id)->value('cat_name');
            if($other_category_name &&  $ticket->category == "Other" ){
                $ticket->category =  $ticket->category.' ('.$other_category_name.')';
            }
            $ticket->user_email = User::where('id', $ticket->user_id)->value('email');
            $ticket->first_name = User::where('id', $ticket->user_id)->value('first_name');
            $ticket->employee_code = User::where('id', $ticket->user_id)->value('employee_code');
        }
        return $tickets;
    }

    public function add()
    {
        return view('tickets.addTicket', ['title' => $this->title]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required',
            'category_id' => 'required',
            'other_category' => 'max:50',
            'gallery.*' => [
                Rule::filepond([
                    'max:2000',
                ])
            ],
        ]);

        $other_id = HarmonyTicketsCategory::where('name', 'Other')->value('id');
        $ticket = new Ticket([
            'user_id' => Auth::user()->id,
            'ticket_id' => '',
            'message' => Crypt::encrypt($request->input('description')),
            'status' => "Open",
            'category_id' => $request->input('other_category') ?  $other_id : $request->input('category_id'),
            'attachment' =>  ''
        ]);

        $user = User::where('id', $ticket->user_id)->first();
        $ticket->save();
        $ticket->ticket_id = $this->generateTicketId($ticket->id);

        if ($request->input('other_category')) {
            $new_category = new OtherCategoriesHarmonyTicket([
                'user_id' => Auth::user()->id,
                'cat_name' => $request->input('other_category'),
            ]);

            $new_category->harmony_ticket_id =  $ticket->ticket_id;
            $new_category->save();
        }

        $ticket->save();

        if ($request->gallery) {
            $tickets_attachments = 'tickets_attachments-' . time();
            $fileInfos = Filepond::field($request->gallery)
                ->moveTo('tickets_attachments/' . $tickets_attachments);

            if (!empty($fileInfos)) {
                foreach ($fileInfos as $file) {
                    $attached_files = new AttachedFile([
                        'user_id' => Auth::user()->id,
                        'harmony_ticket_id' =>  $ticket->id,
                        'basename' => $file['basename'],
                        'dirname' => $file['dirname'],
                        'extension' => $file['extension'],
                        'url' => $file['url']
                    ]);
                    $attached_files->save();
                }
                // return redirect('/tickets/list')->with("flash_message", "A ticket with ID: #$ticket->ticket_id has been opened.");
            } else {
                return redirect('/tickets/list')->with("error", "Something went wrong. Please try again.");
            }
        }

        try {
            $category_name = HarmonyTicketsCategory::where('id', $ticket->category_id)->value('name');       
            
            $other_category_name = OtherCategoriesHarmonyTicket::where('harmony_ticket_id', $ticket->ticket_id)->value('cat_name');
            if($other_category_name &&  $category_name == "Other" ){
                $category_name =   $category_name.' ('.$other_category_name.')';
            }
            Mail::send('mails.ticket_mail', [
                'ticketNumber' => $ticket->ticket_id,
                'userName' => $user->first_name . ' ' . $user->last_name,
            ], function ($message) use ($request, $ticket, $category_name) {
                $message->to('harmony@talentelgia.in');
                $message->cc('mgmt@talentelgia.in');
                $message->subject('New ticket ' . $ticket->ticket_id . ' : ' . $category_name . ' created');
            });
        } catch (\Exception $e) {
        }
        return redirect('/tickets/list')->with("flash_message", "A ticket with ID: #$ticket->ticket_id has been opened.");
    }

    public function changeStatus(Request $request)
    {
        $status = $request->query('status');
        $ticket_id = $request->query('ticket_id');
        $ticket = new Ticket;
        $ticket = $ticket->where('ticket_id', $ticket_id)->first();
        $ticket->status = $status;
        if ($ticket->save()) {
            return redirect('/tickets/list')->with("flash_message", "A ticket with ID: #$ticket->ticket_id has marked .$status.");
        } else {
            return redirect('/tickets/list')->with("error", "Something went wrong. Please try again.");
        }
    }


    public function update(Request $request)
    {
        $this->validate($request, [
            'edit_description' => 'required',
            'category_id' => 'required',
            'other_category' => 'max:50',
            'gallery.*' => [
                'required',
                Rule::filepond([
                    'max:2000',
                ])
            ],
        ]);

        $ticket_id = $request->input('ticket_id');
        $ticket = new Ticket;
        $ticket_edit_history = new TicketEditHistory;
        $ticket = $ticket->where('ticket_id', $ticket_id)->first();
        $other_id = HarmonyTicketsCategory::where('name', 'Other')->value('id');
        if ($request->gallery) {
            $tickets_attachments = 'tickets_attachments-' . time();
            $fileInfos = Filepond::field($request->gallery)
                ->moveTo('tickets_attachments/' . $tickets_attachments);

                if ($request->input('other_category')) {
                    $other_exists = OtherCategoriesHarmonyTicket::where('harmony_ticket_id',$ticket->ticket_id)->first();
                    if($other_exists){
                        OtherCategoriesHarmonyTicket::where('harmony_ticket_id',$ticket->ticket_id)->update([
                            'cat_name' => $request->input('other_category'),
                        ]);
                    }else {
                        $new_category = new OtherCategoriesHarmonyTicket([
                            'user_id' => Auth::user()->id,
                            'cat_name' => $request->input('other_category'),
                        ]);
        
                        $new_category->harmony_ticket_id =  $ticket->ticket_id;
                        $new_category->save();
                    }
                }

            $ticket_edit_history::create([
                'user_id' => Auth::user()->id,
                'ticket_id' => $ticket->id,
                'message' => $ticket->message,
                'category_id' => $request->input('other_category') ?  $other_id : $request->input('category_id'),
                'attachment' => $ticket->attachment
            ]);


            $ticket->update([
                'user_id' => Auth::user()->id,
                'ticket_id' => $ticket_id,
                'message' => Crypt::encrypt($request->input('edit_description')),
                'status' =>  $ticket->status,
                'category_id' => $request->input('other_category') ?  $other_id : $request->input('category_id'),
                'attachment' =>  ''
            ]);

            foreach ($fileInfos as $file) {
                $attached_files = new AttachedFile([
                    'user_id' => Auth::user()->id,
                    'harmony_ticket_id' =>  $ticket->id,
                    'basename' => $file['basename'],
                    'dirname' => $file['dirname'],
                    'extension' => $file['extension'],
                    'url' => $file['url']
                ]);
                $attached_files->save();
            }
        } else {
            if ($request->input('other_category')) {
                $other_exists = OtherCategoriesHarmonyTicket::where('harmony_ticket_id',$ticket->ticket_id)->first();
                if($other_exists){
                    OtherCategoriesHarmonyTicket::where('harmony_ticket_id',$ticket->ticket_id)->update([
                        'cat_name' => $request->input('other_category'),
                    ]);
                }else {
                    $new_category = new OtherCategoriesHarmonyTicket([
                        'user_id' => Auth::user()->id,
                        'cat_name' => $request->input('other_category'),
                    ]);
    
                    $new_category->harmony_ticket_id =  $ticket->ticket_id;
                    $new_category->save();
                }
               
            }

            $ticket_edit_history::create([
                'user_id' => Auth::user()->id,
                'ticket_id' => $ticket->id,
                'message' => $ticket->message,
                'category_id' => $request->input('other_category') ?  $other_id : $request->input('category_id'),
                'attachment' => $ticket->attachment,
            ]);
            $ticket->update([
                'user_id' => Auth::user()->id,
                'ticket_id' => $ticket_id,
                'message' => Crypt::encrypt($request->input('edit_description')),
                'category_id' => $request->input('other_category') ?  $other_id : $request->input('category_id'),
                'status' =>  $ticket->status
            ]);
        }

        return redirect('/tickets/list')->with("flash_message", "A ticket with ID: #$ticket->ticket_id has been updated.");
    }

    public function edit(Request $request)
    {
        $ticket_id = $request->route('ticket_id');
        $ticket = new Ticket;
        $attached_files = new AttachedFile;
        $ticket = $ticket->where('ticket_id', $ticket_id)->first();
        $ticket->attachment = $attached_files->where('harmony_ticket_id', $ticket->id)->get();
        $ticket->message = Crypt::decrypt($ticket->message);

        $other_category = '';
        if ($ticket->category_id == 16) {
            $ticket->other_category = OtherCategoriesHarmonyTicket::where('harmony_ticket_id', $ticket->ticket_id)->value('cat_name');
        }

        return view('tickets.editTicket', ['ticket' => $ticket]);
    }

    public function delete(Request $request)
    {
        $ticket_id = $request->route('ticket_id');
        $ticket = new Ticket;
        $ticket = $ticket->where('ticket_id', $ticket_id)->first();
        $attached_files = AttachedFile::where('harmony_ticket_id',  $ticket->id)->get();
        foreach ($attached_files as $attach_file) {
            $attach_file->delete();
        }
        if ($ticket->delete()) {
            return redirect('/tickets/list')->with("flash_message", "A ticket with ID: #$ticket->ticket_id has been deleted.");
        } else {
            return redirect('/tickets/list')->with("error", "Something went wrong. Please try again.");
        }
    }

    public function close(Request $request)
    {
        $ticket_id = $request->route('ticket_id');
        $ticket = new Ticket;
        $ticket = $ticket->where('ticket_id', $ticket_id)->first();
        $ticket->status = "Closed";

        if ($ticket->save()) {
            return redirect('/tickets/list')->with("flash_message", "A ticket with ID: #$ticket->ticket_id has been deleted.");
        } else {
            return redirect('/tickets/list')->with("error", "Something went wrong. Please try again.");
        }
    }

    public function inProgress(Request $request)
    {
        $ticket_id = $request->route('ticket_id');
        $ticket = new Ticket;
        $ticket = $ticket->where('ticket_id', $ticket_id)->first();
        $ticket->status = "InProgress";
        if ($ticket->save()) {
            return redirect('/tickets/list')->with("flash_message", "A ticket with ID: #$ticket->ticket_id has been marked in progress.");
        } else {
            return redirect('/tickets/list')->with("error", "Something went wrong. Please try again.");
        }
    }

    public function details(Request $request)
    {
        $ticket_id = $request->route('ticket_id');
        $ticket_user_id = $request->route('user_id');
        $ticket = new Ticket;
        $ticket_reply = new TicketReply;
        $attached_files = new AttachedFile;
        $ticket = $ticket->where('ticket_id', $ticket_id)->where('user_id', $ticket_user_id)->first();
        $ticket->name = User::where('id', $ticket->user_id)->value('first_name') . " " . User::where('id', $ticket->user_id)->value('last_name');
        $ticket->email = User::where('id', $ticket->user_id)->value('email');

        $ticket->attachment = $attached_files->where('harmony_ticket_id', $ticket->id)->get();
        $ticket->category_name = HarmonyTicketsCategory::where('id', $ticket->category_id)->value('name');
        $other_category_name = OtherCategoriesHarmonyTicket::where('harmony_ticket_id', $ticket->ticket_id)->value('cat_name');
        if ($other_category_name) {
            $ticket->category_name = $ticket->category_name . ' (' . $other_category_name . ')';
        }
        $extension = pathinfo($ticket->attachment, PATHINFO_EXTENSION);

        $ticket_replies = $ticket_reply->where('ticket_id', $ticket_id)->latest()->paginate(10);
        $latest_reply_id = $ticket_replies->isNotEmpty() ? $ticket_replies->first()->id : null;
        foreach ($ticket_replies as $reply) {
            $reply->user_email = User::where('id', $reply->user_id)->value('email');
            $reply->user_name = User::where('id', $reply->user_id)->value('first_name');
            $reply->reply =  Crypt::decrypt($reply->reply);
            $extension = pathinfo($reply->attachment, PATHINFO_EXTENSION);
            $reply->is_latest = $reply->id == $latest_reply_id;
        };
        $ticket->message = Crypt::decrypt($ticket->message);
        $ticket->created_at = Carbon::parse($ticket->created_at)->setTimezone('Asia/Kolkata');
        return view('tickets.details', ['ticket' => $ticket, 'ticket_replies' => $ticket_replies]);
    }

    public function filter(Request $request)
    {
        $tickets = Ticket::where('status', $request->status)->latest()->paginate(2);
        return view('tickets.index', compact('tickets'));
    }

    public function deleteAttachment(Request $request)
    {
        $attachment_id = $request->route('attachment_id');
        $attachment = AttachedFile::find($attachment_id);
        $attachment->delete();
        return redirect()->back()->with("flash_message", "Attachment deleted successfully.");
    }

    function generateTicketId($ticketNumber)
    {
        // Add leading zeros to the ticket number until it has 5 digits
        $id = str_pad($ticketNumber, 6, '0', STR_PAD_LEFT);
        // Add the prefix and return the modified ID
        return 'TLGT-HC-' . $id;
    }
}
