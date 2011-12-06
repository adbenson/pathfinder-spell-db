$(document).ready(function() {
	$('select[name=class]').change(class_change);
	$('select[name=level]').change(level_change);
	$('fieldset legend').click(toggle_fieldset);
	
	$('form').submit(handle_submit);
	
	$('fieldset.closed .content').hide();
	
	load_spells();
});

function handle_submit(e) {
	e.preventDefault();
	
	var options = $(this).serialize();
	
	$.ajax({
		url: this.action,
		dataType: 'json',
		type: 'post',
		data: options,
		success: load_spells
	});
}

function toggle_fieldset() {
	var fieldset = $(this).parent();
	var content = fieldset.children('.content');
	
	content.slideToggle();
	fieldset.toggleClass('open');
	fieldset.toggleClass('closed');
}

function class_change() {
	var class_id = $('select[name=class]').val();
	
	$.ajax({
	  url: 'spell_db/get_levels',
	  dataType: 'json',
	  type: 'post',
	  data: 'class=' + class_id,
	  success: function(response) {
			var levels = $('select[name=level]').empty();
			
			$.each(response, function(i, value) {
				levels.append($("<option />").val(value).text(value));
			});
			
			load_spells();
		}
	});
}

function level_change() {
	var level = $('select[name=level]').val();
	
	$.ajax({
		  url: 'spell_db/set_level',
		  dataType: 'json',
		  type: 'post',
		  data: 'level=' + level,
		  success: function(response) {
			load_spells();
		}
	});
	
}

function load_spells() {
	
	setSpinner(true);
	
	$.ajax({
		  url: 'spell_db/get_spells',
		  dataType: 'html',
		  type: 'post',
		  success: function(response) {
		
				$('#spells').html(response);
				
				setSpinner(false);
			}
	});
}

function setSpinner(value) {
	if (value) {
		$('#spell_table').addClass('spinner');
	}
	else {
		$('#spell_table').removeClass('spinner');
	}
}