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
			<?php endif;?>
			
			<?php Template::block('sim_details','league_manager/sim_details'); ?>
			
			<?php Template::block('home_news_list','league_manager/empty'); ?>
			
			<?php Template::block('twitter_feed','league_manager/empty'); ?>
		</div>
		
		<div class="main">

			<?php Template::block('home_news_block','league_manager/empty'); ?>

		</div>	<!-- /main -->