<?php
namespace App\Http\Controllers\Campaign;

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
 * Class CampaignController
 * @package App\Http\Controllers
 */
class CampaignController extends Controller 
{ 
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $show_action = true;
    public $view_col = 'name';
    public $Headings = ['id', 'name', 'description', 'N0. of questions'];
    public $listing_cols = ['id', 'name', 'description', 'questions'];
    public $Headings_q = ['id','Question name', 'options', 'type'];
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application Campaigns.
     *
     * @return Response
     */
    public function index()
    {
       $module = Module::get('Campaign');
  
        return View('campaign.index', [
            'show_actions' => $this->show_action,
            'listing_cols' => $this->Headings,
            'module' => $module 
        ]);
        
    }

    public function uploadIndex(){
        $module = Module::get('Campaign');
        return View('campaign.upload');
    }
    public function upload_Post(Request $request){
        ini_set('max_execution_time', 900);
        $rules = [
            'name'      => 'required',
            'campaign_data_file'=> 'required',
        ];
           
        $validator = Validator::make($request->all(), $rules);
                    
        if ($validator->fails()) {
                
                return redirect()->back()->withErrors($validator)->withInput()->with('error_edit', true);
        }else{
            if($request->hasFile('campaign_data_file')){
                $data = $request->input('campaign_data_file');
                $file = $request->file('campaign_data_file');
                $data_file = $file->getClientOriginalName();
              
                if($file->getClientOriginalExtension()  != 'csv'){
                    $request->session()->flash('alert-error', 'Please upload file collect file format');
                    return redirect()->back()->withInput()->with('error_edit', true);
                }
                $csv = file_get_contents($_FILES['campaign_data_file']['tmp_name']);// read file data
                $csv = explode("\n", $csv); 
                foreach($csv as $k=>$v) {
                    $csv[$k] = explode(",", $v);
                }
                
                $i =0;
                 
                $question = [];
                foreach ($csv as $key => $value) {

                        if($i == 0){
                            for ($t=0; $t < sizeof($value); $t++) { 
                               $answers = [];
                               
                               $question[] =(object)['question'=>str_replace('"', '',strip_tags($value[$t])), 'answers'=>$answers];
                            }
                        }else{
                            for ($t=0; $t < sizeof($value); $t++) {
                             $question[$t]->answers[] = str_replace('"', '', strip_tags($value[$t]));
                            }
                            
                        }
                       $i++; 
                      
                    }
                    $user =Auth::user();
                    $new_cap = new Campaign();
                    $new_cap->name = $request->input('name');
                    $new_cap->user_id = $user->id;
                    $new_cap->description  = $request->input('name');
                    $new_cap->questions  = 0;
                    $new_cap->save();
                    $campaign_id = $new_cap->id;
                    $rn = 0;
                    $remain = [];
                    $failed =[];
                    foreach ($question as $qu) {
                        if(array_key_exists('question', $qu)){
                           
                                $type = $this->get_type($qu->question);
                                 $data=[
                                        'campaign_id'=>$new_cap->id,
                                        'label'=>str_replace('"', '', $qu->question),
                                        'options'=>$type->values,
                                        'type'=>$type->type,
                                    ];
                                $new_question = new CampaignQuestions();
                                $new_question->fill($data)->save();
                                $campaign = Campaign::find($new_cap->id);
                                $campaign->questions = ($campaign->questions + 1);
                                $campaign->save();
                                $remain_questions = [];
                                for ($i=0; $i < sizeof($qu->answers) ; $i++) {
                                    if($i < 50){ 
                                        $data=[
                                            'question_id'=>$new_question->id,
                                            'value'=>$qu->answers[$i],
                                                   
                                        ];
                                        $answer = new CampaignQuestionsAnswers();
                                        $answer->fill($data)->save();
                                    }else{
                                        $remain_questions[]= $qu->answers[$i];
                                    }
                                }
                                $remain[] = (object)['question_id'=> $new_question->id, 'answers'=> $remain_questions];
                                $rn++;
                        }else{
                           $failed[] = $qu;
                        }
                        // $rn++;
                    }
                   
                    $content =[];
                    if(\File::exists(storage_path('app/campaigns_upload/queue_file.txt'))){
                         $data = \Storage::disk('campaigns')->get('queue_file.txt');
                         $content = json_decode($data);
                    }

                    if(empty($content)){
                       $content[] = (object)['id'=>$campaign_id, 'data'=>$remain];
                    }else{
                        foreach ($content as $value) {
                           
                            if($value->id == $campaign_id){
                                $value->data[] = $remain; 
                            }
                        }
                    }
                    $file_name = "upload_error".time().".txt";
                    \Storage::disk('campaigns')->put('queue_file.txt', json_encode($content));
                    \Storage::disk('campaigns')->put($file_name, json_encode($failed));
                   
                if(!empty($remain)){
                    $request->session()->flash('alert-success', "Campaign has been uploaded successfully, and the remaining data is on the queue");
                }else{
                    $request->session()->flash('alert-success', "Campaign has been uploaded successfully");
                }
             }else{
                $request->session()->flash('alert-error', 'Please upload file collect file format');
             }
       
            return redirect()->route('node.campaign.upload.index');
        }
        

    }

