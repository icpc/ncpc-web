<?
    include_once('defs.php');
    
    function writeXML() {
        global $COUNTRIES, $SCHOOLINFO, $FIELD_NAMES;
        $fp = fopen('registrations.xml', 'w');
        fwrite($fp, '<?xml version="1.0" encoding="ISO-8859-1"?>'."\n");
        fwrite($fp, "<data>\n");
        foreach ($COUNTRIES as $k => $v) {
            fwrite($fp, "    <country>\n");
            fwrite($fp, "        <id>$k</id>\n");
            fwrite($fp, "        <name>$v</name>\n");
            fwrite($fp, "    </country>\n");
        }
        foreach ($SCHOOLINFO as $k => $v) {
            fwrite($fp, "    <school>\n");
            fwrite($fp, "        <id>$k</id>\n");
            fwrite($fp, "        <name>{$v['name']}</name>\n");
            fwrite($fp, "        <country>{$v['country']}</country>\n");
            fwrite($fp, "        <email>{$v['email']}</email>\n");
            if (isset($v['nosite'])) {
            fwrite($fp, "        <nosite/>\n");
            }
            fwrite($fp, "    </school>\n");
        }
        $teams = dbquery('SELECT * FROM TEAMS');
        foreach ($teams as $t) {
            fwrite($fp, "    <team>\n");
            foreach ($FIELD_NAMES as $f) {
                $v = $t[$f];
                $v = str_replace('<', '&lt;', $v);
                $v = str_replace('>', '&gt;', $v);
                //$v = str_replace('&', '&amp;', $v);
                fwrite($fp, "        <$f>$v</$f>\n");
            }
            fwrite($fp, "    </team>\n");
        }
        fwrite($fp, "</data>\n");
        fclose($fp);
    }
?>
