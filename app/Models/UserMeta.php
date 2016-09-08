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
    
    // --- Formatted Responses ---
    
    /**
     * Returns an array of the user settings.
     * @param int $user_id
     * @return string[]|boolean
     */
    public function GetUserSettings($user_id = 0) {
        $user_id = ($user_id == 0) ? get_current_user_id() : $user_id;
        
        $resultant = $this->getSettings($user_id)->first();
        
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
        
        $entry = ($this->getUserSettings($user_id) != false) ? $this->getSettings($user_id)->first() : new UserMeta();
        $entry->user_id    = $user_id;
        $entry->meta_key   = 'm12e-user-settings';
        $entry->meta_value = serialize($content); 
        $entry->save();
        
        return $entry;
    }

}
