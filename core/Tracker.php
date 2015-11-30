<?php	
	class Tracker {
		
		private $blizz_data_url;
		private $versions_data,$cdns_data,$build_config;
		private $version_hash, $cdn_path, $build_hash;
		private $errlog;
		private $tracker_code;
		private $data_path;
		private $region_prio;
		private $history;
		
		public function __construct($_Code,$_DataPath){		
			$this->blizz_data_url = "http://us.patch.battle.net:1119/".$_Code."";
			$this->region_prio = array("us","xx","eu","kr","tw","cn","sg");
			$this->tracker_code = $_Code;
			$this->data_path = $_DataPath;
			$this->versions_data = $this->BlizzDataDecode($this->getBlizzData($this->blizz_data_url."/versions"));
			$this->cdns_data = $this->BlizzDataDecode($this->getBlizzData($this->blizz_data_url."/cdns"));
			$this->loadHashes();
			$this->build_config = $this->getBuildConfig($this->cdn_path,$this->version_hash);
			$this->history = $this->generateHistory();
		}
		
		private function getBlizzData($_url){
			try {
				return file_get_contents($_url);
			} catch (Exception $e) {
				$this->errlog .= "<br/>Error loading Data from URL:'".$_url."' Tracker '".$this->tracker_code."' : ".$e->getMessage()." <br/> \n";
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
			$this->version_hash = $this->prioritySearch($this->versions_data,$this->region_prio,"Region","BuildConfig");
			$this->cdn_path = $this->prioritySearch($this->cdns_data,$this->region_prio,"Name","Path");
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
		
		
		private function getBuildConfig($_cdnpath, $_BuildHash){
			$turl = "http://dist.blizzard.com.edgesuite.net/".$_cdnpath."/config/".$this->prepareBlizzHash($_BuildHash);
			$buildconfigraw = $this->getBlizzData($turl);
			$buildconfigarr = explode("\n",trim($buildconfigraw,"\n"));
			array_shift($buildconfigarr); //First Two Lines obsolete
			array_shift($buildconfigarr);
			foreach($buildconfigarr as $row){ //Convert into Associative Array 
				$arguments = explode("=",$row);
				$key = str_split($arguments[0]);
				$value = str_split($arguments[1]);
				array_pop($key);
				array_shift($value);
				$key = implode($key);
				$value = implode($value);
				$result[$key] = $value;
			}
			return $result;			
		}
		
		private function generateHistory(){
			$filename = $this->data_path.$this->tracker_code.".json";
			$history = array();
			try { //Load JSON Data from File; Create File if not yet created
				if(file_exists($filename)){
					$history = json_decode(file_get_contents($filename),true);
				} else {
					$file = fopen($filename, 'w') or die($this->errlog .= "<br/>Error creating JSON-Datafile:'".$filename." for' Tracker '".$this->tracker_code."' : ".$e->getMessage()." <br/> \n");
					fclose($file);
				}
			} catch (Exception $e) {
				$this->errlog .= "<br/>Error loading JSON-Data from file:'".$filename."' for Tracker '".$this->tracker_code."' : ".$e->getMessage()." <br/> \n";
			}
			$currentbuildid = $this->prioritySearch($this->versions_data,$this->region_prio,"Region","BuildId"); 
			$current["date"] = time(); 
			$current["build-hash"] = $this->build_hash;
			$current["cdn-hash"] = $this->prioritySearch($this->versions_data,$this->region_prio,"Region","CDNConfig"); 
			$current["version-name"] = $this->prioritySearch($this->versions_data,$this->region_prio,"Region","VersionsName"); 
			$current["build_config"] = $this->build_config;
			if(!isset($history[$currentbuildid])){
				$history[$currentbuildid] = $current;
				try { //Load JSON Data from File; Create File if not yet created
					file_put_contents($filename, json_encode($history));
				} catch (Exception $e) {
					$this->errlog .= "<br/>Error storing JSON-Data in file:'".$filename."' for Tracker '".$this->tracker_code."' : ".$e->getMessage()." <br/> \n";
				}
			}
			return $history;
		}
		
		
	}
	
?>