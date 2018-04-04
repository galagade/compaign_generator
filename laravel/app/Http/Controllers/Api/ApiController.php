<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use DB;
use Validator;
use Datatables;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;
use View;
use App\User;
use App\Models\Campaign;
use App\Models\CampaignQuestions;
use App\Models\CampaignQuestionsAnswers;
/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends Controller 
{

	public function form_post(Request $request){

		$rules = [
                'redirect_url'  => 'required',
                'campaign_id'=> 'required|valid_campaign',
        ];
        $rules = $this->campaign_questions($request, $rules);  
        $validator = Validator::make($request->all(), $rules);
                    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('error_edit', true);
        }else{
        	$campaign = Campaign::find($request->input('campaign_id'));
        	$questions = $campaign->Questions->all();
			foreach ($questions as $question) {
				$name = 'question_'.$question->id;
				$data=[
                    'question_id'=>$question->id,
                    'value'=>$request->input($name),
                           
                ];
				$answer = new CampaignQuestionsAnswers();
				$answer->fill($data)->save();
				
			}

        	$request->session()->flash('alert-sucess', "Your data has been submitted successfully");
        	return redirect()->back();
        }


	}

	private function campaign_questions($request, $rules){
		$campaign = Campaign::find($request->input('campaign_id'));
		if(isset($campaign->id)){
			$questions = $campaign->Questions->all();
			foreach ($questions as $question) {
				$name = 'question_'.$question->id;
				$rules[$name] = 'required';
			}
		}
		
		return $rules;
	}

	public function sync_campaign(){
		 $content=[];
        if(\File::exists(storage_path('app/campaigns_upload/queue_file.txt'))){
            $data = \Storage::disk('campaigns')->get('queue_file.txt');
            $content = json_decode($data);
        }
       
        if(empty( $content)){
     
        	echo json_encode(['status'=>false, 'msg'=>'file contains not data']);
        }else{
        	$remain = [];
        	$size = [];
        	foreach ($content as $data) {
        		$remain_data =[];
        		$rema_num =[];
        		foreach ($data->data as $questions) {
        			$remain_answers = [];
        			for ($i=0; $i < sizeOf($questions->answers); $i++) { 
        				if($i < 50){
        					 $qun=[
                                'question_id'=>$questions->question_id,
                                'value'=>$questions->answers[$i],         
                            ];
                            $answer = new CampaignQuestionsAnswers();
                            $answer->fill($qun)->save();
        				}else{
        					$remain_answers[] = $questions->answers[$i]; 
        					$rema_num[] = $questions->answers[$i];
        				}
        			}

        			$remain_data[] = (object)['question_id'=> $questions->question_id, 'answers'=>$remain_answers]; 
        		}
        		$remain[]= (object)['id'=>$data->id, 'data'=>$remain_data];
        		$size[] =['id'=>$data->id , 'num_of_questions'=>sizeof($remain_data), 'num_of_answer_remaining'=> sizeof($rema_num)];
        	}
        	
        	\Storage::disk('campaigns')->put('queue_file.txt', json_encode($remain));
        	
        	echo json_encode(['status'=>true, 'msg'=>'sync done remaing data is', 'info'=>$size ]);
        }
       
    }
}