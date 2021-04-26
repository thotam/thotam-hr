<?php

namespace Thotam\ThotamHr\Traits;

use Thotam\ThotamHr\Models\MailHR;

trait HasMailTrait {

    /**
     * getMail
     *
     * @param  mixed $tag
     * @return void
     */
    public function getMail($tag = null)
    {
        $mail = $this->mails()->latest();

        if (!!$tag) {
            $mail->where("tag", $tag);
        }

        $email = $mail->first();
        return !!$email ? $email->mail : NULL;
    }

    /**
     * updateMail
     *
     * @param  mixed $mail
     * @param  mixed $tag
     * @return void
     */
    public function updateMail(string $mail, string $tag = null)
    {
        $mails = $this->mails()->latest();

        if (!!$tag) {
            $mails->where("tag", $tag);
        }

        $email = $mails->first();

        if (!!$email) {
            $email->update(['mail' => $mail]);
        } else {
            $email = new MailHR;
            $email->mail = $mail;
            $email->tag = $tag;
            $this->mails()->save($email);
        }
    }

    /**
     * checkAndGetMail
     *
     * @param  mixed $mail
     * @param  mixed $tag
     * @return void
     */
    public function checkAndGetMail(string $mail, string $tag = null)
    {
        $this->updateMail($mail, $tag);
        return $this->getMail($tag);
    }

}
