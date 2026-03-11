<div class="container">
    <form action="" method="post">
		<?php echo csrfField(); ?>
		<div class="form-group row">
			<div class="col-sm-4">
				<input type="text" class="form-control" name="download_server" placeholder="Server">
			</div>
			<div class="col-sm-8">
				<input type="url" class="form-control" name="download_link" placeholder="Link" value="https://">
			</div>
		</div>

        <div class="form-group row">
            <div class="col-sm-10">
                <button type="submit" name="submit" class="btn btn-primary"><?php print $lang['add']; ?></button>
            </div>
        </div>
    </form>
	
	<?php if(count($jsondataDownload)) { ?>
	<table class="table table-hover">
		<thead class="table-dark">
			<tr>
				<th style="width: 15%">#</th>
				<th style="width: 70%">Server</th>
				<th><?php print $lang['delete']; ?></th>
			</tr>
		</thead>
		<tbody>
		<?php $i=1; foreach($jsondataDownload as $key => $download) { ?>
			<tr>
				<th scope="row"><?php print $i++; ?></th>
				<td><?php print e($download['name']); ?></td>
				<td><form action="" method="post" style="display:inline"><?php echo csrfField(); ?><input type="hidden" name="del" value="<?php print intval($key); ?>"><button type="submit" name="submit_delete" class="btn btn-primary btn-sm"><?php print $lang['delete']; ?></button></form></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<?php } ?>
</div>