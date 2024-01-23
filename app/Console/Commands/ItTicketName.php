<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ItTicketName extends Command
{
    protected $signature = 'command:update-it-names';
    protected $description = 'Update IT ticket names in the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Updating IT ticket names and related data...');

        $ranges = [
            ['start' => 106, 'end' => 131],
            ['start' => 200, 'end' => 202]
        ];

        $updatedTicketIDs = [];

        foreach ($ranges as $range) {
            $tickets = DB::table('it_tickets')
                ->whereBetween('ticket_id', ['TLGT-IT-' . sprintf('%06d', $range['start']), 'TLGT-IT-' . sprintf('%06d', $range['end'])])
                ->get();

            foreach ($tickets as $ticket) {
                $newName = sprintf('TLGT-IT-%06d', $ticket->id);

                if (!in_array($ticket->ticket_id, $updatedTicketIDs)) {
                    DB::table('it_tickets')->where('id', $ticket->id)->update(['ticket_id' => $newName]);
                    $updatedTicketIDs[] = $ticket->ticket_id;

                    // Update related data in ticket_replies table
                    DB::table('ticket_replies')->where('ticket_id', $ticket->ticket_id)->update(['ticket_id' => $newName]);
                }
            }
        }

        $this->info('IT ticket names and related data updated successfully.');
    }
}
