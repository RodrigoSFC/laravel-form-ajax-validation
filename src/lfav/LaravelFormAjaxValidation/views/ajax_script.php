<script>
    <?php
    if(!isset($form)){
        $form = 'form';
    }
    if(!isset($on_start)){
        $on_start = false;
    }
    if (!isset($auto_focus)) {
        $auto_focus = true;
    }
    ?>
    var validated = false;
    var buton_submit = false;
    var my_form = $('<?=$form?>');
    var name_class = '<?=$request?>';
    var on_start = '<?=$on_start?>';
    var auto_focus = '<?=$auto_focus?>';

    initialize();

    function initialize(){
        my_form.on('submit',function(){
            if(validated == true) {
                var $form = $(this);
                if ($form.data('submitted') === true) {
                    e.preventDefault();
                } else {
                    $form.data('submitted', true);
                }
                return true;
            } else {
                return validate();
            }
        });

        my_form.find("input[type=submit]").on('click',function(e){
            e.preventDefault();
            buton_submit = true;
            validate();
        });

		var form_group = my_form.find('.div-error');		
		form_group.find('input,select').each(function(i, field) {
			if  ($(field).parent().find('.help-block').attr("class")	!=	"help-block with-errors"){
				$(field).parent().append('<div class="help-block with-errors ' + $(field).attr('id') + '"></div>');
			}
		});

        my_form.find(':input').each(function(){
            $(this).on('change',function(){
                validate();
            });
        });

        if(on_start=='1'){
            validate();
        }

        if (auto_focus) {
            $(':input:enabled:visible:first').focus();
        }
    }


    function validate(){
        var data = my_form.serializeArray();

        data.push({name:'class',value:name_class});

        for(var i = 0; i < data.length; i++) {
            item = data[i];
            if(item.name == '_method'){
                data.splice(i,1);
            }
        }

        $.ajax({
            url: '<?=url('validation')?>',
            type: 'post',
            data: $.param(data),
            dataType: 'json',
            success: function(data){
                if(data.success){
                    $.each(my_form.serializeArray(), function(i, field) {
						var input = $('input[name="'+field.name+'"]');
						if  (typeof(input.attr('id')) != "undefined"){
							var father = input.parents('.div-error');
							var label = $("label[for='label_"+input.attr('id')+"']");
							label.removeClass('has-error');
							label.addClass('has-success');
							father.removeClass('has-error');
							father.addClass('has-success');
							father.find('.help-block.'+input.attr('id')).html('');
							father.find('.help-block.'+input.attr('id')).height(0);
						}
                    });

                    validated = true;
                    if(buton_submit==true){
                        my_form.submit();
                    }
                } else {
                    var campos_error = [];

                    $.each(data.errors,function(key, msg){
                        var campo = $('#'+key);
                        var father = campo.parents('.div-error');
						var label = $("label[for='label_"+campo.attr('id')+"']");
						label.removeClass('has-success');
						label.addClass('has-error');
                        father.removeClass('has-success');
                        father.addClass('has-error');
						father.find('.help-block.'+campo.attr('id')).html(msg[0]);
						father.find('.help-block.'+campo.attr('id')).height(21);
                        campos_error.push(key);
                    });
					
                    $.each(my_form.serializeArray(), function(i, field) {
						var input = $('input[name="'+field.name+'"]');
						if  (typeof(input.attr('id')) != "undefined"){
							if ($.inArray(input.attr('id'), campos_error) === -1){
								var father = input.parents('.div-error');
								var label = $("label[for='label_"+input.attr('id')+"']");
								label.removeClass('has-error');
								label.addClass('has-success');
								father.removeClass('has-error');
								father.addClass('has-success');
								father.find('.help-block.'+input.attr('id')).html('');
							}
						}
                    });

                    validated = false;
                    buton_submit = false;
                }
            },
            error: function(xhr){
                console.log(xhr.status);
            }
        });
        return false;
    }
</script>