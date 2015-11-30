<?php
	require_once("core/classLoader.php");	
	require_once("core/BuildTracker.php");	
	
	$BT = new BuildTracker(array("d3"));

	echo $BT->errlog;
	echo "<pre>";
	print_r($BT->Trackers["d3"]);
	echo "</pre>";
	
	//echo "'wow' is a legit code: ".$BT->isLegitCode("wow")."</br>\n";
	//echo "'whorecraft' is a legit code: ".$BT->isLegitCode("whorecraft")."</br>\n";

?>