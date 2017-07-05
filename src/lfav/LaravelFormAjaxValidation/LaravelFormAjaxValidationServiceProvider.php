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
        Route::post('validation',function(Request $request,$table){
            $class = $request->class;
            $class = str_replace('/','\\',$class);
            $my_request = new $class();
            $get	=	$request->all();
			$get_request = [];
			$ii = 0;
			foreach($get as $key => $value){
				if  (is_array($value)){
					foreach ($value as $campos => $valores){
						if  ($ii==0){
							$get_request = array($campos => $valores);
						} else {
							$get_request = array_merge($get_request, array($campos => $valores));
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
			$get_request = array_merge($get_request, $request->except($table));
            $validator = Validator::make($get_request,$my_request->rules(),$my_request->messages());
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
            __DIR__.'/views' => base_path('resources/views/vendor/lfavp'),
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
