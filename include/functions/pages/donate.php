<?php
	$jsondataDonate = file_get_contents('include/db/donate.json');
	$jsondataDonate = json_decode($jsondataDonate, true);
	
	$jsondataCurrency = file_get_contents('include/db/currency.json');
	$jsondataCurrency = json_decode($jsondataCurrency,true);
	
	if(isset($_POST["method"]) && strtolower($_POST["method"])=='paypal' && isset($_POST['id']) && isset($_POST['type']))
	{
		csrfCheck();
		if(!isset($jsondataDonate[$_POST['id']]) || !isset($jsondataDonate[$_POST['id']]['list'][$_POST['type']]))
		{
			header("Location: ".$site_url);
			die();
		}

		$return_url = $site_url."index.php";
		$cancel_url = $site_url."index.php";
		$notify_url = $site_url."paypal.php";

		$querystring = '';
		$querystring .= "?business=".urlencode($paypal_email)."&";

		$price = $jsondataDonate[$_POST['id']]['list'][$_POST['type']];
		
		$querystring .= "item_name=".urlencode($jsondataDonate[$_POST['id']]['name'].' ['.$price['price'].' - '.$price['md'].' MD]')."&";
		$querystring .= "amount=".urlencode($price['price'])."&";
		
		$querystring .= "cmd=".urlencode("_xclick")."&";
		$querystring .= "no_note=".urlencode("1")."&";
		$querystring .= "currency_code=".urlencode($price['currency'])."&";
		$querystring .= "bn=".urlencode("PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest")."&";
		$querystring .= "first_name=".urlencode(getAccountName($_SESSION['id']))."&";
		
		$querystring .= "return=".urlencode($return_url)."&";
		$querystring .= "cancel_return=".urlencode($cancel_url)."&";
		$querystring .= "notify_url=".urlencode($notify_url)."&";
		$querystring .= "item_number=".urlencode($jsondataDonate[$_POST['id']]['name'].' ['.$price['price'].' - '.$price['md'].' MD]')."&";
		$querystring .= "custom=".urlencode($_SESSION['id']);
		
		//redirect('https://www.sandbox.paypal.com/cgi-bin/webscr'.$querystring);
		$url = 'https://www.paypal.com/cgi-bin/webscr'.$querystring;
		if(!headers_sent()) {
			header('Location: '.$url);
			exit;
		} else {
			$safeUrl = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
			echo '<script type="text/javascript">';
			echo 'window.location.href="'.$safeUrl.'";';
			echo '</script>';
			echo '<noscript>';
			echo '<meta http-equiv="refresh" content="0;url='.$safeUrl.'" />';
			echo '</noscript>';
			exit;
		}

		exit();
	}
?>