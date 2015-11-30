# BlizzBuildTracker

Generates JSON-Files tracking builds of virtually any Blizzard Download Product.


EDIT generate.php to instruct the BuildTracker what gamecodes it should check.

Working Codes: "agent","bnt","d3","d3cn","d3t","demo","hero","herot","hsb","pro",
"prodev","sc2","s2","s2t","s2b","test","storm","war3","wow","wowt","wow_beta"

The Software generates JSON files with buildids, names and buildconfig pulled directly from Blizzards CDN;


Best used with a Cronjob set up to open generate.php every 5 Minutes. the frontend is up to your needs. it saves valid JSON files 
in your Data Folder and logs every new build in a list.
