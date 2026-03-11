<?php
	if(isset($_POST['uninstall']))
	{
		csrfCheck();
		$dir = basename($_POST['uninstall']);
		if($dir && is_dir($dir) && strpos(realpath($dir), realpath('.')) === 0)
			rmdir($dir);
		print '<div class="alert alert-success alert-dismissible fade show" role="alert">
			 <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>'.$lang['uninstall-info'].'</div>';
	} else if(isset($_POST['install']))
	{
		csrfCheck();
		$installName = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['install']);
?>
		<center><img src="<?php print $site_url; ?>images/site/updating.gif"></center></br>
<?php
		$file = 'update.zip';

		$download = file_get_contents_curl('https://new.metin2cms.cf/v2/modules/'.$installName.'.zip', 2, 10);
		file_put_contents($file, $download);

		if(file_exists($file)) {
			$tryUpdate = ZipExtractUpdate();
			if($tryUpdate[0])
				print "<script>top.location='".$site_url."admin/modules'</script>";
			else
			{
				if(isset($tryUpdate[1]))
					print $tryUpdate[1];
			}
		}
	}
		
?>
<div class="row">
	<?php
		$modules_list = getModulesList(); 
		foreach($modules_list as $mod)
		{
	?>
    <div class="col-sm-6">
		<div class="card">
			<img class="card-img-top" src="<?php print e($mod['img']); ?>">
			<div class="card-body">
				<h4 class="card-title"><?php print e($mod['name']); ?></h4>
				<p class="card-text"><?php print e($mod['description']); ?><?php if(is_dir($mod['directory'])) { ?></br><a href="<?php print $site_url.$mod['directory']; ?>"><?php print $site_url.$mod['directory']; ?></a><?php } ?></p>
				<?php if(is_dir($mod['directory'])) print '<form method="POST" action="">'.csrfField().'<input type="hidden" value="'.$mod['directory'].'" name="uninstall"><button type="submit" class="btn btn-danger">'.$lang['uninstall'].'</button></form>';
						else print '<form method="POST" action="">'.csrfField().'<input type="hidden" value="'.$mod['directory'].'" name="install"><button type="submit" class="btn btn-success">'.$lang['install'].'</button></form>'; ?>
			</div>
		</div>
    </div>
	<?php }
	if(!count($modules_list))
		print '<div class="alert alert-info alert-dismissible fade show" role="alert">
			 <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>'.$lang['no-modules'].'</div>';
	?>
</div>