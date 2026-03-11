<?php
	$jsondataDonate = file_get_contents('include/db/donate.json');
	$jsondataDonate = json_decode($jsondataDonate, true);
	
	$jsondataCurrency = file_get_contents('include/db/currency.json');
	$jsondataCurrency = json_decode($jsondataCurrency,true);
	
	if(!$jsondataDonate)
		$jsondataDonate = array();
	
	if(isset($_POST['submit']))
	{
		csrfCheck();
		$new_link = array();
		$new_link['name'] = strip_tags($_POST['donation_method']);
		$new_link['list'] = array();
		
		array_push($jsondataDonate, $new_link);
		
		$json_new = json_encode($jsondataDonate);
		file_put_contents('include/db/donate.json', $json_new);
		
		header("Location: ".$site_url.'admin/donate');
		die();
	} else if(isset($_POST['submit_delete_method']) && is_numeric($_POST['del']))
	{
		csrfCheck();
		unset($jsondataDonate[intval($_POST['del'])]);

		$json_new = json_encode($jsondataDonate);
		file_put_contents('include/db/donate.json', $json_new);

		header("Location: ".$site_url.'admin/donate');
		die();
	}  else if(isset($_POST['submit_delete_price']))
	{
		csrfCheck();
		$dId = intval($_POST['id']);
		$pId = intval($_POST['price_id']);
		if(isset($jsondataDonate[$dId]['list'][$pId]))
			unset($jsondataDonate[$dId]['list'][$pId]);
		
		$json_new = json_encode($jsondataDonate);
		file_put_contents('include/db/donate.json', $json_new);
		
		header("Location: ".$site_url.'admin/donate');
		die();
	} else if(isset($_POST['submit_price']))
	{
		csrfCheck();
		$new_price = array();
		$new_price['price'] = intval($_POST['price']);
		$new_price['md'] = intval($_POST['md']);
		$new_price['currency'] = intval($_POST['currency']);

		if(isset($jsondataDonate[intval($_POST['id'])]))
			array_push($jsondataDonate[intval($_POST['id'])]['list'], $new_price);
		
		$json_new = json_encode($jsondataDonate);
		file_put_contents('include/db/donate.json', $json_new);
		
		header("Location: ".$site_url.'admin/donate');
		die();
	}
?>