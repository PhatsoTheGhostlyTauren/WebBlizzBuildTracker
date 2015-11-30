<?php
	/*		MIT License
			-----------

			Copyright (c) 2015 Phatso, the ghostly Tauren (https://github.com/PhatsoTheGhostlyTauren/)
			Permission is hereby granted, free of charge, to any person
			obtaining a copy of this software and associated documentation
			files (the "Software"), to deal in the Software without
			restriction, including without limitation the rights to use,
			copy, modify, merge, publish, distribute, sublicense, and/or sell
			copies of the Software, and to permit persons to whom the
			Software is furnished to do so, subject to the following
			conditions:

			The above copyright notice and this permission notice shall be
			included in all copies or substantial portions of the Software.

			THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
			EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
			OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
			NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
			HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
			WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
			FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
			OTHER DEALINGS IN THE SOFTWARE.
	*/

	class BuildTracker
	{
		public $Data_Path,$Core_Path;
		private $LegitCodes = ["agent","bnt","d3","d3cn","d3t","demo","hero","herot","hsb","pro","prodev","sc2","s2","s2t","s2b","test","storm","war3","wow","wowt","wow_beta"];
		public $Trackers = array();
		public $errlog;
	
		public function __construct($_Codes){
			$this->Data_Path = 'Data/';
			$this->Core_Path = 'Core/';
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