<?php
	require_once("core/BuildTracker.php");	
	require_once("core/Tracker.php");	
	
	$BT = new BuildTracker(array("wow_beta","wow","wowt"));
	
	echo "<pre>";
	print_r($BT->Trackers["wow_beta"]);
	echo "</pre>";
?>