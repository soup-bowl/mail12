<?php namespace MailTwelve\Models;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model {
	public $timestamps = false;
	protected $table = 'usermeta';
	protected $primaryKey = 'umeta_id';
	
	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = [
		'meta_key', 'meta_value'
	];
	
	/**
	 * Post relationship.
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user() {
		return $this->belongsTo(__NAMESPACE__ . '\User', 'user_id');
	}
	
	/**
	 * Scope to obtain the Mailer settings for the specified user.
	 * @param $query
	 * @param int $user_id
	 * @return UserMeta
	 */
	public function scopeGetSettings($query, $user_id) {
		return $query->where('user_id', '=', $user_id)->where('meta_key', '=', 'm12e-user-settings');
	}
}
