		<!-- Header -->
		<div class="head text-left">
			<h1><?php echo config_item('site.title'); ?></h1>
		</div>
			
		<div class="aside">
			<?php
				// acessing our userdata cookie
				$cookie = unserialize($this->input->cookie($this->config->item('sess_cookie_name')));
				$logged_in = isset ($cookie['logged_in']);
				unset ($cookie);

			if ($logged_in) : ?>
			<div class="profile">
				Jeff Fox (jfox015)
				<ul>
					<li>Admin Dashboard</li>
					<br />
					<li><a href="<?php echo site_url('users/profile');?>">Edit Profile</a></li>
					<li><a href="<?php echo site_url('logout');?>">Logout</a></li>
				</ul>
			</div>
            <?php else: ?>

			<?php endif;?>

            <div class="clearfix break"></div>

            <?php echo($sim_details); ?>

            <div class="clearfix break"></div>

            <?php echo($home_news_list); ?>

            <div class="clearfix break"></div>

            <?php echo($tweets); ?>
		</div>
		
		<div class="main">

            <?php echo($home_news_block); ?>

		</div>	<!-- /main -->