    public function get_type($question){
      if(strpos($question, 'Gender')){
        return (object)['type'=>'Dropdown', 'values'=>'Male, Female'];
      }
      if(strpos($question, 'Date ')){
        return (object)['type'=>'Date', 'values'=>''];
      }
      if(strpos($question, 'mobile')){
        return (object)['type'=>'Mobile', 'values'=>''];
      }
       return (object)['type'=>'TextField', 'values'=>''];
       
    }
    public function ajax(){
        $module = Module::get('Users');
        
        if(Module::hasAccess($module->id)) {
             $values = DB::table('campaigns')->select($this->listing_cols)->whereNull('deleted_at');
         }else{
            $user =Auth::user();
            $values = DB::table('campaigns')->select($this->listing_cols)->where('user_id', $user->id)->whereNull('deleted_at');
         }
        
        $out = Datatables::of($values)->make();
        $data = $out->getData();

        $fields_popup = ModuleFields::getModuleFields('Campaign');
        
        for($i=0; $i < count($data->data); $i++) {
            $name =[];
            for ($j=0; $j < count($this->listing_cols); $j++) { 
                $col = $this->listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                
                if($col == $this->view_col) {
                    $name[]= $data->data[$i][$j];
                    $data->data[$i][$j] = '<a href="'.url(config('laraadmin.adminRoute') . '/campaign/'.$data->data[$i][0]).'">'.$data->data[$i][$j].'</a>';
                }
                
            } 
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("Campaign", "edit")) {
                   
                  
                  
                     $output .= "<a href='#' class='btn btn-warning btn-xs edit_btn' data-name='".$name[0]."' data-meta='".json_encode($data->data[$i])."' data-toggle='modal' data-target='#EditModal' style='display:inline;padding:2px 5px 3px 5px;' data-toggle='tooltip' title='select to edit Campaign' ><i class='fa fa-edit'></i></a>";
                }
                if(Module::hasAccess("Campaign", "create")) {
                     $output .= '<a href="'.url(config('laraadmin.adminRoute') . '/copy/'.$data->data[$i][0].'/campaign').'" class="btn btn-primary btn-xs" style="display:inline;padding:2px 5px 3px 5px; margin-left:5px;" data-toggle="tooltip" title="select to duplicate Campaign" ><i class="fa fa-copy"></i></a>';
                }
                
                if(Module::hasAccess("Campaign", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') .'.campaign.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit" data-toggle="tooltip" title="select to delete Campaign" ><i class="fa fa-times"></i></button>';
                    $output .= Form::close();
                }
                $data->data[$i][] = (string)$output;
            }
        }
        $out->setData($data);
        return $out;
    }

    public function Copy_campaign($id, Request $request){
         if(Module::hasAccess("Campaign", "create")) {
            $campaign =  Campaign::find($id);
             if(isset($campaign->id)) {
                $user =Auth::user();
                $new_cap = new Campaign();
                $new_cap->name = $campaign->name;
                $new_cap->user_id = $user->id;
                $new_cap->description  = $campaign->description;
                $new_cap->questions  = $campaign->questions;
                $new_cap->save();
                foreach ($campaign->Questions->all() as $question) {
                     $data=[
                            'campaign_id'=>$new_cap->id,
                            'label'=>$question->label,
                            'options'=>$question->options,
                            'type'=>$question->type,
                        ];
                    $new_question = new CampaignQuestions();
                    $new_question->fill($data)->save();
                }

                $request->session()->flash('alert-success', "Campaign has been duplicated successfully");

            } else {
                $request->session()->flash('alert-error', "Invalid Campaign");
                
            }
            return redirect(config('laraadmin.adminRoute')."/campaign");
            
         }
    }

    public function store(Request $request)
    {
        if(Module::hasAccess("Campaign", "create")) {
        
            $rules = Module::validateRules("Campaign", $request);
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput()->with('error_add', true);
            }
                $user =Auth::user();
                $data=[
                    'name'=>$request->input('name'),
                    'description'=>$request->input('description'),
                    'questions'=>$request->input('questions'),
                    'status_id'=>1,
                    'user_id'=>$user->id,
                ];
                $questions = new Campaign();
                $questions->fill($data)->save();
                $insert_id = $questions->id;
            $request->session()->flash('alert-success', "Campaign has been created successfully.");
            return redirect()->route('node.campaign.add.questions', ['id'=>$insert_id]);
            
        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }

    }

    public function destroy($id, Request $request)
    {
       
        if(Module::hasAccess("Campaign", "delete")) {
         
            Campaign::find($id)->delete();
            $request->session()->flash('alert-success', "Campaign has been deleted successfully.");
            return redirect(config('laraadmin.adminRoute')."/campaign");
        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
    }

     public function edit_compaign(Request $request){
        $campaign = Campaign::find($request->input('id'));
        if(isset($campaign->id)) {
            
            $rules = [
                'name'      => 'required',
               
                'description'=> 'required',
            ];
           
            $validator = Validator::make($request->all(), $rules);
                    
            if ($validator->fails()) {
                
                return redirect()->back()->withErrors($validator)->withInput()->with('error_edit', true);
            }else{
                $campaign->name = $request->input('name');
                $campaign->description = $request->input('description');
                $campaign->save();
                $request->session()->flash('alert-success', "Campaign has been updated successfully.");
                return redirect(config('laraadmin.adminRoute')."/campaign");
            }
        }else{
            $request->session()->flash('alert-error', "Invalid Campaign");
            return redirect(config('laraadmin.adminRoute')."/campaign");
        }
    }


    /**
     * Display the specified campaign.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Module::hasAccess("Campaign", "view")) {
            
            $campaign = Campaign::find($id);
            if(isset($campaign->id)) {
                if(empty($campaign->Questions->all())){
                   return redirect()->route('node.campaign.add.questions', ['id'=>$campaign->id]);
                }
                $module = Module::get('Campaign');
                return view('campaign.show', [
                   'show_actions' => $this->show_action,
                    'listing_cols' => $this->Headings_q,
                    'module' => $module ,
                    'question_types'=>DB::table('module_field_types')->get()
                ])->with('campaign', $campaign);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("Campaign"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute')."/");
        }
    }

    public function addQuestions($id){
       $campaign = Campaign::where('id', '=', $id)->first();
       if(!$campaign){
        return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("Campaign"),
                ]);
       }
       return View('campaign.add_questions', [
            'campaign' => $campaign,
            'question_types'=>DB::table('module_field_types')->get()
            
        ]);

    }
    public function storequestions(Request $request){
        $campaign = Campaign::find($request->input('campaign_id'));
        if(isset($campaign->id)) {
            $data_q = $request->all();
            if(!array_key_exists('main_page', $data_q)){
                $type = $request->input('type');
                $rules = [
                    'name'      => 'required',
                    'type' => 'required',
                ];
                $rules = $this->values_required($type, $rules, true);
            }else{
                $type = $request->input('type');
                $rules = [
                    'name'      => 'required|has_array_data',
                    'type' => 'required|has_array_data',
                 ];
                $rules = $this->values_required($type, $rules);
            }
            $validator = Validator::make($request->all(), $rules);
                    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput()->with('error_add', true);
            }else{
                if(!array_key_exists('main_page', $data_q)){
                    $data=[
                            'campaign_id'=>$request->input('campaign_id'),
                            'label'=>$request->input('name'),
                            'options'=>str_replace("'", '', $request->input('values')),
                            'type'=>$request->input('type'),
                        ];
                    $questions = new CampaignQuestions();
                    $questions->fill($data)->save();
                    $campaign->questions = $campaign->questions + 1;
                    $campaign->save();
                    $request->session()->flash('alert-success', "Campaign question has been created successfully.");
                }else{
                    for ($i=0; $i < (int)$request->input('total'); $i++) { 
                        $data=[
                            'campaign_id'=>$request->input('campaign_id'),
                            'label'=>$request->input('name')[$i],
                            'options'=>str_replace("'", '', $request->input('values_'.$i)),
                            'type'=>$request->input('type')[$i],
                        ];
                        $questions = new CampaignQuestions();
                       $questions->fill($data)->save();
                       
                    }
                    $request->session()->flash('alert-success', "Campaign questions has been created successfully.");
                }
                
                 
                return redirect(config('laraadmin.adminRoute')."/campaign/".$request->input('campaign_id'));
            }
        }else{
            $request->session()->flash('alert-error', "Invalid Campaign");
            return redirect(config('laraadmin.adminRoute')."/campaign");
        }
         
    }
    private function values_required($data, $rules, $sig=false){
        $type_array =['Checkbox', 'Dropdown', 'Multiselect', 'Radio'];
        if($sig){
            if(in_array($data, $type_array)){
                   $rules['values'] = 'required';
            }
        }else{
            for($i = 0; $i < sizeof($data); $i++){
                if(in_array($data[$i], $type_array)){
                         $rules['values_'.$i] = 'required';
                }
            }
        }
        return $rules;
    }

    public function ajax_question($id){
        $listing_cols = ['id','label', 'options', 'type'];
        $values = DB::table('campaign_questions')->select($listing_cols)->where('campaign_id', $id)->whereNull('deleted_at');

        $out = Datatables::of($values)->make();
        $data = $out->getData();
        for($i=0; $i < count($data->data); $i++) {
            for ($j=0; $j < count($listing_cols); $j++) { 
                $col = $listing_cols[$j];
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("Campaign", "edit")) {
                    $output .= "<a href='#' class='btn btn-warning btn-xs edit_btn' data-meta='".json_encode($data->data[$i])."' data-toggle='modal' data-target='#EditModal' style='display:inline;padding:2px 5px 3px 5px;'><i class='fa fa-edit'></i></a>";
                }
                
                if(Module::hasAccess("Campaign", "delete")) {
                    $output .= Form::open(['route' => ['node.campaign.question.destroy', $data->data[$i][0]], 'method' => 'delete', 'style'=>'display:inline']);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
                    $output .= Form::close();
                }
                $data->data[$i][] = (string)$output;
            }
        }
        $out->setData($data);
        return $out;
    }

    public function edit_question(Request $request){
        $question = CampaignQuestions::find($request->input('id'));
        if(isset($question->id)) {
            $type = $request->input('type');
            $rules = [
                'name'      => 'required',
                'type' => 'required',
            ];
            $rules = $this->values_required($type, $rules, true);
             $validator = Validator::make($request->all(), $rules);
                    
            if ($validator->fails()) {
                
                return redirect()->back()->withErrors($validator)->withInput()->with('error_edit', true);
            }else{
                $question->label = $request->input('name');
                $question->type = $request->input('type');
                $question->options = $request->input('values');
                $question->save();
                $request->session()->flash('alert-success', "Campaign question has been updated successfully.");
                return redirect(config('laraadmin.adminRoute')."/campaign/".$request->input('campaign_id'));
            }
        }else{
            $request->session()->flash('alert-error', "Invalid Campaign");
            return redirect(config('laraadmin.adminRoute')."/campaign");
        }
    }

    public function delete_question($id, Request $request){
        $question = CampaignQuestions::find($id);
        if(isset($question->id)) {

            if(!empty($question->answers->all())){
                $request->session()->flash('alert-error', "Campaign question delete failed, you cannot delete a questions that contains answers.");
                return redirect(config('laraadmin.adminRoute')."/campaign/".$question->campaign_id);
            }else{
                $cam_id  = $question->campaign_id;

                $question->delete();
                $campaign =  Campaign::find($cam_id);
                $campaign->questions = $campaign->questions - 1;
                $campaign->save();
                $request->session()->flash('alert-success', "Campaign question has been deleted successfully.");
                 return redirect(config('laraadmin.adminRoute')."/campaign/".$cam_id);
            }
        }else{
            $request->session()->flash('alert-error', "Invalid Campaign Question");
            return redirect(config('laraadmin.adminRoute')."/campaign");
        }
    }

    public function Answers($id, $page, Request $request){
        $campaign = Campaign::find($id);
        if(isset($campaign->id)) {
            // $total = sizeof($campaign->Questions->all());
            // $total = $total * 2;
            // $query = \DB::table('campaign_questions_answers')->select('campaign_questions_answers.*')->Join('campaign_questions', 'campaign_questions_answers.question_id', '=', 'campaign_questions.id')->where('campaign_questions.campaign_id', $campaign->id)->orderBy('campaign_questions_answers.id', 'asc')->take($total)->get();
            $data_question  = [];//$this->tablePaginate($query);
            if(empty($page)){
                $page = 1;
            }
            $module = Module::get('Campaign');
                return view('campaign.answer', [
                   'show_actions' => $this->show_action,
                    'listing_cols' => $campaign->Questions,
                    'module' => $module ,
                    'data_question'=> $data_question,
                    'filter'=>$request->all(),
                    'current'=> $page
                ])->with('campaign', $campaign);
        }else{
            $request->session()->flash('alert-error', "Invalid Campaign");
            return redirect(config('laraadmin.adminRoute')."/campaign");
        }
    }
    public function tablePaginate($query) {
        // $rpp = Config::get('storefronts-backoffice.pagination-default');
        // if (\Request::has('rpp')) {
            $rpp = \Request::get('rpp');
        // }

        return $query->paginate($rpp);
    }
}