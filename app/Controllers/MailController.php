<?php namespace MailTwelve\Controllers;

use MailTwelve\Services\settingService;
use Herbert\Framework\Models\Post;
use MailTwelve\Helper;

class MailController {
	protected $SettingsService;
	
	public function __construct(SettingService $SettingsService) {
		$this->SettingService = $SettingsService;
	}
	
	public function getHome() {
		if (!extension_loaded('imap')) {
			return view('@MailTwelve/error/noimap.twig');
		}
		
		$userConfig = $this->SettingService->GetUserSettings();
		$sysConfig  = $this->SettingService->GetMailSystemSettings();
		$mailips = ['incoming' => gethostbyname($sysConfig['incomingUrl']), 'outgoing' => gethostbyname($sysConfig['outgoingUrl'])];
		
		$mbox = \imap_open("{".$mailips['incoming'].":".$sysConfig['incomingPort']."}INBOX", $userConfig['username'], $userConfig['password']);
		die(var_dump($mbox));
		
		return view('@MailTwelve/mail/home.twig', [
			'address' => $userConfig['username'],
			'title'   => Helper::get('pluginName')
		]);
	}
}