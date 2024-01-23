<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = $this->user['toUser']->email;
        $subject = 'Employee Comment From DSR';
        $name = $this->user['fromUser']->first_name.' '.$this->user['fromUser']->last_name;

        $headerData = [
            'category' => 'category',
            'unique_args' => [
                'variable_1' => 'abc'
            ]
        ];
        $header = $this->asString($headerData);
        $this->withSwiftMessage(function ($message) use ($header) {
            $message->getHeaders()
                ->addTextHeader('X-SMTPAPI', $header);
        });
        $data = [
            "receiver" => $this->user['toUser']->first_name,
            "email" => $this->user['toUser']->email,
            "message" => $this->user['comments'],
            "userId" => $this->user['fromUser']->id
        ];
        return $this->view('mails.comment_mail')
            ->from($address, $name)
            // ->cc($address, $name)
            // ->bcc($address, $name)
            ->replyTo($data['email'], $data['receiver'])
            ->subject($subject)
            ->with([ 'data' => $data ]);
    }

    private function asString($data)
    {
        $json = $this->asJSON($data);
        return wordwrap($json, 76, "\n   ");
    }

    private function asJSON($data)
    {
        $json = json_encode($data);
        $json = preg_replace('/(["\]}])([,:])(["\[{])/', '$1$2 $3', $json);
        return $json;
    }
}
