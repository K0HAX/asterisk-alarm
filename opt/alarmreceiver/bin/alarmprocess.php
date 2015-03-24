#!/usr/bin/php
<?php
	require("config.php");
	require('/usr/share/php/Services/Twilio.php');

	$con=mysqli_connect($host, $user, $pass, $db);

	if(mysqli_connect_errno())
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$query = mysqli_query($con,"SELECT events.acct,events.event,events.q,contactid.description,contactid.datatype,events.gg,events.zone,events.dbupdate,events.uniqueid,accounts.sms_number
FROM events 
LEFT JOIN contactid ON events.`event`=contactid.id 
LEFT JOIN accounts ON events.`acct`=accounts.id
WHERE events.uniqueid LIKE \"" . $argv[1] . "\";");
	$result = mysqli_fetch_assoc($query);
	
	$message = $result['description'] . " " . $result['datatype'] . " " . $result['zone'];
	//sendMessage($message, $result['sms_number']);
	mysqli_close($con);	
	//print_r($result);

	if(strcmp($result['description'], "Burglary") == 0)
	{
		System("/opt/alarmreceiver/bin/alertOwner.php " . $argv[1]);
	}

	if($result['event'] == 401)
	{
		switch($result['q']) {
			case 1:
				$message = "Alarm Disarmed User [" . $result['zone'] . "]";
				break;
			case 3:
				$message = "Alarm Armed User [" . $result['zone'] . "]";
				break;
		}
	}
	
	sendMessage($message, $result['sms_number']);

	function sendMessage($arg_1, $arg_2)
	{
		// Twilio Account Information
		$AccountSid = "";
		$AuthToken  = "";
		$client = new Services_Twilio($AccountSid, $AuthToken);
		$message = $client->account->messages->create(array(
			"From" => "952-555-0123",
			"To" => $arg_2,
			"Body" => "[ALARM] " . $arg_1,
		));
	}

?>
	
