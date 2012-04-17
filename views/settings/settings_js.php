	$('input#use_ootp_details').click(function() {
		$('#ootp_block').toggle(!this.checked);
	});
	$('#ootp_block').toggle(!$('input#use_ootp_details').is(':checked'));