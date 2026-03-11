<?php
	if(isset($_POST['account']))
	{
		csrfCheck();
		if($_POST['account']==1)
			$account_id = getAccountIDbyName($_POST['name']);
		else
			$account_id = getAccountIDbyChar($_POST['name']);
		
		$added = 0;
		$coins = intval($_POST['coins']);
		if($account_id && $coins > 0 && $coins <= 999999999)
		{
			if($_POST['type']==1)
				addCoins($account_id, $coins);
			else
				addjCoins($account_id, $coins);
			$added = 1;
		} else $added = 2;
	}
?>