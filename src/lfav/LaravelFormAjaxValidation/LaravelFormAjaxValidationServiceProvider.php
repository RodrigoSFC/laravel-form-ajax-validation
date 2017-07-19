<?php namespace Lfav\LaravelFormAjaxValidation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class LaravelFormAjaxValidationServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        Route::post('validation',function(Request $request){
            $class = $request->class;
            $class = str_replace('/','\\',$class);
            $my_request = new $class();
            $get	=	$request->all();
			$get_request = [];
			$ii = 0;
			$rules_request = [];
			foreach($get as $key => $value){
				if  (is_array($value)){
					foreach ($value as $campos => $valores){
						if  ($ii==0){
							$get_request = array($campos => $valores);
							$rules_request = array($campos);
						} else {
							$get_request = array_merge($get_request, array($campos => $valores));
							$rules_request = array_merge($rules_request, array($campos));
						}
						$ii++;
					}
				}
				if  ($ii==0){
					$get_request = array($key => $value);
				} else {
					$get_request = array_merge($get_request, array($key => $value));
				}
				$ii++;
			}
			$rules_valida = $my_request->rules();
			foreach ($rules_valida as $key => $value){
				if  (!in_array($key,$rules_request)){
					unset($rules_valida[$key]);
				}
			}
            $validator = Validator::make($get_request,$rules_valida,$my_request->messages());
            $validator->setAttributeNames($my_request->attributes());
            if($request->ajax()){
                if ($validator->fails())
                {
                    return response()->json(array(
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray()

                    ));
                }else{
                    return response()->json(array(
                        'success' => true,
                    ));
                }
            }
        });
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/vendor/lfav'),
        ]);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}

}
