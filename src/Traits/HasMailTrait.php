<?php

namespace Thotam\ThotamHr\Traits;

use Thotam\ThotamHr\Models\MailHR;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasMailTrait
{

	/**
	 * Get all of the mails for the HR
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function mails(): HasMany
	{
		return $this->hasMany(MailHR::class, 'hr_key', 'key');
	}

	/**
	 * getMailNoiBo
	 *
	 * @param  mixed $tag
	 * @return void
	 */
	public function getMailNoiBo()
	{
		$mail = $this->mails()->latest()->where("tag", 'noibo');

		$email = $mail->first();
		return !!$email ? $email->mail : NULL;
	}

	/**
	 * getMailCaNhan
	 *
	 * @param  mixed $tag
	 * @return void
	 */
	public function getMailCaNhan()
	{
		$mail = $this->mails()->latest()->where("tag", 'canhan');

		$email = $mail->first();
		return !!$email ? $email->mail : NULL;
	}

	/**
	 * getMail
	 *
	 * @param  mixed $tag
	 * @param  mixed $tag2
	 * @return void
	 */
	public function getMail($tag = null, $tag2 = null)
	{
		$mail = $this->mails()->latest();

		if (!!$tag) {
			$mail->where("tag", $tag);
		}

		if (!(bool)$mail->first() && !!$tag2) {
			$mail = $this->mails()->latest();
			$mail->where("tag", $tag2);
		}

		$email = $mail->first();
		return !!$email ? $email->mail : $this->getMailCaNhan();
	}

	/**
	 * getMailLuanChuyen
	 *
	 * @param  mixed $tag
	 * @return void
	 */
	public function getMailLuanChuyen()
	{
		$mail = $this->mails()->latest()->where("tag", 'luanchuyen-phieu');

		$email = $mail->first();
		if (!!$email) {
			return $email->mail;
		}

		$mail_nb = $this->getMailNoiBo();
		if (!!$mail_nb) {
			return $mail_nb;
		}

		$mail_cn = $this->getMailCaNhan();
		if (!!$mail_cn) {
			return $mail_cn;
		}
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
			$email->update(['mail' => trim($mail)]);
		} else {
			$email = new MailHR;
			$email->mail = trim($mail);
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

	/**
	 * getMailFallback
	 *
	 * @param  mixed $tag
	 * @return void
	 */
	public function getMailFallback($tag = null)
	{
		$mail = $this->mails()->latest();

		if (!!$tag) {
			$mail->where("tag", $tag);
		}

		$email = $mail->first();
		if (!!$email) {
			return $email->mail;
		}

		$mail_nb = $this->getMailNoiBo();
		if (!!$mail_nb) {
			return $mail_nb;
		}

		$mail_cn = $this->getMailCaNhan();
		if (!!$mail_cn) {
			return $mail_cn;
		}
	}
}
