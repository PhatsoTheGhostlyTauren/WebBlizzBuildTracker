<?php	
	class Tracker {
		
		private $blizz_data_url;
		private $versions_data,$cdns_data,$build_data;
		private $version_hash, $cdn_path, $build_hash;
		private $errlog;
		private $tracker_code;
		private $data_path;
		private $history;
		
		public function __construct($_Code,$_DataPath){		
			$this->blizz_data_url = "http://us.patch.battle.net:1119/".$_Code."";
			$this->tracker_code = $_Code;
			$this->data_path = $_DataPath;
			$this->versions_data = $this->BlizzDataDecode($this->getBlizzData("versions"));
			$this->cdns_data = $this->BlizzDataDecode($this->getBlizzData("cdns"));
			$this->loadHashes();
			$this->build_data = $this->getBuildData($this->cdns_data,$this->versions_data);
		}
		
		private function getBlizzData($_spec){
			try {
				return file_get_contents($this->blizz_data_url."/".$_spec);
			} catch (Exception $e) {
				echo "Error loading Version-Data for Tracker '".$this->tracker_code."' : ".$e->getMessage()."\n";
			}		
		}
		
		private function BlizzDataDecode($_contents){
			$result = array();
			$d = explode("\n",trim($_contents,"\n"));
			$lcount = 1;			
			$htemp = array_shift($d);
			$headers = explode("|",$htemp);
			foreach($d as $row){
				$colCount = 0;
				$r = explode("|",$row);
				foreach($headers as $h){
					$tempstr = split("!",$h);
					$temparray[$tempstr[0]] = $r[$colCount];
					$colCount++;				
				}			
				array_push($result,$temparray);				
			}
			return $result;
		}
		
		private function prepareBlizzHash($_hash){			
			return $_hash[0].$_hash[1]."/".$_hash[2].$_hash[3]."/".$_hash;			
		}
		
		private function loadHashes(){
			$this->version_hash = $this->prioritySearch($this->versions_data,array("us","xx","eu","kr","tw","cn","sg"),"Region","BuildConfig");
			$this->cdn_path = $this->prioritySearch($this->cdns_data,array("us","xx","eu","kr","tw","cn","sg"),"Name","Path");
		}
		
		private function prioritySearch($_arr, $_keyarr, $_keyname,$_val){
			foreach($_keyarr as $key){
				foreach($_arr as $entry){
					if($entry[$_keyname] == $key){
						return $entry[$_val];
					}					
				}			
			}	
			return false;
		}
		

		
		
		
		private function getBuildData(){
			$turl = "http://dist.blizzard.com.edgesuite.net/".$this->cdn_path."/config/".$this->prepareBlizzHash($this->version_hash);
			echo $turl;
		}
		
		
	}
	
?>