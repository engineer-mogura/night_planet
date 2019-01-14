<?php
namespace App\Mailer;

use Cake\Mailer\Mailer;

class OwnerMailer extends Mailer
{
    public function registration($owner)
    {
        $this
            ->to($owner->email)
            ->setSubject('ご登録ありがとうございます。')
            ->set('owner', $owner);
    }
}
