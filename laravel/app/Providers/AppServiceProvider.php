<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;
use App\Models\Campaign;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        
        Validator::extend('has_array_data', function($attribute, $value, $parameters, $validator) {
            if(is_array($value) ){
                if(!empty($value)){
                    $status = true;
                    for($i=0; $i < sizeof($value); $i++){
                        if(empty($value[$i])){
                            $status = false;
                        }
                    }
                    return $status;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        });
         Validator::extend('has_value', function($attribute, $value, $parameters, $validator) {
            return false;
            // print_r($value);
            // die();
            // if(!empty($value)){
            //     $status = true;
                
            //     return false;
            // }else{
            //     return false;
            // }
           
        });

        Validator::extend('valid_campaign', function($attribute, $value, $parameters, $validator) {
            $Campaign = Campaign::find($value);
            if(isset($Campaign->id)){
                return true;
            }else{
                return false;
            }
            
        });
         
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('ViewHelper', 'App\Http\Helpers\ViewHelper');
    }
}
