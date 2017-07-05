# laravel-form-ajax-validation

##Installation

### 1. Composer

Add to the composer of your project

```console
composer require lfav/laravel-form-ajax-validation
```

Or edit your composer.json

```json
"require": {
    "lfav/laravel-form-ajax-validation": "dev-master"
},
```

### 2. Add the ServiceProvider

Open the file config/app.php

```php
"providers": {
    ...
    'Lfav\LaravelFormAjaxValidation\LaravelFormAjaxValidationServiceProvider',
    ...
},
```

### 3. Publish vendor resources

You need to publish the necessary views for create the scripts of jQuery

```console
$ php artisan vendor:publish
```

### 4. Laravel Request

Create a request

```console
$ php artisan make:Request TestRequest
```

Add the rules

```php
public function rules()
{
	return [
          'name'=>'required|max:5',
          'description'=>'required',
          'tags'=>'required|min:3',
	];
}
```

You also can add to the request custom error messages and change de attributes name

```php
public function messages()
{
	return [
          'name.required'=>'Do not forget your name',
          'description.required'=>'You need the description',
          'name.max'=>'Your name have less than 5 letters?',
	];
}

public function attributes(){
        return [
            'name'=>'Your name',
            'tags'=>'The tags',
        ];
    }
```

### 5. Add to the view

Create your form

```html
<form method="post" action="<?=url('save_form')?>" id="myform">
    <input type="hidden" name="_token" value="<?=csrf_token()?>">
    <div class="form-group">
        <label for="label_name">Name</label>
		<div class="div-error">
			<input type="text" name="name" id="name" class="form-control">
		</div>
    </div>
    <div class="form-group">
        <label for="label_description">Description</label>
		<div class="div-error">
			<textarea type="text" name="description" id="description" rows="5" class="form-control">
			</textarea>
		</div>
    </div>
    <div class="form-group">
        <label for="label_tags">Tags</label>
		<div class="div-error">
			<input type="text" name="tags" id="tags" class="form-control">
		</div>
    </div>
    <input type="submit" value="Save" class="btn btn-success">
</form>
```

Add the jQuery and include the view that have the ajax script

```javascript
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
@include('vendor.lfav.ajax_script', ['form' => '#myform','request'=>'App/Http/Requests/TestRequest','on_start'=>true])
```

You need jQuery 1.11.2 or higher
