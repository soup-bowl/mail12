<?php namespace MailTwelve\Controllers;

use MailTwelve\Services\settingService;
use Herbert\Framework\Models\Post;
use MailTwelve\Helper;
use Zend\Mail\Storage\Imap;

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
		
		/*
		// Connect to mailbox, or show an error page if the connection fails.
		$mbox = null;
		$mbox = \imap_open("{".$mailips['incoming'].":".$sysConfig['incomingPort']."/imap/readonly/notls}INBOX", $userConfig['username'], $userConfig['password']);
		if (!$mbox) {
			return view('@MailTwelve/error/imapExtError.twig', [ 'error' => imap_last_error() ]);
		}
		
		$headers = imap_headers($mbox);
		$head1 = [];
		foreach ($headers as $header) {
			array_push($head1, imap_rfc822_parse_headers($header));
		}
		//die(var_dump($headers));
		
		imap_close($mbox);*/
		
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
		
		/*for ($i = 1; $i < $mail->count; $i++) {
    		$letter;
			try {
				$letter = $mailService->getMessage($i);
			} catch {
				$letter = "Error reading message: " . $e;
			}
			array_push($mail->letters, $letter);
		}*/
		
		//die(var_dump( $mailService->getFolders() ));
		
		$mailArray = [];
		foreach ($mailService as $messageNum => $message) {
    		$mailObj = new \stdClass();
			$mailObj->num     = $messageNum;
			$mailObj->title   = $message->subject;
			$mailObj->from 	  = $message->from; 
			$mailObj->cc      = (isset($message->cc)) ? $message->cc : null;
			$mailObj->date    = $message->date;
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