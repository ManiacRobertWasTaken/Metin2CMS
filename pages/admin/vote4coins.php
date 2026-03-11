<div class="container">
    <form action="" method="post">
		<?php echo csrfField(); ?>
		<div class="form-group row">
			<div class="col-sm-4">
				<input type="text" class="form-control" name="site_name" placeholder="Site">
			</div>
			<div class="col-sm-8">
				<input type="url" class="form-control" name="site_link" placeholder="Link" value="https://">
			</div>
		</div>
		<div class="form-group row">
			<div class="col-sm-4">
				<input type="number" class="form-control" name="coins" placeholder="0" value="0">
			</div>
			<div class="col-sm-4">
				<select class="form-control" name="type">
					<option value="1">MD</option>
					<option value="2">JD</option>
				</select>
			</div>
			<div class="col-sm-4">				
				<div class="input-group">
					<input class="form-control" name="time" min="1" required="" type="number">
					<span class="input-group-addon"><?php print $lang['hours']; ?></span>
				</div>
				
			</div>
		</div>

        <div class="form-group row">
            <div class="col-sm-10">
                <button type="submit" name="submit" class="btn btn-primary"><?php print $lang['add']; ?></button>
            </div>
        </div>
    </form>
	
	<?php if(count($jsondataVote4Coins)) { ?>
	<table class="table table-hover">
		<thead class="table-dark">
			<tr>
				<th>#</th>
				<th>Site</th>
				<th><?php print $lang['coin']; ?></th>
				<th><?php print $lang['value']; ?></th>
				<th><?php print $lang['time']; ?></th>
				<th><?php print $lang['delete']; ?></th>
			</tr>
		</thead>
		<tbody>
		<?php $i=1; foreach($jsondataVote4Coins as $key => $vote4coins) { ?>
			<tr>
				<th scope="row"><?php print $i++; ?></th>
				<td><?php print e($vote4coins['name']); ?></td>
				<td><?php if($vote4coins['type']==1) print 'MD'; else print 'JD'; ?></td>
				<td><?php print e($vote4coins['value']); ?></td>
				<td><?php print e($vote4coins['time']).' '.$lang['hours']; ?></td>
				<td><form action="" method="post" style="display:inline"><?php echo csrfField(); ?><input type="hidden" name="del" value="<?php print intval($key); ?>"><button type="submit" name="submit_delete" class="btn btn-primary btn-sm"><?php print $lang['delete']; ?></button></form></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<?php } ?>
</div>