<?php namespace MailTwelve\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Herbert\Framework\Models\SoftDeletes\SoftDeletes;

class User extends Model {
	use SoftDeletes;
	
	protected $table = 'users';
	protected $primaryKey = 'ID';
	const USER_REGISTERED = 'user-registered';
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_login', 'user_pass', 'user_nicename', 
		'user_email', 'user_url', 'user_registered', 
		'user_activation_key', 'user_status', 'display_name'
	];
	
	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = [
		'user_registered', 
	];
	
	/**
	 * PostMeta relationship.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function meta() {
		return $this->hasMany(__NAMESPACE__ . '\UserMeta', 'user_id');
	}
}
