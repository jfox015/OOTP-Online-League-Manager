			$('input#sim_details').click(function() {
                $('#ootp_block').toggle(!this.checked);
            });
            $('#ootp_block').toggle(!$('input#sim_details').is(':checked'));
			
			$("#next_sim, #league_date, #league_file_date").datepicker();