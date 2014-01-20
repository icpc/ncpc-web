<?
    include('defs.php');

//<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
//        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
//<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">
?>
<html>
    <head>
        <title>Registered teams for NCPC 2005</title>
        <style>
            td, th {
                text-align: left;
            }
        </style>
    </head>

    <body>
        <h1>Registered teams for NCPC 2005</h1>
<?
    if (!file_exists($RDATA) || filesize($RDATA) == 0) {
        $registrations = array();
    }
    else {
        $fp = fopen($RDATA, "r");
        $registrations = unserialize(fread($fp, filesize($RDATA)));
        fclose($fp);
    }

    echo "
        <p>
            There are " . count($registrations) . " teams registered in total.
        </p>";

    $sitereg = array();
    foreach($registrations as $r) {
        $s = $r['SiteID'];
        if (!isset($sitereg[$s])) {
            $sitereg[$s] = array();
        }
        $sitereg[$s][] = $r;
    }

    foreach ($sites as $sid) {
        echo "
            <h2>" . $siteinfo[$sid]['name'] . "</h2>
            <p>" . count($sitereg[$sid]) . " teams registered.</p>";
        if (isset($sitereg[$sid])) {
            echo "
            <table>
                <tr>
                    <th width=\"200px\">Team name</th>
                    <th width=\"200px\">Member 1</th>
                    <th width=\"200px\">Member 2</th>
                    <th width=\"200px\">Member 3</th>
                </tr>";
            foreach ($sitereg[$sid] as $n => $r) {
                echo "
                <tr>
                    <td>".$r['TeamName']."</td>";
                foreach (array(1,2,3) as $C) {
                    $nam = trim($r['FirstMiddle'.$C]." ".$r['LastName'.$C]);
                    if ($nam != '') {
                        echo "<td>$nam</td>";
                    }
                }
                echo "
                </tr>";
                /*
                echo "
                    <pre>";
                foreach ($r as $k => $v) {
                    echo str_pad("$k:", 20, " ", STR_PAD_RIGHT) .  "$v\n";
                }
                echo "
                    </pre>";
                */
            }
            echo "
            </table>";
        }
    }
?>
    </body>
</html>
