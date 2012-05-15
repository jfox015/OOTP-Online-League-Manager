<ul class="nav nav-pills">
	<li <?php echo $this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/custom/league_manager/') ?>"><?php echo "Menu"; ?></a>
	</li>
	<li <?php echo $this->uri->segment(4) == 'load_sql' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/custom/league_manager/load_sql') ?>"><?php echo "Manage SQL Files"; ?></a>
	</li>
	<li <?php echo $this->uri->segment(4) == 'sim_details' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/custom/league_manager/sim_details') ?>" id="sim_details"><?php echo 'Set Sim Details'; ?></a>
	</li>
	<li <?php echo $this->uri->segment(4) == 'table_list' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/custom/league_manager/table_list') ?>" id="table_list"><?php echo "Manage Required Tables"; ?></a>
	</li>
	<li <?php echo $this->uri->segment(4) == 'about' ? 'class="active"' : '' ?>>
		<a href="<?php echo site_url(SITE_AREA .'/custom/league_manager/about') ?>" id="about"><?php echo "About"; ?></a>
	</li>
</ul>
