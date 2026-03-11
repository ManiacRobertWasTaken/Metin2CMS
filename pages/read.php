<div class="mt2cms2-c-l">
    <div class="page-hd" style="background-image: url(<?php print $site_url; ?>images/news.png)">
        <div class="bd-c">
            <h2 class="pre-social"><?php print $lang['news']; ?></h2>
			<?php if($offline) print '<small><strong><font color="red">'.$lang['server-offline'].'</font></strong></small>' ?>
        </div>
    </div>
	<?php	
		if(!$offline && $database->is_loggedin())
			if($web_admin>=$jsondataPrivileges['news'])
				include 'include/functions/edit-news.php';
	?>
    <div class="bd-c">
        <ul class='blogroll'>
			<h2 class="blog-title"><?php print htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>
			<?php
				if(!$offline && $database->is_loggedin())
					if($web_admin>=$jsondataPrivileges['news'])
					{
			?>
			<a href="<?php print $site_url; ?>?delete=<?php print $read_id; ?>" onclick="return confirm('<?php print $lang['sure?']; ?>');"><i style="color:red;" class="fa fa-trash-o fa-2" aria-hidden="true"></i></a>
			<?php
					}
			?>
			</h2>
			<p class="blog-attribution"><?php print htmlspecialchars($article['time'], ENT_QUOTES, 'UTF-8'); ?></p>
			<div class="text">
				<div>
					<div class="copy">
						<?php print $article['content']; ?>
					</div>
				</div>
			</div>
			
        </ul>
    </div>
</div>