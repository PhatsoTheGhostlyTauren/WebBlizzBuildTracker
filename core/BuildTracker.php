<?php
	class BuildTracker
	{
		public $Data_Path,$Core_Path;
		private $LegitCodes = ["agent","bnt","d3","d3cn","d3t","demo","hero","herot","hsb","pro","prodev","sc2","s2","s2t","s2b","test","storm","war3","wow","wowt","wow_beta"];
		public $Trackers = array();
		public $errlog;
	
		public function __construct($_Codes){
			$this->Data_Path = '/Data/';
			$this->Core_Path = '/Core/';
			$this->generateTrackers($_Codes);
		}
		
		private function generateTrackers($_codes){
			foreach($_codes as $c){
				if($this->isLegitCode($c)){
					if(!array_key_exists($c,$this->Trackers)){
						$this->Trackers[$c] = new Tracker($c,$this->Data_Path);
					} else {
						$this->errlog .= "<p>Error loading Tracker '".$c."' : Tracker already generated.</p>\n";
					}
				} else {
					$this->errlog .= "<p>Error loading Tracker '".$c."' : Not a legitimate Game-Code.</p>\n";
				}
			}			
		}		
		
		private function isLegitCode($_code){
			if(in_array($_code, $this->LegitCodes)){
				return true;
			}	else {
				return false;
			}
		}		
	}	
?>