		
		<div class="aside">
			<?php
			if ($logged_in) : ?>
			<div class="profile">
                <h3>Welcome <?php echo $username.$user_name ?></h3>
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