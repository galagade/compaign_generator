<?php
/**
 * Model genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignQuestions extends Model
{
    use SoftDeletes;
	
	protected $table = 'campaign_questions';
	
	protected $hidden = [
        
    ];

	protected $guarded = [];

	protected $dates = ['deleted_at'];
	public function answers() {
        return $this->hasMany('\App\Models\CampaignQuestionsAnswers', 'question_id');
    }
}
