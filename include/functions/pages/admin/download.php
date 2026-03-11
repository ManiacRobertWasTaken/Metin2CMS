<?php
	$jsondataDownload = file_get_contents('include/db/download.json');
	$jsondataDownload = json_decode($jsondataDownload, true);
	
	if(!$jsondataDownload)
		$jsondataDownload = array();
	
	if(isset($_POST['submit']))
	{
		csrfCheck();
		$new_link = array();
		$new_link['name'] = strip_tags($_POST['download_server']);
		$rawLink = $_POST['download_link'];
		$new_link['link'] = (filter_var($rawLink, FILTER_VALIDATE_URL) && preg_match('/^https?:\/\//i', $rawLink)) ? $rawLink : '';
		
		array_push($jsondataDownload, $new_link);
		
		$json_new = json_encode($jsondataDownload);
		file_put_contents('include/db/download.json', $json_new);
		
		header("Location: ".$site_url.'admin/download');
		die();
	} else if(isset($_POST['submit_delete']))
	{
		csrfCheck();
		$delKey = $_POST['del'];
		if(isset($jsondataDownload[$delKey]))
		{
			unset($jsondataDownload[$delKey]);

			$json_new = json_encode($jsondataDownload);
			file_put_contents('include/db/download.json', $json_new);
		}

		header("Location: ".$site_url.'admin/download');
		die();
	}
?>