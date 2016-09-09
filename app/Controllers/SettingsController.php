<?php namespace MailTwelve\Controllers;

/** @var Herbert\Framework\Http $http */

use MailTwelve\Services\settingService;
use Herbert\Framework\Http;
use MailTwelve\Helper;

class SettingsController {
	protected $SettingsService;
	
	public function __construct(SettingService $SettingsService) {
		$this->SettingService = $SettingsService;
	}
	
	public function getSettingsPanel(Http $http) {
		$options = $this->SettingService->GetMailSystemSettings();
		
		if ( $http->has('mail_protocol') ) {
			$postback = true;
			
			$options_pure = array();
			$options_pure["protocol"]     = $http->get('mail_protocol');
			$options_pure["incomingUrl"]  = $http->get('incoming_mailserver_url');
			$options_pure["incomingPort"] = $http->get('incoming_mailserver_port');
			$options_pure["outgoingUrl"]  = $http->get('outgoing_mailserver_url');
			$options_pure["outgoingPort"] = $http->get('outgoing_mailserver_port');
			$options = $options_pure;
			
			$this->SettingService->SetMailSystemSettings( $options );
		} else {
			$postback = false;
		}
		
		$protocols = ['IMAP', 'POP'];
		
		return view('@MailTwelve/settings.twig', [
			'title'     => Helper::get('pluginName'),
			'protocols' => $protocols, 
			'data'      => $options,
			'saved'     => $postback
		]);
	}
	
    public function getUserSettingsPanel(Http $http) {
		$options = $this->SettingService->GetUserSettings();
		
		if ( $http->has('account_username') ) {
			$postback = true;
			
			$options_pure = array();
			$options_pure["username"] = $http->get('account_username');
			$options_pure["password"] = $http->get('account_password');
			$options = $options_pure;
			
			$this->SettingService->SetUserSettings( $options );
		} else {
			$postback = false;
		}
		
		return view('@MailTwelve/settings-user.twig', [
			'title'     => Helper::get('pluginName'),
			'data'      => $options,
			'saved'     => $postback
		]);
    }
}
