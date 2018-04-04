<?php

namespace App\Http\Helpers;

use Session;
use App\Models\CampaignQuestionsAnswers;
class ViewHelper {

	private $cssFiles = [];
	private $jsFiles = [];
	private $pageTitle = null;
	private $pageContentTitle = null;
	private $pageContentDescription = null;
	private $activeNav = null;

	public function addJS($url) {
		$url = $this->getFullUrl($url);
		if (!in_array($url, $this->jsFiles)) {
			$this->jsFiles[] = $url;
		}
	}

  public function getAnswer($id, $num, $current, $filter_id){ 
    if($current > 1){
      $take = 50 * (int)$current;   
    }else{
      $take = 0;
    }

    if(!empty($filter_id)){
      $ans = CampaignQuestionsAnswers::where('question_id', $id)->where('value', $filter_id)->offset($take)->limit(50)->get();
    }else{
      $ans = CampaignQuestionsAnswers::where('question_id', $id)->offset($take)->limit(50)->get();
    }
    if(empty($ans)){
      return  Null;
    }elseif(isset($ans[$num])){
       return $ans[$num]->value;
    }else{
      return  Null;
    }
    
  }
  public function getAnswers($id){
     $query = \DB::table('campaign_questions_answers')->where('campaign_questions_answers.question_id', $id)->get();
    return $query;
  }
  public function answers_count($id){
    $query = \DB::table('campaign_questions_answers')->Join('campaign_questions', 'campaign_questions_answers.question_id', '=', 'campaign_questions.id')->where('campaign_questions.campaign_id', $id)->count();
    return $query;
  }

	public function addCSS($url) {
		$url = $this->getFullUrl($url);
		if (!in_array($url, $this->cssFiles)) {
			$this->cssFiles[] = $url;
		}
	}

	private function getFullUrl($url) {
		if (substr($url, 0, 4) === 'http') {
			return $url;
		} else {
			return \Illuminate\Support\Facades\URL::asset($url);
		}
	}

	public function renderJS() {
		foreach ($this->jsFiles as $file) {
			echo "<script type='text/javascript' src='$file'></script>" . PHP_EOL;
		}
	}

	public function renderCSS() {
		foreach ($this->cssFiles as $file) {
			echo "<link href='$file' rel='stylesheet'>" . PHP_EOL;
		}
	}
	
	public function renderPageTitle() {
		echo $this->pageTitle;
	}

	public function renderPageContentTitle() {
		echo $this->pageContentTitle;
	}

	public function renderPageContentDescription() {
		echo $this->pageContentDescription;
	}

	public function setPageTitle($pageTitle) {
		$this->pageTitle = $pageTitle;
	}

	public function setPageContentTitle($pageContentTitle) {
		$this->pageContentTitle = $pageContentTitle;
	}

	public function setPageContentDescription($pageContentDescription) {
		$this->pageContentDescription = $pageContentDescription;
	}

	public function setPageDetails($title, $contentTitle, $contentDescription = null) {
		$this->pageTitle = $title;
		$this->pageContentTitle = $contentTitle;
		$this->pageContentDescription = $contentDescription;
	}
	
	/**
	 * Echo's 'active' if the identifier matches the set navigation identifier. 
	 * Used for menus and navigation to add a css class when the current menu is active.
	 * @param array $identifier
	 */
	public function navActive($identifiers) {
		if (!is_array($identifiers)) {
			//Single identifier comparison
			if ($identifiers === $this->activeNav) {
				echo 'active';
			}
		} else {
			//Compare against array of identifiers
			foreach ($identifiers as $identifier) {
				if ($identifier === $this->activeNav) {
					echo 'active';
					return;
				}
			}
		}
		
		
	}
	
	/**
	 * Set the identifier for the currently active menu element.
	 * @param string $identifier
	 */
	public function setActiveNav($identifier) {
		$this->activeNav = $identifier;
	}
	
	
        private function hasErrors($field) {
            $errors = Session::get('errors');
            
            if (isset($errors)) {
                $fieldErrors = $errors->get($field);
                
                if (isset($fieldErrors) && !empty($fieldErrors)) {
                    return $fieldErrors;
                }
            }
            
            return false;
        }
        
        /**
         * Check if the given input name has validation errors and if it does return the has-error class
         * @param string $field
         * @return string
         */
        public function showHasError($field) {
            $errors = $this->hasErrors($field);
            if ($errors) {
                echo "has-error";       
                return;
            }
        }
        
        /**
         * Check if the given input name has validation errors and if it does print them out in a help-box
         * @param string $field
         * @return string
         */
        public function showErrors($field) {
            $errors = $this->hasErrors($field);
          
            if ($errors) {
                $msgs = implode(", ", $errors);
                echo "<span class='help-block'>$msgs</span>";
                return;
            }
        }
        
