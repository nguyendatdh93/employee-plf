<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Config;

class MailService {

    private $subject;
    private $to_email;
    private $cc_emails;

    public function sendMail($subject, $to_email, $mail_template, $data = null, $cc_emails = null) {
        $this->subject   = $subject;
        $this->to_email  = $to_email;
        $this->cc_emails = $cc_emails;

        Mail::send("mail.$mail_template", $data, function($message){
            $message->to($this->to_email, $this->to_email);

            if (!empty($this->cc_emails)) {
                foreach ($this->cc_emails as $cc_email) {
                    $message->cc($cc_email);
                }
            }

            $message->subject($this->subject);
        });
    }

    public function notifyNewAccount($user, $password) {
        $data = [
            'user' => $user,
            'password' => $password
        ];
        $this->sendMail('【社員認証システム】アカウントが登録されました', $user->email, 'notify_new_account', $data);
    }

    public function notifyResetExpireTime($user) {
        $data = [
            'user' => $user,
            'password' => Config::get('base.default_password')
        ];
        $this->sendMail('【社員認証システム】パスワードがリセットされました', $user->email, 'notify_reset_expire_time', $data);
    }
}