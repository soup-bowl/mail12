<?php namespace MailTwelve\Controllers;

use MailTwelve\Services\settingService;
use Herbert\Framework\Models\Post;
use MailTwelve\Helper;
use Zend\Mail\Storage\Imap;
use Carbon\Carbon;

class MailController {
	protected $SettingsService;
	
	public function __construct(SettingService $SettingsService) {
		$this->SettingService = $SettingsService;
	}
	
	public function getHome() {
		if (!extension_loaded('imap')) {
			return view('@MailTwelve/error/noimap.twig');
		}
		
		// Load up configuration files and negate IP addresses for faster comms.
		$userConfig = $this->SettingService->GetUserSettings();
		$sysConfig  = $this->SettingService->GetMailSystemSettings();
		$mailips = ['incoming' => gethostbyname($sysConfig['incomingUrl']), 'outgoing' => gethostbyname($sysConfig['outgoingUrl'])];
		
		// Set up the connection.
		$mailService = new Imap([
			'host'     => $mailips['incoming'],
			'port'     => $sysConfig['incomingPort'],
			'user'     => $userConfig['username'],
			'password' => $userConfig['password'],
		]);
		$mailService->selectFolder('INBOX');
		
		$mail = new \stdClass();
		$mail->count   = $mailService->countMessages(); 
		$mail->letters = [];
		
		$mailArray = [];
		foreach ($mailService as $messageNum => $message) {
    		$mailObj = new \stdClass();
			$mailObj->num     = $messageNum;
			$mailObj->title   = $message->subject;
			$mailObj->from    = $message->from; 
			$mailObj->cc      = (isset($message->cc)) ? $message->cc : null;
			$mailObj->date    = Carbon::parse( $message->date )->format( get_option('date_format').', '.get_option('time_format') );
			$mailObj->content = $message->getContent();
			
			array_push( $mailArray, $mailObj );
		}
		
		//die(print_r( $mailArray ));
		
		return view('@MailTwelve/mail/home.twig', [
			'address'    => $userConfig['username'],
			'title'      => Helper::get('pluginName'),
			'collection' => $mailArray
		]);
	}
}