        /**
         * Takes the old value and the modelValue and determines which to sh
         * @param type $showOld
         * @param type $old
         * @param type $modelValue
         * @return string 
         */
        public function showValue($old, $model, $field, $default = "") {

            if (!is_null($old)) {
                return $old;
            }
            else {
                return (isset($model)) ? $model->$field : $default;
            }
        }
    public function oldValue($field, $pos){
		$old =  app('request')->old($field, null);
	    if (isset($old)) {
	        return $old[$pos];
	    }
	    return NULL;
	}

	public function handleType($field, $pos=0, $sig=false){
		if($sig){
			$value = app('request')->old($field, null);
		}else{
			$value = $this->oldValue($field, $pos);
		}
		
		if(!empty($value)){
			$type_array =['Checkbox', 'Dropdown', 'Multiselect', 'Radio'];
 			if(in_array($value, $type_array)){
 				echo "style='display:block'";
			}else{
				echo "style='display:none'";
			}
		}else{
			echo "style='display:none'";
		}
	}

	public function showHasErrorArray($field, $pos, $underscore =false){
		$value = $this->hasErrorsArray($field, $pos, $underscore);
		if($value){
			if(!$underscore){
				$data = $this->oldValue($field, $pos);
				if($data == NULL){
				  echo "has-error"; 
				}
			}else{
				echo "has-error"; 
			}
			 
		}

    }

    public function showErrorsArray($field, $pos, $underscore =false) {
        $errors = $this->hasErrorsArray($field, $pos,$underscore);
        
        if ($errors) {
           	if($underscore == false){
           		$data = $this->oldValue($field, $pos);
				if($data == NULL){
					$msgs = $errors[0];
					echo "<span class='help-block'>$msgs</span>";
				}
            		
           	}else{

           		$msgs = str_replace($pos, '', $errors[0]);
           		echo "<span class='help-block'>$msgs</span>";
           	}
            return;
        }
    }

    private function hasErrorsArray($field, $pos, $underscore =false){
      $errors = Session::get('errors');
      if (isset($errors)) {

      	if($underscore){
      		$fieldErrors = $errors->get($field.'_'.$pos);
      	}else{
      		$fieldErrors = $errors->get($field);
      		
      	}

        if (isset($fieldErrors) && !empty($fieldErrors)) {
            return $fieldErrors;
        }
      }
      return false;
      
    }

    public function CampaignForm($campaign){
    	echo '<div class="box-body">';

    	echo "<p> ".$campaign->description." </p>";
    	foreach ($campaign->Questions->all() as $question) {
    		
    		echo '<div class="form-group">';
    		echo '<label>';
    		echo $question->label;
    		echo '</label>';
    		echo $this->field_type($question);
    		echo '</div>';
    		
    	}
    	echo '</div>';
    }

    public function CampaignFormHtml($campaign){
      $css = asset('la-assets/css/bootstrap.css');
      $html ='<link href="'.$css.'" rel="stylesheet" type="text/css" />';
      $html .= \Form::open(['route' => 'node.form.post', 'id' => 'campaign-html-form']);
      
      $html .= '<input type="hidden" name="campaign_id"  value="'.$campaign->id.'">';
      $html .= '<input type="hidden" name="redirect_url"  value="sample">';
    	$html .= '<div class="box-body">';

    	$html .= "<p> ".$campaign->description." </p>";
    	foreach ($campaign->Questions->all() as $question) {
    		
    		$html .= '<div class="form-group">';
    		$html .= '<label>';
    		$html .= $question->label;
    		$html .= '</label>';
    		$html .=$this->field_type($question);
    		$html .= '</div>';
    		
    	}
    	$html .= '</div>';
      $html .= \Form::submit( 'Submit', ['class'=>'btn btn-success']);
      $html .= \Form::close();
    	return $html;
    }

    public function field_type($question){
    	switch ($question->type) {
    		case 'Checkbox':
    				$options = explode(',', $question->options);
    				$html ='';
    				for ($i=0; $i < sizeof($options); $i++) { 
    					$html .= "<div class='Checkbox'> <input type='Checkbox' required name='question_".$question->id."' value='".$options[$i]."' /><span> ".$options[$i]."</span></div> ";
    				}
    				return $html;
    			break;
    		case 'Dropdown':
    				$options = explode(',', $question->options);

    				$html = "<select class='form-control' required name='question_".$question->id."'>";
    				$html .= "<option value=''>Select ".$question->label."</option>";
    				for ($i=0; $i < sizeof($options); $i++) { 
    					$html .= "<option value='".$options[$i]."'>".$options[$i]."</option>";
    				}
    				$html .= "</select>";
    				return $html;
    			break;
        case 'Password':
            return "<input type='password' required name='question_".$question->id."' class='form-control' placeholder='".$question->label."' />";
          break;
        case 'Radio':
            $options = explode(',', $question->options);
            $html ='';
            for ($i=0; $i < sizeof($options); $i++) { 
              $html .= "<div class='Checkbox'> <input type='radio' required name='question_".$question->id."' value='".$options[$i]."'/><span> ".$options[$i]."</span></div> ";
            }
            return $html;
            break;
    		default:
    				return "<input type='text' required name='question_".$question->id."' class='form-control' placeholder='".$question->label."' />";
    			break;
    	}

    }   
      
}
