<?php namespace MailTwelve\Controllers;

use Herbert\Framework\Models\Post;
use MailTwelve\Helper;

class MailController {
	public function getHome() {
		return view('@MailTwelve/mail/home.twig', [
			'title'   => Helper::get('pluginName')
		]);
	}
}