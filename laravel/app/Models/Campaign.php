<?php
/**
 * Model genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;
	
	protected $table = 'campaigns';
	
	protected $hidden = [
        
    ];
    protected $fillable = [
        'user_id', 'name', 'description', 'status_id', 'questions'
    ];
	protected $guarded = [];

	protected $dates = ['deleted_at'];

	public function Questions() {
        return $this->hasMany('\App\Models\CampaignQuestions');
    }
}
