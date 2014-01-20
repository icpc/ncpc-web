<?
    include_once('defs.php');
    
    function writeText() {
        global $COUNTRIES, $SCHOOLINFO, $FIELD_NAMES;
        $fp = fopen('registrations.txt', 'w');
        foreach ($COUNTRIES as $k => $v) {
	    fwrite($fp, "COUNTRY:$k\t$v\n");
        }
        foreach ($SCHOOLINFO as $k => $v) {
	    fwrite($fp,"SCHOOL:$k\t$v['name']\t$v['country']\t$v['email']");
            if (isset($v['nosite'])) {
              fwrite($fp, "\tsite=false\n");
            }
	    else {
              fwrite($fp, "\tsite=true\n");
	    }
        }
        $teams = dbquery('SELECT * FROM TEAMS');
        foreach ($teams as $t) {
            fwrite($fp, "TEAM:");
            foreach ($FIELD_NAMES as $f) {
                $v = $t[$f];
                fwrite($fp, "\t$v");
            }
            fwrite($fp, "\n");
        }
        fclose($fp);
    }
?>
