/*---------------------------------------
	Main menu
-----------------------------------------*/
$(function(){
	var menu_el= $('#header .main-menu');

	menu_el.find('a').click(function(e) {
        var el= $(this);

		if( el.attr('href').charAt(0) != '#' )return;//оставил возможность работать и обычным ссылкам
		
		e.preventDefault();
	
		var mode= el.attr('href').substr(1);
		
		o_method.ajax({
			mode: mode,
			success: function(data){
				o_method.print(data.html);
				if( typeof $.fn[mode] != 'undefined'){
					o_method.cont_el[mode]();
				}
			}
		});
    });
});
/*---------------------------------------
	Authorization form
-----------------------------------------*/
$.fn.loginForm = function(){
	var base_el= this;
	var form_el= base_el.find('form');
	var inpreq_el= form_el.find('[data-required]');
	
	form_el.submit(function(e){
		e.preventDefault();
		
		var error= false;
		inpreq_el.each(function(){
			var el= $(this);

			if( el.val() < 3 ){
				error= true;
				el.addClass('error');
			}
			else el.removeClass('error');
		});
		
		if(error){
			o_method.alert({
				message: 'Пожалуйста заполните обязательные поля',
				type: 'error'
			});
			return;
		}
		
		o_method.ajax({
			mode: 'logged',
			data: form_el.serialize(),
			callback: function(data){
				if(data.status=='success')document.location.reload(true);
				else inpreq_el.addClass('error');
			}
		});
	});
}
/*---------------------------------------
	Public Phonebook
-----------------------------------------*/
$.fn.phonebook = function(){
	var base_el= this;
	var row_el= base_el.find('.phonebook-list [data-uid]');
	
	row_el.find('.butt').click(function(e){
		e.preventDefault();
		var link_el= $(this);
		
		var item= link_el.closest('[data-uid]');
		var detail_el= item.children('.details');

		if( detail_el.hasClass('processing') )return;//убираем дубликаты кликов
		
		var showDetails= function(){
			detail_el.addClass('processing').slideToggle(function(){
				link_el.toggleClass('active', detail_el.is(':visible'));
				detail_el.removeClass('processing');
			});
		}
		
		if( detail_el.length ){
			showDetails();
			return;
		}
		
		o_method.ajax({
			mode: 'get-user-details',
			data: {uid: item.data('uid')},
			success: function(data){
				detail_el= $(data.html).appendTo(item).hide(0);
				showDetails();
			}
		});
	});
	
	return
}