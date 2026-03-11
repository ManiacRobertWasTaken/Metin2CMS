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
			<form action="<?php print $site_url; ?>" method="post" style="display:inline" onsubmit="return confirm('<?php print $lang['sure?']; ?>');"><?php echo csrfField(); ?><input type="hidden" name="delete_article" value="<?php print intval($read_id); ?>"><button type="submit" style="background:none;border:none;cursor:pointer;padding:0;"><i style="color:red;" class="fa fa-trash-o fa-2" aria-hidden="true"></i></button></form>
			<?php
					}
			?>
			</h2>
			<p class="blog-attribution"><?php print htmlspecialchars($article['time'], ENT_QUOTES, 'UTF-8'); ?></p>
			<div class="text">
				<div>
					<div class="copy">
						<?php print sanitizeHtml($article['content']); ?>
					</div>
				</div>
			</div>
			
        </ul>
    </div>
</div>