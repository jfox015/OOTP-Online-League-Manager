<h3>Recent Tweets</h3>
    <p><b><?php echo $username; ?></b></p>
<ul class="rss">
    <?php foreach($tweets as $tweet): ?>
	<li>
	    <?php echo $tweet->text; ?>
	    <p class="date"><em><?php echo anchor('https://twitter.com/' . $username . '/status/' . $tweet->id, date('m/d/Y h:i:s A',strtotime($tweet->created_at)), 'target="_blank"'); ?></em></p>
	</li>
    <?php endforeach; ?>
</ul>