<?php

namespace App\Http\Controllers;

use App\Models\MailBox;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use function GuzzleHttp\Promise\all;

class MailController extends Controller
{
    public $mailPaginationLimit = 8;

    public function index(){
        $usersMail = auth()->user()
            ->receivedMails()
            ->with(['sender', 'repliedLetter'])
            ->orderBy('created_at', 'desc')
            ->paginate($this->mailPaginationLimit);
        $usersMail = $this->fixMAilTextLen($usersMail);

        return view('mail.mymailbox',[
            'mail' => $usersMail
        ]);
    }

    public function fixMAilTextLen($mails){
        foreach ($mails as $msg){
            if(strlen($msg->mail_text) >= 157){
                $msg->mail_text = mb_substr($msg->mail_text, 0, 157) . "...";
            }
        }
        return $mails;
    }

    public function sendMailView(Request $request){
        $vacancyId = $request->get('vacancy_id');
        $jobgiverId = $request->get('jobgiver_id');

        $jobAuthor = User::where('id', $jobgiverId)->first();
        $vac = Vacancy::where('id', $vacancyId)->first();

        //dd($vac, $jobAuthor);
        return view('mail.mailform', [
            'vac' => $vac,
            'company' => $jobAuthor
        ]);
    }

    public function sendMail(Request $request){
        $recipientId = $request->get('recipient_id');
        $vacancyId = $request->get('vacancy_id');
        $senderId = auth()->id();
        $is_reply = $request->get('is_reply', null) == null ? null : intval($vacancyId);

        MailBox::create([
            'sender_id'=>$senderId,
            'is_read' => 0,
            'subject' => $request->get('subject'),
            'mail_text' => $request->get('text'),
            'recipient' => $recipientId,
            'reply_to' => $is_reply
        ]);
        return redirect(route('vacancy_show'));
    }

    public function unreadMailList(){
        $usersMail = auth()->user()
            ->receivedMails()->with(['sender'])
            ->where('is_read', '=', 0)
            ->orderBy('created_at', 'desc')
            ->paginate($this->mailPaginationLimit);
        $usersMail = $this->fixMAilTextLen($usersMail);
        return view('mail.unread',
        [
            'mail' => $usersMail
        ]);
    }

    public function sentMailList(){
        $userId = auth()->id();
        $usersMail = auth()->user()
            ->sentMails()
            ->where('sender_id', '=', $userId)
            ->with(['recipientObject'])
            ->orderBy('created_at', 'desc')
            ->paginate($this->mailPaginationLimit);
        $usersMail = $this->fixMAilTextLen($usersMail);
        return view('mail.sent',[
            'mail' => $usersMail
        ]);
    }

    public function getMail(Request $request){
        $mailId = $request->get('id');
        $mail = MailBox::where('id', '=', $mailId)
            ->with(['sender', 'recipientObject'])
            ->first();
        if($mail->recipient == auth()->id()){
            $mail->is_read = 1;
            $mail->save();
        }
        return response()->json([
            'mail' => $mail,
            'user_id' => auth()->id()
            ]);
    }

    public function replyView(Request $request){
        $mail = MailBox::where('id', '=', $request->get('mail_id'))->with('recipientObject')->first();
        $recipientUser = User::where('id', '=', $request->get('to'))->first();
        if(Auth::id() != $mail['recipientObject']->id)
            return redirect()->to(route('main_page'));
        return view('mail.reply_from',
        [
            'recipient' => $recipientUser,
            'mail' => $mail
        ]);
    }
}
