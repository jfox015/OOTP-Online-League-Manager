			$('input#sim_details').click(function() {
                $('#ootp_block').toggle(!this.checked);
            });
            $('#ootp_block').toggle(!$('input#sim_details').is(':checked'));
			
			$("#next_sim").datepicker();
			$("#league_date").datepicker();
			$("#league_file_date").datepicker();