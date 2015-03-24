#!/usr/bin/php
<?php
        ob_implicit_flush(true);
        set_time_limit(0);

        $cf = fopen("/var/spool/asterisk/outgoing/cb".$argv[1],"w+"); fputs($cf,"Channel: Local/1000@from-alarms\n");
        fputs($cf,"SetVar: DIDNUM=9522306342\n");
        fputs($cf,"CallerID: Alarm <911>\n");
        fputs($cf,"MaxRetries: 2\n");
        fputs($cf,"RetryTime: 10\n");
	fputs($cf,"Context: from-alarms\n");
	fputs($cf,"Extension: 1001\n");
        fclose($cf);
?>
