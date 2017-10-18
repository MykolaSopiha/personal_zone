$(document).ready(function(){

	$('#cards_table').DataTable();

	$(function() {
		$('#card_date').datepicker({
			dateFormat: "mm-yy",
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			onClose: function(dateText, inst) {
				function isDonePressed(){
					return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
				}
				if (isDonePressed()){
					var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
					var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
					$(this).datepicker('setDate', new Date(year, month, 1)).trigger('change');
					$('.date-picker').focusout()//Added to remove focus from datepicker input box on selecting date
				}
			},
			beforeShow : function(input, inst) {
				inst.dpDiv.addClass('month_year_datepicker')
				if ((datestr = $(this).val()).length > 0) {
					year = datestr.substring(datestr.length-4, datestr.length);
					month = datestr.substring(0, 2);
					$(this).datepicker('option', 'defaultDate', new Date(year, month-1, 1));
					$(this).datepicker('setDate', new Date(year, month-1, 1));
					$(".ui-datepicker-calendar").hide();
				}
			}
		})
	});

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
		let card_number = $(this).parent().attr('card-number');
		post('cards.php', {deletecard: card_number});
	});

	$('.action').on('click', 'img.disable_card', function(event) {
		event.preventDefault();
		let card_number = $(this).parent().attr('card-number');
		post('cards.php', {disablecard: card_number});
	});

	$('.action').on('click', 'img.activate_card', function(event) {
		event.preventDefault();
		let card_number = $(this).parent().attr('card-number');
		post('cards.php', {activatecard: card_number});
	});

});