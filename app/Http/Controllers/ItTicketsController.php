<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ItTicket;
use App\InventoryItem;
use App\User;
use App\ItTicketCategories;
use App\ItTicketEditHistory;
use App\AttachedFile;
use Crypt;
use App\TicketReply;
use Mail;
use Illuminate\Validation\Rule;
use carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\Foreach_;
use RahulHaque\Filepond\Facades\Filepond;

class ItTicketsController extends Controller
{

    public function __construct()
    {
        $this->title = "IT-Tickets";
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {

            $filters = [
                'status' => $request->query('status') ?? null,
                'date' => $request->query('date') ?? null,
                'category' => $request->query('category') ?? null,
                'severity' => $request->query('severity') ?? null,
                'start_date' => $request->query('start_date') ?? null,
                'end_date' => $request->query('end_date') ?? null,
                'search' => $request->query('search') ?? null, 
                'high_severity' => $request->has('high_severity') ?? null,
                'medium_severity' => $request->has('medium_severity') ?? null,
                'low_severity' => $request->has('low_severity') ?? null,
                'turnaround_time' =>$request->has('turnaround_time') ?? null,
            ];
            if (Auth::user()->email == 'rohit.gupta@talentelgia.in' || Auth::user()->email == 'gautam.uppal@talentelgia.in') {
                $it_tickets = $this->getItTickets($filters, 'admin');
                return view('it-tickets.search', compact('it_tickets'));
            } else {
                $it_tickets = $this->getItTickets($filters, 'employee');
                return view('it-tickets.search', compact('it_tickets'));
            }
        } else {
            $filters = [
                'status' => $request->query('status') ?? null,
                'date' => $request->query('date') ?? null,
                'category' => $request->query('category') ?? null,
                'severity' => $request->query('severity') ?? null,
                'start_date' => $request->query('start_date') ?? null,
                'end_date' => $request->query('end_date') ?? null,
                'search' => $request->query('search') ?? null,
                'high_severity' => $request->has('high_severity') ?? null,
                'medium_severity' => $request->has('medium_severity') ?? null,
                'low_severity' => $request->has('low_severity') ?? null,
                'turnaround_time' =>$request->has('turnaround_time') ?? null,
            ];

            if (Auth::user()->email == 'rohit.gupta@talentelgia.in' || Auth::user()->email == 'gautam.uppal@talentelgia.in') {
                $it_tickets = $this->getItTickets($filters, 'admin');

                if (isset($it_tickets)) {
                    return view('it-tickets.index', compact('it_tickets'));
                } else {
                    $it_tickets = $this->getItTickets([], 'admin');
                    return view('it-tickets.index', compact('it_tickets'));
                }
            } else {

                $it_tickets = $this->getItTickets($filters, 'employee');

                if (isset($it_tickets)) {
                    return view('it-tickets.index', compact('it_tickets'));
                } else {
                    $it_tickets = $this->getItTickets([], 'employee');
                    return view('it-tickets.index', compact('it_tickets'));
                }
            }
        }

        return view('it-tickets.index', compact('it_tickets'));
    }

    public function add()
    {
        $items = InventoryItem::where('assigned_to', Auth::user()->id)->get();
        return view('it-tickets.addTicket', ['title' => $this->title, 'items' => $items]);
    }

