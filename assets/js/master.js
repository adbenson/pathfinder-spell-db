$(document).ready(function() {
	$('select[name=class]').change(class_change);
	$('select[name=level]').change();
});

function class_change() {
	var class_id = $(this).val();
	
	$.ajax({
	  url: 'get_levels',
	  dataType: 'ajax',
	  type: 'post',
	  success: function(data) {
			alert(data);
		}
	});
}