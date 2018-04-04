<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () { 
    return view('welcome');
}); 
/** sigup ***/
Route::get('signup', ['as' => 'node.signup', 'uses' => 'Auth\RegisterController@index']);
Route::post('signup/post', ['as' => 'node.signup.store', 'uses' => 'Auth\RegisterController@Post_Signup']);

Route::post('api/form/post', ['as' => 'node.form.post', 'uses' => 'Api\ApiController@form_post']);
Route::get('api/campaign/sync', ['as' => 'node.form.sync', 'uses' => 'Api\ApiController@sync_campaign']);
/* ================== Homepage + Admin Routes ================== */
 
require __DIR__.'/admin_routes.php';
//campaign
Route::group(['as' => $as, 'middleware' => ['auth']], function () { 
	/*****  campaign *************/
	Route::resource(config('laraadmin.adminRoute').'/campaign', 'campaign\CampaignController');
	Route::any(config('laraadmin.adminRoute').'/edit/campaign', ['as' => 'node.campaign.edit', 'uses' =>'campaign\CampaignController@edit_compaign']);
	Route::any(config('laraadmin.adminRoute').'/copy/{id}/campaign', ['as' => 'node.campaign.copy', 'uses' =>'campaign\CampaignController@Copy_campaign']);
	Route::get(config('laraadmin.adminRoute').'/upload/campaign', ['as' => 'node.campaign.upload.index', 'uses' =>'campaign\CampaignController@uploadIndex']);
	Route::post(config('laraadmin.adminRoute').'/upload/campaign/post',  ['as' => 'node.campaign.upload', 'uses' =>'campaign\CampaignController@upload_Post']);
	/*****  questions *************/
	Route::any(config('laraadmin.adminRoute').'/campaign/{id}/questions', ['as' => 'node.campaign.add.questions', 'uses' =>'campaign\CampaignController@addQuestions']);
	Route::any(config('laraadmin.adminRoute').'/campaign/{id}/questions/answers/{page?}', ['as' => 'node.campaign.questions.answers', 'uses' =>'campaign\CampaignController@Answers']);

	
	Route::post(config('laraadmin.adminRoute').'/storequestions', ['as' => 'node.campaign.store.questions', 'uses' => 'campaign\CampaignController@storequestions']);
	
	
	Route::get(config('laraadmin.adminRoute') . '/campaign_dt_ajax', ['as' => 'node.campaign.ajax', 'uses' => 'campaign\CampaignController@ajax']);
	Route::get(config('laraadmin.adminRoute') . '/questions/{id}/campaign_q_dt_ajax', ['as' => 'node.campaign.q.ajax', 'uses' => 'campaign\CampaignController@ajax_question']);
	Route::post(config('laraadmin.adminRoute') . '/questions/edit', ['as' => 'node.campaign.question.edit', 'uses' => 'campaign\CampaignController@edit_question']);
	Route::delete(config('laraadmin.adminRoute') . '/questions/{id}', ['as' => 'node.campaign.question.destroy', 'uses' => 'campaign\CampaignController@delete_question']);


	


});
 