		<!-- Header -->
		<div class="head text-left">
			<h1><?php
                if (isset($league_name) && !empty($league_name)) {
                    echo $league_name;
                } else if (isset($settings['site.title']) && !empty($settings['site.title'])) {
                    echo $settings['site.title'];
                } else {
                    echo config_item('site.title');
                } ?></h1>
            <div class="title"><?php echo config_item('site.title'); ?></div>
		</div>
		
		<div class="nav">
				<?php 
				$attributes['id'] = 'nav';
				$attributes['class'] = 'dropdown dropdown-horizontal';

				echo show_navigation('hn', FALSE, $attributes); ?>
		</div>
			
		<div class="aside">
			<?php
				// acessing our userdata cookie
				$cookie = unserialize($this->input->cookie($this->config->item('sess_cookie_name')));
				$logged_in = isset ($cookie['logged_in']);
				unset ($cookie);

			if ($logged_in) : ?>
			<div class="profile">
                <h3>Welcome Jeff Fox (jfox015)</h3>
				<ul>
					<li><a href="<?php echo site_url('admin');?>">Admin Dashboard</a></li>
					<br />
                </ul>
				<a href="<?php echo site_url('users/profile');?>">Edit Profile</a> |
				<a href="<?php echo site_url('logout');?>">Logout</a>
				</ul>
			</div>
            <?php else: ?>
                <h3>Login<h3>

                <?php echo form_open('login'); ?>

                <label for="login_value"><?php echo $this->settings_lib->item('auth.login_type') == 'both' ? 'Username/Email' : ucwords($this->settings_lib->item('auth.login_type')) ?></label>
                <input type="text" name="login" id="login_value" value="<?php echo set_value('login'); ?>" tabindex="1" placeholder="<?php echo $this->settings_lib->item('auth.login_type') == 'both' ? lang('bf_username') .'/'. lang('bf_email') : ucwords($this->settings_lib->item('auth.login_type')) ?>" />

                <label for="password"><?php echo lang('bf_password'); ?></label>
                <input type="password" name="password" id="password" value="" tabindex="2" placeholder="<?php echo lang('bf_password'); ?>" />

                <?php if ($this->settings_lib->item('auth.allow_remember')) : ?>
                    <div class="small indent">
                        <input type="checkbox" name="remember_me" id="remember_me" value="1" tabindex="3" />
                        <label for="remember_me" class="remember"><?php echo lang('us_remember_note'); ?></label>
                    </div>
                    <?php endif; ?>

                <div class="submits">
                    <input type="submit" name="submit" id="submit" value="Let Me In" tabindex="5" />
                </div>

                <?php echo form_close(); ?>
			<?php endif;?>

            <?php echo($sim_details); ?>

            <div class="clearfix break"></div>

            <?php echo($home_news_list); ?>

            <div class="clearfix break"></div>

            <?php echo($tweets); ?>
		</div>
		
		<div class="main">

            <?php echo($home_news_block); ?>

		</div>	<!-- /main -->