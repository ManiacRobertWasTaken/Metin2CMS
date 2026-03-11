<?php
	ini_set('session.cookie_httponly', 1);
	ini_set('session.cookie_samesite', 'Lax');
	ini_set('session.use_strict_mode', 1);
	session_start();

	if(empty($_SESSION['csrf_token']))
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

	function csrfField() {
		return '<input type="hidden" name="csrf_token" value="'.$_SESSION['csrf_token'].'">';
	}

	function csrfCheck() {
		if(!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']))
		{
			http_response_code(403);
			die('Invalid request');
		}
	}

	header('Cache-control: private');
	header('X-Frame-Options: SAMEORIGIN');
	header('X-Content-Type-Options: nosniff');
	header('Referrer-Policy: strict-origin-when-cross-origin');
	header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
	header('X-XSS-Protection: 1; mode=block');
	header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://www.google.com/recaptcha/ https://www.gstatic.com/recaptcha/; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; frame-src https://www.google.com/recaptcha/ https://recaptcha.google.com/; object-src 'none'; base-uri 'self'");
	
	$current_page = isset($_GET['p']) ? $_GET['p'] : null;
	
	include 'config.php';
	
	if (substr($site_url, -1)!='/')
		$site_url.='/';
	
	$site_domain = parse_url($site_url, PHP_URL_HOST);
	
	include 'include/functions/version.php';
	
	include 'include/functions/language.php';
	
	require_once("include/classes/cache.php");
	require_once("include/classes/user.php");
	
	$jsondata = file_get_contents('include/db/settings.json');
	$jsondata = json_decode($jsondata,true);
	$jsondataRanking = file_get_contents('include/db/ranking.json');
	$jsondataRanking = json_decode($jsondataRanking,true);
	include 'include/functions/json.php';
	$site_title = getJsonSettings("title");
	$paypal_email = getJsonSettings("paypal");
	$forum=getJsonSettings("forum", "links");
	$support=getJsonSettings("support", "links");
	$item_shop=getJsonSettings("item-shop", "links");
	$top10backup_date=getJsonSettings("date", "top10backup");
	
	include 'include/functions/social-links.php';
	$social_links=getJsonSettings("", "social-links");
	$social_links=getSocialLinks();

	$offline = 0;
	
	$database = new USER($host, $user, $password);
	
	include 'include/functions/pages.php';
	
	$jsondataPrivileges['news']=9;
	
	if(!$offline)
	{
		include 'include/functions/basic.php';
		
		if($database->is_loggedin())
		{
			if(($_SESSION['fingerprint']!=hash('sha256', $_SERVER['HTTP_USER_AGENT'].'x'.$_SERVER['REMOTE_ADDR'])) || ($_SESSION['password']!=securityPassword(getAccountPassword($_SESSION['id']))) || !checkStatus($_SESSION['id']))
			{
				$database->doLogout();
				header("Location: ".$site_url);
				die();
			}
			$web_admin = web_admin_level();
		} else $web_admin = 0;

		if($web_admin)
		{
			$jsondataPrivileges = file_get_contents('include/db/privileges.json');
			$jsondataPrivileges = json_decode($jsondataPrivileges,true);
		}
		
		if($database->is_loggedin() && $web_admin>=$jsondataPrivileges['news'])
		{
			if(isset($_POST['delete_article']) && is_numeric($_POST['delete_article']))
			{
				csrfCheck();
				$paginate->delete_article(intval($_POST['delete_article']));
				header("Location: ".$site_url);
				die();
			}
		}
		
		$jsondataFunctions = file_get_contents('include/db/functions.json');
		$jsondataFunctions = json_decode($jsondataFunctions, true);
		
		$statistics = false;
		foreach($jsondataFunctions as $key => $status)
			if($key != 'active-registrations' && $key != 'players-debug' && $key != 'active-referrals' && $status)
			{
				$statistics = true;
				break;
			}
		
		if($current_page=="logout")
		{
			$database->doLogout();
			header("Location: ".$site_url);
			die();
		}
		
		include 'include/functions/functions.php';

		if($page=='admin')
		{
			$admin_page = isset($_GET['a']) ? $_GET['a'] : null;
			include 'include/functions/admin-pages.php';

			checkPrivileges($a_page, $web_admin);

			include 'include/functions/admin-functions.php';
		}
		
		include 'include/functions/top10backup.php';
	}
	else
	{
		$web_admin = 0;
		if($page!='news' && $page!='read')
		{
			header("Location: ".$site_url);
			die();
		}
		$offline_date=date_format(date_create($top10backup_date), 'd.m.Y');
		$offline_players=getJsonSettings("players", "top10backup");
		$offline_guilds=getJsonSettings("guilds", "top10backup");
	}

	if(isset($_GET['api']) && isset($_GET['key']) && $_GET['api']=='metin2cms')
	{
		$apidata = file_get_contents('include/db/api.json');
		$apidata = json_decode($apidata,true);
		
		if(hash_equals($apidata['key'], $_GET['key']))
			die('ok');
		else
			die();
	}