<?php
/**
 * Model genrated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignQuestionsAnswers extends Model
{
    use SoftDeletes;
	
	protected $table = 'campaign_questions_answers';
	
	protected $hidden = [
        
    ];

	protected $guarded = [];

	protected $dates = ['deleted_at'];
	public function question() {
        return $this->belongsTo('\App\Models\CampaignQuestions');
    }
}
