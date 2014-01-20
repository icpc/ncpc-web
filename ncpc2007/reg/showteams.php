<?
    include('defs.php');

    function print_header() {
        header('Content-Type: text/html; charset=UTF-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Registered teams for NCPC 2007</title>
        <link rel="stylesheet" type="text/css" href="../../main.css" />
        <style type="text/css">
            h1,h2,h3,h4 {
                text-align: left;
                padding-left: 0;
            }
            h2,h3,h4 {
                padding-top: 40px;
                margin-bottom: 10px;
            }
        </style>
    </head>

    <body>
        <div style="text-align: center;">
            <table class="divcenter" style="text-align: justify">
                <tr>
                    <td class="page">';
    }

    function print_footer() {
        echo '
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>';
    }

    print_header();

    $registrations = dbquery("SELECT * FROM teams");
    $cunt = array(
                          // eliglibility
        'nordic' => array('yes' => 0, 'no' => 0),
        'bycountry' => array(),
        'bysite' => array(),
    );
    foreach ($COUNTRIES as $ckey => $cval) {
        $cunt['bycountry'][$ckey] = array('yes' => 0, 'no' => 0);
    }
    $sitereg = array();
    foreach ($SCHOOLS_ALL as $sid) {
        $cunt['bysite'][$sid] = array('yes' => 0, 'no' => 0);
        $sitereg[$sid] = array();
    }
    foreach($registrations as $r) {
        if ($r['Eliglible'] == 'yes') $sid = $r['AffiliationID'];
        else                          $sid = $r['SiteID'];
        $cunt['nordic'][$r['Eliglible']]++;
        $cunt['bycountry'][$SCHOOLINFO[$sid]['country']][$r['Eliglible']]++;
        $cunt['bysite'][$sid][$r['Eliglible']]++;
        $sitereg[$sid][] = $r;
    }

    function num_all($arr) {
        return $arr['yes'] + $arr['no'];
    }

    function print_stats($arr, $where) {
        echo '
            <tr>
                <td>'.$where.'</td>
                <td style="text-align: right;">'.num_all($arr).'</td>
                <td style="text-align: right;">'.$arr['yes'].'</td>
            </tr>';
    }

    echo '
        <h1>Nordic Programming Championship</h1>
        <p style="font-style: italic;">
            Go back to <a href="..">NCPC 2007</a> page.
        </p>
        <p style="font-style: italic;">
            <a href="http://ncpc.idi.ntnu.no/ncpc2007/#Rules/">ICPC 
            eliglible</a> teams are listed under their affiliated site, while 
            other teams are listed at the site they compete at.
        </p>
        <table>
            <tr>
                <th></th>
                <th>Total</th>
                <th>ICPC</th>
            </tr>
        ';
    print_stats($cunt['nordic'], 'All countries');
    foreach ($COUNTRIES as $ckey => $cval) {
        if (num_all($cunt['bycountry'][$ckey])) {
            print_stats($cunt['bycountry'][$ckey], $cval);
            foreach ($SCHOOLS_BY_COUNTRY[$ckey] as $sid) {
                if (num_all($cunt['bysite'][$sid])) {
                    if ($sid == 'chal') $short = 'Chalmers';
                    else                $short = strtoupper($sid);
                    print_stats($cunt['bysite'][$sid],
                                '&nbsp;&nbsp;&nbsp;&nbsp;'.$short);
                }
            }
        }
    }
    echo '
        </table>';

    foreach ($COUNTRIES as $ckey => $cval) {
        if (num_all($cunt['bycountry'][$ckey])) {
            //echo '
            //<br />
            //<img src="http://www.csc.kth.se/contest/ncpc/2006/'.$ckey.'.gif" alt="flag" />';
            //echo '
            //<h2>'.  $cval .'</h2>';
            //print_stats2($cunt['bycountry'][$ckey]);
            foreach ($SCHOOLS_BY_COUNTRY[$ckey] as $sid) {
                if (num_all($cunt['bysite'][$sid])) {
                    echo "
                        <h3>{$SCHOOLINFO[$sid]['name']}</h3>";
                    //print_stats2($cunt['bysite'][$sid]);
                    echo '
                        <table>
                            <tr>
                                <th style="width: 30px"></th>
                                <th style="width: 40px">ICPC</th>
                                <th style="width: 300px">Team name</th>
                                <th style="width: 250px">Member 1</th>
                                <th style="width: 250px">Member 2</th>
                                <th style="width: 250px">Member 3</th>
                            </tr>';
                    $cnt = 1;
                    foreach ($sitereg[$sid] as $n => $r) {
                       $tn = escapeHTML($r['TeamName']);
                        if ($tn == 'WE LOVE UNICODE') {
                            $tn = 'WE &#9744; UNICODE';
                        }
                        echo '
                        <tr>
                            <td style="text-align: right">'.$cnt++.'</td>
                            <td style="text-align: center">'.($r['Eliglible']=='yes'?'x':'').'</td>
                            <td>'.$tn.'</td>';
                        foreach (array(1,2,3) as $C) {
                            $nam = trim(escapeHTML($r['FirstMiddle'.$C]) . " " .
                                        escapeHTML($r['LastName'.$C]));
                            if ($nam != '') {
                                echo "<td>$nam</td>";
                            }
                        }
                        echo "
                        </tr>";
                    }
                    echo "
                    </table>";
                }
            }
        }
    }

    print_footer();
?>
