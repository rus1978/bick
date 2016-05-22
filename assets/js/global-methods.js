$(function(){
o_method= {//global
	cont_el: $('#main .content-block'),
	
	ajax: function(options){

		options = $.extend({
			mode: false,
			callback: function(){},
			success: function(){},
			data: {}
		}, options);
		
		if(options.mode === false){
			o_method.alert({
				message: 'Не указан метод ajax запроса',
				type: 'error'
			});	
			return;
		}
		var body_el= $('body');

		$.ajax({
			url: '/index-ajax.php?mode='+ options.mode,
			data: options.data,
			type: 'post',

			dataType:'json',
			beforeSend: function(){
				body_el.addClass('loading');
			},
			complete: function(){
				body_el.removeClass('loading');
			},
			error:function(xhr){
				console.error( xhr.responseText );
			},
			success:function( data ){
				if(!data)data={};
				
				var isExistsMess= typeof data.message != 'undefined';
				var isExistsStat= typeof data.status != 'undefined';
				
				if(//дефолтное сообщение об ошибке
					!isExistsStat ||
					(data.status == 'error' && (!isExistsMess || data.message.length==0 ))
				){
					o_method.alert({
						message: 'Не удалось выполнить ajax запрос',
						type: 'error'
					});
				}
				else if(isExistsMess && data.message.length){
					o_method.alert({
						message: data.message,
						type: data.status
					});
				}
				
				if(isExistsStat && data.status == 'success'){
					options.success(data);
				}
				options.callback(data);
			}

		});	
	},


	/**
	 * print
	 * 
	 * @desc Out content.
	 * 
	 * @param cont {string|obj html} - Выводимый контент. @required
	 * 
	 * @return - no.
	 
	 * example:
		o_method.print('<h1>Hello!</h1>');
	 */
	print: function(html){
		o_method.cont_el.children().wrapAll('<div>');
		var old_cont= o_method.cont_el.children('div');
		
		var new_cont= $('<div>').html( html ).appendTo( o_method.cont_el ).hide(0);
		
		new_cont.add( old_cont ).slideToggle(function(){
			old_cont.remove();
			new_cont.children().unwrap();
		});
	},

	
	/**
	 * alert
	 * 
	 * @desc Show message.
	 * 
	 * @param $message {string} - Текст сообщения. @required
	 * @param $type {string} - Возможные значения (error|success). Default: 'success'.
	 * 
	 * @return - no.
	 
	 * example:
		o_method.alert({
			message: 'Не удалось выполнить',
			type: 'error'
		});
	 */	
	alert: function(options){
		options = $.extend({
			message: 'пустое сообщение',
			type: 'success'
		}, options);
		
		
		//Если бы время позволяло можно допилить свой попап со сменными статусами
		alert(options.message);
	}
};
});


