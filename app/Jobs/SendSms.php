<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

class SendSms implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $data;
    public $user;
    public function __construct($data, $user)
    {
        $this->data = $data;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $token="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE3MzQxMTQwNjYsImlhdCI6MTczMTUyMjA2Niwicm9sZSI6InRlc3QiLCJzaWduIjoiNjg3Zjc3MjQwZmI1OGUyNWUzNDRiZmRlMWM2NzVlYjBlODgwYjlmNDg5NjMyZDNlYzgwNzUxMDYzOWFkOGI3NSIsInN1YiI6Ijg5MjAifQ.rZVwBaZuwuJhlQ_WTRNLzL9FnFW04PK0PiLT08VSUt4";
        $massiv=[
            'mobile_phone'=>$this->user->phone,
            'message'=>'Sizning Tasdiqlash kodingiz:  '. $this->data->code,
            'from'=>4546,
            'callback_url'=>route('dashboard'),

        ];
        Http::withToken($token)->post('notify.eskiz.uz/api/message/sms/send',$massiv);
        
    }
}