    private function getItTickets($filters = [], $user_type = 'employee')
    {

        $it_tickets = ItTicket::query();

        if (isset($filters['category'])) {
            if ($filters['category'] !== "All") {
            $it_tickets->where('category', $filters['category']);
            }
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $start_date = $filters['start_date'] . ' 00:00:00';
            $end_date = $filters['end_date'] . ' 23:59:59';
            $it_tickets->whereBetween('created_at', [$start_date, $end_date]);
        }

        if (isset($filters['severity'])) {
            if ($filters['severity'] !== "All") {
            $it_tickets->where('severity', $filters['severity']);
            }
        }

        if ($filters['high_severity'] == true) {
            $statuses = ['Open', 'Reopen'];
            $it_tickets->where('severity', 'High')->whereIn('status', $statuses);
        }

        if ($filters['medium_severity'] == true) { 
            $statuses = ['Open', 'Reopen'];
            $it_tickets->where('severity', 'Medium')->whereIn('status', $statuses);
        }

        if ($filters['low_severity'] == true) { 
            $statuses = ['Open', 'Reopen'];
            $it_tickets->where('severity', 'Low')->whereIn('status', $statuses);
        }

        if (isset($filters['search'])) {
            $it_tickets->where(function ($query) use ($filters) {
                $query->where('user_name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('category_name', 'like', '%' . $filters['search'] . '%');
            });
        }

        if ($filters['turnaround_time'] == true) { 
            $it_tickets = ItTicket::where('turnaround_time', '>', '04:00:00');
        }

        if (isset($filters['status'])) {
            if ($filters['status'] !== "All") {
                $it_tickets->where('status', $filters['status']);
            }
        }

        if ($user_type == 'admin') {
            $it_tickets = $it_tickets->latest()->paginate(10);
        } else {
            $it_tickets = $it_tickets->where('user_id', Auth::user()->id)->latest()->paginate(10);
        }

        foreach ($it_tickets as $ticket) {
            $ticket->reply = TicketReply::where('ticket_id', $ticket->ticket_id)->latest()->value('reply');
            $ticket->category = ItTicketCategories::where('id', $ticket->category)->value('name');
            $ticket->severity = $ticket->severity;
            $ticket->turnaround_time = $ticket->turnaround_time;
            $ticket->user_first_name = $ticket->first_name;
            $ticket->user_last_name = $ticket->last_name;
            $ticket->created_at = Carbon::parse($ticket->created_at)->setTimezone('Asia/Kolkata');
            $ticket->employee_code = User::where('id', $ticket->user_id)->value('employee_code');
        }
        return $it_tickets;
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'description' => 'required',
            // 'items_select' => 'required',
            'gallery.*' => [
                Rule::filepond([
                    'max:2000',
                ])
            ],
            'category_id' => 'required',
            'severity' => 'required',
        ]);

        if ($request->gallery) {
            $it_tickets_attachments = 'it_tickets_attachments-' . time();
            $fileInfos = Filepond::field($request->gallery)
                ->moveTo('it_tickets_attachments/' . $it_tickets_attachments);
        }

        if (!empty($fileInfos)) {
          
            $ticket = new ItTicket([
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                'category_name' => ItTicketCategories::where('id', $request->input('category_id'))->value('name'),
                'ticket_id' => '',
                'item_id' => $request->input('items_select') != 0 ? $request->input('items_select') :   null,
                'message' => $request->input('description'),
                'category' => $request->input('category_id'),
                'severity' => $request->input('severity'),
                'status' => "Open",
                'attachment' =>  ''
            ]);
            
            $user = User::where('id', $ticket->user_id)->first();
           
            $ticket->save();
        
            $ticket->ticket_id = $this->generateTicketId($ticket->id);
            $ticket->save();

            foreach ($fileInfos as $file) {
                $attached_files = new AttachedFile([
                    'user_id' => Auth::user()->id,
                    'it_ticket_id' =>  $ticket->id,
                    'basename' => $file['basename'],
                    'dirname' => $file['dirname'],
                    'extension' => $file['extension'],
                    'url' => $file['url']
                ]);
                $attached_files->save();
            }


        } else { 

            $ticket = new ItTicket([
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                'category_name' => ItTicketCategories::where('id', $request->input('category_id'))->value('name'),
                'ticket_id' => '',
                'item_id' => $request->input('items_select') != 0 ? $request->input('items_select') :   null,
                'message' => $request->input('description'),
                'category' => $request->input('category_id'),
                'severity' => $request->input('severity'),
                'status' => "Open",
            ]);

            $user = User::where('id', $ticket->user_id)->first();
           
            $ticket->save();
        
            $ticket->ticket_id = $this->generateTicketId($ticket->id);
            $ticket->save();
        }
          
