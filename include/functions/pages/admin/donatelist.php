<?php
	if(isset($_POST['yes']))
	{
		csrfCheck();
		updateDonateStatus(intval($_POST['id']), 1);
		addCoins(intval($_POST['account']), intval($_POST['md']));
	} else if(isset($_POST['no']))
	{
		csrfCheck();
		updateDonateStatus(intval($_POST['id']), 2);
	}
	
	$jsondataDonate = get_donations();
?>