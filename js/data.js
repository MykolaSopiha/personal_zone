$(document).ready(function () {

	$.datepicker.setDefaults( $.datepicker.regional[ "ru" ] );
	$('#date_picker').datepicker({dateFormat: "dd-mm-yy"});
	$('#costs_table').DataTable();

	function post(path, parameters) {
	    var form = $('<form></form>');

	    form.attr("method", "post");
	    form.attr("action", path);

	    $.each(parameters, function(key, value) {
	        var field = $('<input></input>');

	        field.attr("type", "hidden");
	        field.attr("name", key);
	        field.attr("value", value);

	        form.append(field);
	    });

	    // The form needs to be a part of the document in
	    // order for us to be able to submit it.
	    $(document.body).append(form);
	    form.submit();
	}

	$('.action').on('click', 'img.delete_card', function(event) {
		event.preventDefault();
		let cost_id = $(this).parent().attr('cost-id');
		post('data.php', {deletecost: cost_id});
	});

});