        $category_name =  ItTicketCategories::where('id', $ticket->category)->value('name');
            $emails = ['rohit.gupta@talentelgia.in', 'gautam.uppal@talentelgia.in'];
            try {
                foreach ($emails as $email) {
                    Mail::send('mails.it-ticket_mail', [
                        'ticketNumber' => $ticket->ticket_id,
                        'user_id' => $ticket->user_id,
                        'userName' => $user->first_name
                    ], function ($message) use ($email, $ticket ,$category_name) {
                        $message->to($email);
                        $message->subject('New Ticket '.$ticket->ticket_id.':'.$category_name.' created');
                    });
                }
            } catch (\Exception $e) {
                // dd($e->getMessage());
            }
            return redirect('/it-tickets/list')->with("flash_message", "A ticket with ID: #$ticket->ticket_id has been opened.");
        } 
    

    public function edit(Request $request)
    {
        $ticket_id = $request->route('ticket_id');
        $ticket = new itTicket;
        $attached_files = new AttachedFile;
        $ticket = $ticket->where('ticket_id', $ticket_id)->first();
        $ticket->attachment = $attached_files->where('it_ticket_id', $ticket->id)->get();
        $items = InventoryItem::where('assigned_to', Auth::user()->id)->get();
        return view('it-tickets.editTicket', ['ticket' => $ticket, 'items' => $items]);
    }


    public function delete(Request $request)
    {
       
        $ticket_id = $request->route('ticket_id');
        $ticket = new ItTicket;

        $ticket = $ticket->where('ticket_id', $ticket_id)->first();
        $attached_files = AttachedFile::where('it_ticket_id',  $ticket->id)->get();
        foreach($attached_files as $attach_file){  
            $attach_file->delete();         
        }
        if ($ticket->delete()) {
            return redirect('/it-tickets/list')->with("flash_message", "A ticket with ID: #$ticket->ticket_id has been deleted.");
        } else {
            return redirect('/it-tickets/list')->with("error", "Something went wrong. Please try again.");
        }
    }

    public function inProgress(Request $request)
    {
        $ticket_id = $request->route('ticket_id');
        $ticket = new ItTicket;

        $ticket = $ticket->where('ticket_id', $ticket_id)->first();
        $ticket->status = "InProgress";

        if ($ticket->save()) {
            return redirect('/it-tickets/list')->with("flash_message", "A ticket with ID: #$ticket->ticket_id has been marked in progress.");
        } else {
            return redirect('/it-tickets/list')->with("error", "Something went wrong. Please try again.");
        }
    }

    public function reopenTicket(Request $request)
    {
        $ticket_id = $request->route('ticket_id');
        $ticket = new ItTicket;

        $ticket = $ticket->where('ticket_id', $ticket_id)->first();
        $ticket->reopen_time = Carbon::now();
        $ticket->status = "Reopen";

        if ($ticket->save()) {
            return redirect('/it-tickets/list')->with("flash_message", "A ticket with ID: #$ticket->ticket_id has been marked to reopen.");
        } else {
            return redirect('/it-tickets/list')->with("error", "Something went wrong. Please try again.");
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'edit_description' => 'required',
            'gallery.*' => [
                Rule::filepond([
                    'max:2000',
                ])
            ],
            'category_id' => 'required',
            'severity' => 'required',
        ]);
    
        $ticket_id = $request->input('ticket_id');
        $ticket = new ItTicket;
        $ticket_edit_history = new ItTicketEditHistory;
        $ticket = $ticket->where('ticket_id', $ticket_id)->first();

        if ($request->gallery) {
            $it_tickets_attachments = 'it_tickets_attachments-' . time();
            $fileInfos = Filepond::field($request->gallery)
                ->moveTo('it_tickets_attachments/' . $it_tickets_attachments);

                $ticket_edit_history::create([
                    'user_id' => Auth::user()->id,
                    'category' => $ticket->category,
                    'ticket_id' => $ticket->id,
                    'severity' => $ticket->severity,
                    'message' => $ticket->message,
                    'attachment' => $ticket->attachment ? $ticket->attachment : '', 
                ]);

            $ticket->update([
                'user_id' => Auth::user()->id,
                'user_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                'category_name' => ItTicketCategories::where('id', $request->input('category_id'))->value('name'),
                'ticket_id' => $ticket_id,
                'message' => $request->input('edit_description'),
                'category' => $request->input('category_id'),
                'severity' => $request->input('severity'),
                'status' =>  $ticket->status,
                'attachment' => ''
            ]);

            
            foreach($fileInfos as $file){
                    $attached_files = new AttachedFile([
                        'user_id' => Auth::user()->id,
                        'it_ticket_id' =>  $ticket->id,
                        'basename' => $file['basename'],
                        'dirname' => $file['dirname'],
                        'extension' => $file['extension'],
                        'url' => $file['url']
                    ]);  
                    $attached_files->save();
            }


        } else {

            $ticket_edit_history::create([
                'user_id' => Auth::user()->id,
                'category' => $ticket->category,
                'ticket_id' => $ticket->id,
                'severity' => $ticket->severity,
                'message' => $ticket->message,
                'attachment' => $ticket->attachment ? $ticket->attachment : '',     
            ]);

            $ticket->update([
                'user_id' => Auth::user()->id,
                'ticket_id' => $ticket_id,
                'user_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name,
                'category_name' => ItTicketCategories::where('id', $request->input('category_id'))->value('name'),
                'message' => $request->input('edit_description'),
                'category' => $request->input('category_id'),
                'severity' => $request->input('severity'),
                'status' =>  $ticket->status,
                'attachment' => ''
            ]);  
        }

        return redirect('/it-tickets/list')->with("flash_message", "A ticket with ID: #$ticket->ticket_id has been updated.");
    }

    public function close(Request $request)
    {
        $ticket_id = $request->route('ticket_id');
        $ticket = new ItTicket;

        $ticket = $ticket->where('ticket_id', $ticket_id)->first();
        if ($ticket->status == "Reopen") {
            $carbon_reopen_time = Carbon::parse($ticket->reopen_time);
            $diff_reopen_time =  $carbon_reopen_time->diff(Carbon::now());
            $carbon_last_turnaround_time = Carbon::parse($ticket->turnaround_time);
            $reopen_interval = CarbonInterval::hour($diff_reopen_time->h)
                ->minute($diff_reopen_time->i)
                ->second($diff_reopen_time->s);

            $total_turnaround_time = $carbon_last_turnaround_time->add($reopen_interval);

            $ticket->turnaround_time = $total_turnaround_time->toDateTimeString();
            $ticket->status = "Closed";
        } else {
            $diff = $ticket->created_at->diff(Carbon::now());
            $ticket->turnaround_time = $diff->format('%H:%I:%S');
            $ticket->status = "Closed";
        }



        if ($ticket->save()) {
            return redirect('/it-tickets/list')->with("flash_message", "A ticket with ID: #$ticket->ticket_id has been marked closed.");
        } else {
            return redirect('/it-tickets/list')->with("error", "Something went wrong. Please try again.");
        }
    }

    public function changeStatus(Request $request)
    {

        $status = $request->query('status');
        $ticket_id = $request->query('ticket_id');
        $ticket = new ItTicket;

        $ticket = $ticket->where('ticket_id', $ticket_id)->first();

        if ($ticket->status == "Reopen" && $status == "Closed") {
           
            $carbon_reopen_time = Carbon::parse($ticket->reopen_time);
            $carbon_last_turnaround_time = Carbon::parse($ticket->turnaround_time);

            $reopen_interval = $carbon_reopen_time->diffAsCarbonInterval(Carbon::now());

            $total_turnaround_time = $carbon_last_turnaround_time->add($reopen_interval);

            $ticket->turnaround_time = $total_turnaround_time->toDateTimeString();
            $ticket->status = $status;
        } else if ($status == "Closed") {

            $diff = $ticket->created_at->diff(Carbon::now());
            $ticket->turnaround_time = $diff->format('%H:%I:%S');
            $ticket->status = "Closed";
        } else if ($status == "Reopen") {
            $ticket->reopen_time = Carbon::now();
            $ticket->status = $status;
        } else {

            $ticket->status = $status;
        }

        if ($ticket->save()) {
            return redirect('/it-tickets/list')->with("flash_message", "A ticket with ID: #$ticket->ticket_id has marked .$status.");
        } else {
            return redirect('/it-tickets/list')->with("error", "Something went wrong. Please try again.");
        }
    }

    public function dashboard(Request $request)
    {

        if ($request->query('start_date') && $request->query('end_date')) {
            $start_date = $request->query('start_date');
            $end_date = $request->query('end_date');
            $statuses = ['Open', 'Reopen'];
            if ($start_date == $end_date) {
                $highCount = ItTicket::where('severity', 'High')->whereIn('status', $statuses)->whereDate('created_at', $start_date)->count();

                $mediumCount = ItTicket::where('severity', 'Medium')->whereIn('status', $statuses)->whereDate('created_at', $start_date)->count();

                $lowCount = ItTicket::where('severity', 'Low')->whereIn('status', $statuses)->whereDate('created_at', $start_date)->count();

                $openCount = ItTicket::where(['status' => 'Open'])->whereDate('created_at', $start_date)->count();

                $closedCount = ItTicket::where(['status' => 'Closed'])->whereDate('created_at', $start_date)->count();

                $inProgressCount = ItTicket::where(['status' => 'InProgress'])->whereDate('created_at', $start_date)->count();

                $reopenCount = ItTicket::where(['status' => 'Reopen'])->whereDate('created_at', $start_date)->count();

                $turnaroundCount = ItTicket::where('turnaround_time', '>', '04:00:00')->whereDate('created_at', $start_date)->count();

                $allTicketsCount = ItTicket::whereDate('created_at', $start_date)->count();
                return view('it-tickets.dashboard', compact('highCount', 'mediumCount', 'lowCount', 'openCount', 'closedCount', 'inProgressCount', 'reopenCount', 'turnaroundCount', 'allTicketsCount'));
            } else {
                $highCount = ItTicket::where('severity', 'High')->whereIn('status', $statuses)->whereBetween('created_at', [$start_date, $end_date])->count();

                $mediumCount = ItTicket::where('severity', 'Medium')->whereIn('status', $statuses)->whereBetween('created_at', [$start_date, $end_date])->count();

                $lowCount = ItTicket::where('severity', 'Low')->whereIn('status', $statuses)->whereBetween('created_at', [$start_date, $end_date])->count();

                $openCount = ItTicket::where(['status' => 'Open'])->whereBetween('created_at', [$start_date, $end_date])->count();

                $closedCount = ItTicket::where(['status' => 'Closed'])->whereBetween('created_at', [$start_date, $end_date])->count();

                $inProgressCount = ItTicket::where(['status' => 'InProgress'])->whereBetween('created_at', [$start_date, $end_date])->count();

                $reopenCount = ItTicket::where(['status' => 'Reopen'])->whereBetween('created_at', [$start_date, $end_date])->count();

                $turnaroundCount = ItTicket::where('turnaround_time', '>', '04:00:00')->whereBetween('created_at', [$start_date, $end_date])->count();

                $allTicketsCount = ItTicket::whereBetween('created_at', [$start_date, $end_date])->count();
                return view('it-tickets.dashboard', compact('highCount', 'mediumCount', 'lowCount', 'openCount', 'closedCount', 'inProgressCount', 'reopenCount', 'turnaroundCount', 'allTicketsCount'));
            }
        } else {
            $statuses = ['Open', 'Reopen'];
            $highCount = ItTicket::where('severity', 'High')->whereIn('status', $statuses)->count();
            $mediumCount = ItTicket::where('severity', 'Medium')->whereIn('status', $statuses)->count();
            $lowCount = ItTicket::where('severity', 'Low')->whereIn('status', $statuses)->count();
            $openCount = ItTicket::where(['status' => 'Open'])->count();
            $closedCount = ItTicket::where(['status' => 'Closed'])->count();
            $inProgressCount = ItTicket::where(['status' => 'InProgress'])->count();
            $reopenCount = ItTicket::where(['status' => 'Reopen'])->count();
            $turnaroundCount = ItTicket::where('turnaround_time', '>', '04:00:00')->count();
            $allTicketsCount = ItTicket::count();
            return view('it-tickets.dashboard', compact('highCount', 'mediumCount', 'lowCount', 'openCount', 'closedCount', 'inProgressCount', 'reopenCount', 'turnaroundCount', 'allTicketsCount'));
        }
    }

    public function deleteAttachment(Request $request){
        $attachment_id = $request->route('attachment_id');
        $attachment = AttachedFile::find($attachment_id);
        if($attachment->delete()){
            return redirect()->back()->with("flash_message", "Attachment deleted successfully.");
        }

    }

    public function details(Request $request)
    {
        $ticket_id = $request->route('ticket_id');
        $ticket = new ItTicket;
        $ticket_reply = new TicketReply;
        $attached_files = new AttachedFile;
        $ticket = $ticket->where('ticket_id', $ticket_id)->first();
        $ticket->email = User::where('id', $ticket->user_id)->value('email');
        $ticket->created_at = Carbon::parse($ticket->created_at)->setTimezone('Asia/Kolkata');
        $ticket->attachment = $attached_files->where('it_ticket_id', $ticket->id)->get();
        
        $ticket_replies = $ticket_reply->where('ticket_id', $ticket_id)->latest()->paginate(10);
        $latest_reply_id = $ticket_replies->isNotEmpty() ? $ticket_replies->first()->id : null;
        foreach ($ticket_replies as $key => $reply) {
            $reply->user_email = User::where('id', $reply->user_id)->value('email');
            $reply->user_name = User::where('id', $reply->user_id)->value('first_name');
            $reply->reply =  Crypt::decrypt($reply->reply);
            $extension = pathinfo($reply->attachment, PATHINFO_EXTENSION);
            $reply->attachment = $attached_files->where('reply_id', $reply->id)->get();
            $reply->is_latest = $reply->id == $latest_reply_id;
        }

        return view('it-tickets.details', ['ticket' => $ticket, 'ticket_replies' => $ticket_replies]);
    }



    function generateTicketId($ticketNumber)
    {
        // Add leading zeros to the ticket number until it has 5 digits
        $id = str_pad($ticketNumber, 6, '0', STR_PAD_LEFT);
    
        // Add the prefix and return the modified ID
        return 'TLGT-IT-' . $id;
    }
    
}
