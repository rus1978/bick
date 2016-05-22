/*---------------------------------------
	My Contact
-----------------------------------------*/
$.fn.contact = function(){
	var base_el= this;
	var form_el= base_el.find('form');
	
	form_el.find('.butt-add').click(function(e){
		e.preventDefault();
		
		var el= $(this);
		var new_el= el.prev().clone().insertBefore(el);
		
		var new_inptxt= new_el.find('input[type=text]');
		var new_attr= new_inptxt.attr('name').replace(/^([a-z]+)\[.*/, '$1[]');
		
		new_inptxt.attr('name', new_attr).val('');
		
		new_el.find('input[type=checkbox]').attr('checked', false);
		new_el.hide(0).slideDown('fast');
	});
	
	form_el.submit(function(e){
		e.preventDefault();
		
		o_method.ajax({
			mode: 'contact-save',
			data: base_el.find('form').serialize(),
			success: function(data){
				o_method.print(data.html);
				o_method.cont_el.contact();
			}
		});
	});
}