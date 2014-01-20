<?
    include('defs.php');

//<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
//        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
//<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">
?>
<html>
    <head>
        <title>Registered teams for NCPC 2006</title>
        <style>
            h1, h2, h3, p, td, th {
                font-family: sans-serif;
            }
            td, th {
                text-align: left;
            }
        </style>
    </head>

    <body>
        <h1>Registered teams for NCPC 2006</h1>
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

    foreach ($SITES as $sid) {
        if (isset($sitereg[$sid])) {
            echo "
                <h2>" . $SITEINFO[$sid]['name'] . "</h2>
                <!-- <p>" . (isset($sitereg[$sid]) ? count($sitereg[$sid]) : 0) 
                . " teams registered.</p> -->";
            echo "
            <table>
                <tr>
                    <th width=\"30px\"></th>
                    <th width=\"400px\">Team name</th>
                    <th width=\"230px\">Member 1</th>
                    <th width=\"230px\">Member 2</th>
                    <th width=\"230px\">Member 3</th>
                </tr>";
            $cnt = 1;
            foreach ($sitereg[$sid] as $n => $r) {
                echo "
                <tr>
                    <td>".$cnt++."</td>
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
