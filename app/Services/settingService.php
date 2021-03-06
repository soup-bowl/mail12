<?php namespace MailTwelve\Services;

use MailTwelve\Models\User;
use MailTwelve\Models\UserMeta;
use Herbert\Framework\Models\Option;
use Herbert\Framework\Http;
use MailTwelve\Helper;

class SettingService extends service {
	protected $UserMeta;
	protected $Option;
	
	public function __construct(UserMeta $UserMeta, Option $Option) {
		$this->UserMeta = $UserMeta;
		$this->Option   = $Option;
	}
	
	/**
	 * Returns an array of the user settings.
	 * @param int $user_id
	 * @return string[]|boolean
	 */
	public function GetUserSettings($user_id = 0) {
		$user_id = ($user_id == 0) ? get_current_user_id() : $user_id;
		
		$resultant = $this->UserMeta->getSettings($user_id)->first();
		
		if(empty($resultant)) {
			return false;
		} else {
			return unserialize($resultant->meta_value);
		}
	}
	
	/**
	 * Saves the user configuration array to the databaase.
	 * @param int $user_id
	 * @param string[] $content
	 * @return UserMeta
	 */
	public function SetUserSettings($content, $user_id = 0) {
		$user_id = ($user_id == 0) ? get_current_user_id() : $user_id;
		
		$entry = ($this->getUserSettings($user_id) != false) ? $this->UserMeta->getSettings($user_id)->first() : new UserMeta();
		$entry->user_id    = $user_id;
		$entry->meta_key   = 'm12e-user-settings';
		$entry->meta_value = serialize($content); 
		$entry->save();
		
		return $entry;
	}
	
	/**
	 * Grabs the mail system setting collection.
	 * @return array[]|boolean
	 */
	public function GetMailSystemSettings() {
		$options = $this->Option->where('option_name', '=', 'm12e-settings')->first();
		
		if (empty($options)) {
			return false;
		} else {
			return unserialize($options->option_value);
		}
	}
	
	/**
	 * Saves the mail system configuration to the system.
	 * @param array[] $configs 
	 * @return array[]
	 */
	public function SetMailSystemSettings($configs) {
		$settingCollection = $this->Option->where('option_name', '=', 'm12e-settings')->first();
		$settingCollection = (empty($settingCollection)) ? new Option() : $settingCollection;
		
		$settingCollection->option_name  = 'm12e-settings';
		$settingCollection->option_value = serialize($configs);
		$settingCollection->autoload     = 'no';
		$settingCollection->save();
		
		return $settingCollection;
	}

}