<?php
require_once("include/classes/GetClientIp.php");
$vote4coins = file_get_contents('include/db/vote4coins.json');
$vote4coins = json_decode($vote4coins, true);
$siteKey = isset($_GET['site']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['site']) : '';
if($siteKey && isset($vote4coins[$siteKey]) && isset($vote4coins[$siteKey]['link']) && isset($vote4coins[$siteKey]['value']) && isset($vote4coins[$siteKey]['type']) && isset($vote4coins[$siteKey]['time']))
{
    $voted_now  = false;
	$getClientIp = new GetClientIp;
	$account_ip = $getClientIp->getClientIp();
	$session_verified = true;
	$cookieName = 'vote_'.$siteKey;

	if(isset($_COOKIE[$cookieName]))
	{
		$session_verified = $voted_now = false;
		
        $time_needed = strtotime('-' . $vote4coins[$siteKey]['time'] . ' hours');
		$date_diff = date_diff(date_create(date('Y-m-d G:i', $_COOKIE[$cookieName])), date_create(date('Y-m-d G:i', $time_needed)));
		
		$already_voted = getTimeUntilNextVote($date_diff);
	}
	
    if($session_verified && filter_var($account_ip, FILTER_VALIDATE_IP) !== false) {
        if(!check_vote4coins_by_account($siteKey)) {			
			$can_vote = false;
			
            $date_voted = check_date_vote4coins_ip($siteKey, $account_ip);
			
            $time_needed = strtotime('-' . $vote4coins[$siteKey]['time'] . ' hours');
			
            if($time_needed > strtotime($date_voted))
                $can_vote = true;
            else {
                $date_diff = date_diff(date_create($date_voted), date_create(date('Y-m-d G:i', $time_needed)));
				$already_voted = getTimeUntilNextVote($date_diff);
			}
			
			if($can_vote)
			{
				setcookie($cookieName, time(), ['expires' => time()+(3600*$vote4coins[$siteKey]['time']), 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
				
				insert_vote4coins($siteKey, $account_ip);
				
				if($vote4coins[$siteKey]['type'] == 1)
					addCoins($_SESSION['id'], $vote4coins[$siteKey]['value']);
				else
					addjCoins($_SESSION['id'], $vote4coins[$siteKey]['value']);
				
				$voted_now = true;
			}
        } else {
            $can_vote = false;
            $date_voted = check_date_vote4coins($siteKey, $account_ip);
			
            $time_needed = strtotime('-' . $vote4coins[$siteKey]['time'] . ' hours');
			
            if($time_needed > strtotime($date_voted))
                $can_vote = true;
            else
                $date_diff = date_diff(date_create($date_voted), date_create(date('Y-m-d G:i', $time_needed)));
			
            if(!$can_vote)
				$already_voted = getTimeUntilNextVote($date_diff);
            else {
				setcookie($cookieName, time(), ['expires' => time()+(3600*$vote4coins[$siteKey]['time']), 'path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
				
                updateVote4Coins($siteKey, $account_ip);
                if($vote4coins[$siteKey]['type'] == 1)
                    addCoins($_SESSION['id'], $vote4coins[$siteKey]['value']);
                else
                    addjCoins($_SESSION['id'], $vote4coins[$siteKey]['value']);
                $voted_now = true;
            }
        }
    }
    if($voted_now) {
        $redir = $vote4coins[$siteKey]['link'];
        if($redir && filter_var($redir, FILTER_VALIDATE_URL) && preg_match('/^https?:\/\//i', $redir)) {
            header("Location: " . $redir);
        } else {
            header("Location: " . $site_url);
        }
        die();
    }
}
?>