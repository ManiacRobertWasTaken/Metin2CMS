<?php
	$jsondataVote4Coins = file_get_contents('include/db/vote4coins.json');
	$jsondataVote4Coins = json_decode($jsondataVote4Coins, true);
	
	if(!$jsondataVote4Coins)
		$jsondataVote4Coins = array();
	
	if(isset($_POST['submit']))
	{
		csrfCheck();
		$new_link = array();
		$new_link['name'] = strip_tags($_POST['site_name']);
		$raw = $_POST['site_link'];
		$new_link['link'] = (filter_var($raw, FILTER_VALIDATE_URL) && preg_match('/^https?:\/\//i', $raw)) ? $raw : '';
		$new_link['type'] = intval($_POST['type']);
		$new_link['value'] = intval($_POST['coins']);
		$new_link['time'] = intval($_POST['time']);
		
		array_push($jsondataVote4Coins, $new_link);
		
		$json_new = json_encode($jsondataVote4Coins);
		file_put_contents('include/db/vote4coins.json', $json_new);
		
		header("Location: ".$site_url.'admin/vote4coins');
		die();
	} else if(isset($_POST['submit_delete']))
	{
		csrfCheck();
		$delKey = $_POST['del'];
		if(isset($jsondataVote4Coins[$delKey]))
		{
			unset($jsondataVote4Coins[$delKey]);

			$json_new = json_encode($jsondataVote4Coins);
			file_put_contents('include/db/vote4coins.json', $json_new);

			delete_vote4coins($delKey);
		}

		header("Location: ".$site_url.'admin/vote4coins');
		die();
	}
?>