<?php

namespace App\Http\Controllers;

use App\Jobs\SendLateTimeInJob;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;
use Illuminate\Support\Str;
use Swift_Plugins_LoggerPlugin;
use Swift_Plugins_Loggers_ArrayLogger;

class TestController extends Controller
{
    public function email(){
        // $email = ['rohit.gupta@talentelgia.in' ,'manish.chopra@talentelgia.in','advait@talentelgia.in','shilpi@talentelgia.in'];
        $email = 'amanpreet.kaur@talentelgia.in';

          Log::info('Step 1 :');
          $user_detail = $email;
          $attendance = [];
          Log::info('Step 2 : before sending mail');

          $logger = new Swift_Plugins_Loggers_ArrayLogger();
          Mail::getSwiftMailer()->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

          try {
            Mail::send('mails.attendance_detail', [
                'attendance' =>$attendance,
                'user_detail' => $user_detail,
            ], function($message) use($user_detail){
                $message->from('info@talentelgia.in');
                $message->to($user_detail);
                $message->subject('Attendance Detail of ' . 'Sukhpreet' ." " . 'Singh' . " " . "(" . 'TLGT-334' .")");
            });
        
            Log::info('SMTP Details : ' . $logger->dump());
            // dd($logger->dump());
            // if ($message !== null && $message->getFailures()) {
            //     $failures = $message->getFailures();
            //     echo "Errors: " . implode(",", $failures);
            // } else {
                echo "Success";
            // }
        } catch (\Swift_TransportException $e) {
            $errorMessage = $e->getMessage();
            echo "Error: " . $errorMessage;
        }
    }
}
