<?php
/* 
 * Search for TODO to find this that must be changed.
 */


/* Simple email regexp from http://www.regular-expressions.info/email.html
 * ^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$
 */

$REGISTRATION_END = '2008-10-03 08:00';

include_once('defs.php');

function print_header() {
    global $MY_CHARSET;
    header('Content-Type: text/html; charset='.$MY_CHARSET.'');
    echo '<?xml version="1.0" encoding="'.$MY_CHARSET.'"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset='.$MY_CHARSET.'" />
        <title>Registration NCPC 2008</title>
        <link rel="stylesheet" type="text/css" href="../../main.css" />
    </head>

    <body>
        <div style="text-align: center;">
            <table class="divcenter" style="text-align: justify" width="750px">
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

register_shutdown_function('print_footer');
print_header();

$checks = $CHECKS;
// Let us use $_POST for filling out default field values to "" without 
// worrying about whether or not it is set.
foreach ($checks as $k => $v) {
    if (! isset($_POST[$k])) {
        $_POST[$k] = '';
    }
}

if (isset($_POST['action'])) {
    $action = $_POST['action'];
} else {
    $action = '';
}

if ($action == 'AddTeam') {
    dbQuery('BEGIN TRANSACTION');
    
    $error = '';
    
    unset($_POST['Add']);
    unset($_POST['action']);
    
    foreach ($_POST as $k => $v) {
        $_POST[$k] = trim(formToForm($v));
    }
    $havemember = array('' => TRUE, 
                        1    => TRUE, 
                        2    => TRUE, 
                        3    => TRUE);
    foreach (array(2,3) as $C) {
        if ($_POST['FirstMiddle'.$C] == '' && $_POST['LastName'.$C] == '') {
            $havemember[$C] = FALSE;
            foreach ($MEMBER_CHECKS as $k => $v) {
                unset($checks["$k$C"]);
            }
        }
    }
    foreach ($_POST as $k => $v) {
        if (preg_match("(http://)", $v)) {
            $error .= "No URLs, please\n";
        }
    }
    
    /*
    foreach (array(1,2,3) as $C) if ($havemember[$C]) {
        $_POST['Email'.$C] = strtolower($_POST['Email'.$C]);
        $_POST['CEmail'.$C] = strtolower($_POST['CEmail'.$C]);
    }
    */

    foreach ($checks as $field => $what) {
        if ($what === FALSE) continue;
        $value = $_POST[$field];
        $type = $what[0];
        $patt = $PATTERNS[$type];
        $minlen = $what[1];
        $maxlen = $what[2];
        $needed = $what[3];
        
        if ($needed && strlen($value) == 0) {
            $error .= "The field $field was not entered.\n";
        } else if (strlen($value) < $minlen) {
            $error .= "Field $field below the minimum length of $minlen.\n";
        } else if (strlen($value) > $maxlen) {
            $error .= "Field $field above the maximum length of $maxlen.\n";
        } else {
            if (!preg_match($patt, $value) && !in_array($field, array('CEmail1', 'CEmail2', 'CEmail3'))) {
                $error .= "Error prosessing field $field:\n";
                if (in_array($field, array('Email1', 'Email2', 'Email3'))) {
                    $error .= "        Illegal email address.\n";
                }
                $error .= "        '" . $value . "' does not match '$patt'\n";
            }
        }
        if ($error === '' && $type === 'siteid') {
            if ($value === '') {
                $error .= "The field $field was not set.\n";
            } else if ($value == 'fromteamdef') {
                // OK
            } else if (! array_key_exists($value, $SCHOOLINFO)) {
                $error .= "The value '$value' is not valid for $field.\n";
            }
        }
    }

    $aff =    $_POST["AffiliationID"];
    $affpat = "^(?:$aff|fromteamdef)$";
    $otherpat = '^(:?\\w+\\.other)$';
 
    // Check that affiliations are sensible
    $some_fromteamdef = FALSE;
    foreach (array(1, 2, 3) as $C) if ($havemember[$C]) {
        if ($_POST["AffiliationID$C"] === 'fromteamdef') {
            $some_fromteamdef = TRUE;
        }
    }
    foreach (array('', 1, 2, 3) as $C) if ($havemember[$C]) {
        $affm =    $_POST["AffiliationID$C"];
        // Check that it is specified textually if and only if
        // "Country: Other" was chosen
        if (preg_match("/$otherpat/", $affm)) {
            if ( ($C == '' && $some_fromteamdef) ||
                 ($C != '' && $_POST["AffiliationOther"] === '') ) {
                if ($_POST["AffiliationOther$C"] === '') {
                    $error .= "Affiliation was not specified";
                    if ($C !== '') {
                        $error .= " for member $C (or the team)";
                    } else {
                        $error .= " for the team";
                    }
                    $error .= ",\n        even though '" . 
                    affName($affm) . "' was selected";
                    if ($C == '') {
                        $error .= ", and a member had '" . 
                        affName('fromteamdef') . "'";
                    }
                    $error .= ".\n";
                }
            }
        } 
    }

    // Check E-mails
    foreach (array(1,2,3) as $C) if ($havemember[$C]) {
        if ($_POST['Email'.$C] != $_POST['CEmail'.$C]) {
            $error .= "Email addresses did not match: '" .
                      $_POST['Email'.$C] . "' != '" . 
                      $_POST['CEmail'.$C] . "'\n";
        }
        /* Checked elsewhere
        else if (preg_match('//', $_POST['Email'.$C]) == 0) {
            $error .= "Email address '" . $_POST['Email'.$C] . 
                      "' is not legal\n";
        }
        */
    }
    // Check Kattis account
    foreach (array(1,2,3) as $C) if ($havemember[$C]) {
        if ($_POST["HaveKattisLogin$C"] === 'yes' &&
                $_POST["KattisLogin$C"] === '') {
            $error .= "Kattis login was not entered for member $C, " .
                       "but 'I have a Kattis account' was selected.\n";
        } else if ($_POST["HaveKattisLogin$C"] === 'no' &&
                $_POST["KattisLogin$C"] !== '') {
            $error .= "Kattis login was entered for member $C, " .
                      "but 'I need a Kattis account' was selected.\n";
        } 
    }

    // Check eliglibility
    if ($_POST["Eliglible"] == 'yes') {
        if (preg_match("/$otherpat/", $_POST["AffiliationID"])) {
            $error .= "ICPC eliglibility selected, but team " .
                      "has affiliation '" . 
                      affName($_POST["AffiliationID"]) . "'.\n";
        }
    }

    $old = dbQuery("SELECT * 
                    FROM TEAMS 
                    WHERE teamname=".quoteForDB($_POST['TeamName'])."");
    if (count($old) > 0) {
        $error .= "Team with name '{$_POST['TeamName']}' already exists.\n";
    }
    $used_email = array();
    $used_kattislogin = array();
    foreach (array(1,2,3) as $C) if ($havemember[$C]) {
        if (array_key_exists($_POST["Email$C"], $used_email)) {
            $error .= "Members cannot have the same e-mail address.\n";
        }
        $used_email[$_POST["Email$C"]] = TRUE;
        $old = dbQuery("SELECT * 
                        FROM TEAMS
                        WHERE email".$C." == ".
                                quoteForDB($_POST["Email$C"]));
        if (count($old) > 0) {
            $error .= "Member with e-mail {$_POST["Email$C"]} " .
                      "already exists in database.\n";
        }
        if ($_POST["KattisLogin$C"] !== '') {
            if (array_key_exists($_POST["KattisLogin$C"], $used_kattislogin)) {
                $error .= "Members cannot have the same Kattis logins.\n";
            }
            $used_kattislogin[$_POST["KattisLogin$C"]] = TRUE;
            $old = dbQuery("SELECT * 
                            FROM TEAMS
                            WHERE kattislogin".$C." == ".
                                    quoteForDB($_POST["KattisLogin$C"]));
            if (count($old) > 0) {
                $error .= "Member with Kattis login " . 
                          "{$_POST["KattisLogin$C"]} " .
                          "already exists in database.\n";
            }
        }
    }

    if ($error == '') {
        $posts = array();
        foreach ($FIELD_NAMES as $f) {
            $posts[] = quoteForDB($_POST[$f]);
        }
        $query = "INSERT INTO teams (\n" .
                 "        " . implode(",\n        ", 
                        array_merge($FIELD_NAMES, array('Country'))) . 
                 ")\n" .
                 "VALUES (\n" .
                 "        " . implode(",\n        ", 
                        array_merge($posts, array(quoteForDB(
                                        $SCHOOLINFO[$_POST['AffiliationID']]
                                                   ['country'])))) .
                 ")";

        dbQuery($query);

        if ($_POST["Eliglible"] === 'yes') {
            $maddr = $SCHOOLINFO[$_POST['AffiliationID']]['email'];
        } else {
            $maddr = $SCHOOLINFO[$_POST['SiteID']]['email'];
        }

        $pretty = "\nRegistration:\n";
        foreach ($FIELD_NAMES as $f) {
            $pretty .= ljust("$f:", 20) . $_POST[$f] . "\n";
        }
        mb_language("English");
        mb_send_mail($maddr,
                     "NCPC registration for '{$_POST['TeamName']} " . 
                            "at '{$_POST['AffiliationID']}'", 
                     $pretty);
        // TODO: Change this to the technical admins mail address.
        mb_send_mail('nils.grimsmo@idi.ntnu.no',
                     "(backup) NCPC registration for '{$_POST['TeamName']} " .
                            "at '{$_POST['SiteID']}'",
                     $pretty);

        echo "
<p>
    Team '".escapeHTML($_POST['TeamName'])."' added.<br />
    Email sent to &lt;$maddr&gt;.<br />
</p>
<p>
    Go back to <a href=\"../\">NCPC 2008 page</a>,<br />
    or view <a href=\"showteams.php\">registered teams</a>.
</p>";
        $action = 'done';
        /*
                        include_once('toxml.php');
                        writeXML();
                        include_once('totext.php');
                        writeText();
        */
        dbQuery('COMMIT TRANSACTION');
    } else { # if ($error != '') {
        echo '
<p>The form has the following errors:</p>
<pre style="color: red; background-color: inherit; width: 100%">'
            . htmlspecialchars($error). '</pre>';
        $action = '';
        dbQuery('ROLLBACK TRANSACTION');
    }
}

if ($action == '') {
    echo '
<form action="" method="post" accept-charset="'.$MY_CHARSET.'"> 
    <p>
        <input type="hidden" name="action" value="AddTeam" />
    </p>

    <h1>Registration NCPC 2008</h1>';
    if (time() > strtotime($REGISTRATION_END)) {
        echo '
    <p style="color: red; font-size: xx-large;">
        The registration period ended '.$REGISTRATION_END.'
    </p>';
    }
    echo '

    <p style="color: red;">
        The official registration period is now over. Ask your local
        organiser if it is possible to register late.
    </p>

<!--        
    <p style="color: red; background-color: inherit;">
        The registration is not open yet.
        Teams entered will be deleted.
        (Bogus <a href="showteams.php">list of teams</a>)
    </p>
-->

    <p>
        <i>List of <a href="showteams.php">registered teams</a></i>.
        <br />
        <i>Go back to <a href="..">NCPC 2008</a> page.</i>.
    </p>';
    
    /*
    echo '<p>
        The registration is open until ' . $REGISTRATION_END . ' CEST.
        Advance registration until one week before.
        (What this implies depends on your local organiser.)
    </p>
    <p>
        <b>How the registration works for 
        <a href="http://ncpc.idi.ntnu.no/ncpc2008/#rules">ICPC 
            eliglible</a> teams (normal students teams):</b>
    </p>
    <ol>
        <li>You fill out the form below.</li> 
        <li>The contest organiser at your school receives an e-mail with your 
            info, and registers you in the central ICPC system.</li> 
        <li>You receive an e-mail, requesting that you complete your 
            information there.</li>
        <li>You are done!</li>
    </ol>
    <p>
        The reason we have this multi-step system is that the ICPC 
        requires us to verify the contestants.
    </p>
    <p>
        <b>For other teams:</b>
    </p>
    <ol>
        <li>Just fill out the form below.</li> 
    </ol>
    <p style="font-size: small; font-style: italic; ">
        Warning: Only ISO-8859-1 symbols will be displayed properly by the 
        system. Other symbols (Unicode 256 and above) will get mangled 
        (Into &amp;#1234; etc..).
    </p>
    <p>
        <br/>
        <br/>
        <br/>
    </p>
    <table width="100%"> 
        <tr>
            <th class="right" style="width: 30%">Team Name</th>
            <td>
                <input type="text" size="30" maxlength="30"
                        name="TeamName" 
                        value="'.escapeHTML($_POST['TeamName']).'" />
            </td>
        </tr>
        <tr>
            <th class="right">ICPC Eliglibility</th>
            <td>
                <input type="radio" name="Eliglible" value="yes"
                        ' . ($_POST['Eliglible'] == 'yes' ? 
                                 'checked="checked"' : '') . '/>
                Yes. (Students only. See    
                <a href="http://ncpc.idi.ntnu.no/ncpc2008/#rules">Rules</a>)
                <br />
                <input type="radio" name="Eliglible" value="no"
                        ' . ($_POST['Eliglible'] == 'no' ? 
                                 'checked="checked"' : '') . '/>
                No.
            </td>
        </tr>
        <tr>
            <th class="right">Affiliation</th>
            <td>
                <select name="AffiliationID">
                    <option value="">- Select -</option>';
    foreach ($SCHOOLINFO as $k => $v) {
        echo '
                    <option value="'.$k.'"' .    
                            ($_POST['AffiliationID'] == $k ?    
                             ' selected="selected"' : '') . '>'
                            . affName($k) . '</option>';
    }
    echo '
                </select>
                <br />
                <i>Teams that have selected "Country: Other" should specify 
                their affiliation (e.g. "IBM") below, for the team and/or the 
                members.</i>
                <br />
                <input type="text" size="30" maxlength="30"
                        name="AffiliationOther" 
                        value="'.escapeHTML($_POST['AffiliationOther']).'" />
            </td>
        </tr>
        <tr>
            <th class="right">Competes at site</th>
            <td>
                <select name="SiteID">
                    <option value="">- Select -</option>';
    foreach ($SCHOOLINFO as $k => $v) {
        if (!isset($v['nosite'])) {
            echo '
                    <option value="'.$k.'"' .    ($_POST['SiteID'] == $k ?    
                                            ' selected="selected"' : '') . '>'
                    . $COUNTRIES[$v['country']] . ': ' . $v['name'] . 
                    '</option>';
        }
    }
    echo '
                </select>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td></td>
            <td><i>A team can have 1-3 members.</i></td>
        </tr>';
    foreach (array(1,2,3) as $C) {
        echo '
        <tr> 
            <th>&nbsp;</th> 
            <th>Team member '.$C.'</th> 
        </tr> 
        <tr> 
            <th class="right">First Name</th> 
            <td>
                <input type="text" size="30" name="FirstMiddle'.$C.'" 
                        value="'.escapeHTML($_POST["FirstMiddle$C"]).'" 
                        maxlength="30" />
            </td> 
        </tr> 
        <tr> 
            <th class="right">Last Name</th>
            <td>
                <input type="text" size="30" name="LastName'.$C.'" 
                        value="'.escapeHTML($_POST["LastName$C"]).'" 
                        maxlength="30" />
            </td> 
        </tr>
        <tr> 
            <th class="right">Email Address</th>
            <td>
                <input type="text" size="40" maxlength="70" name="Email'.$C.'"
                        value="'.$_POST["Email$C"].'" />
            </td> 
        </tr> 
        <tr> 
            <th class="right">Retype Email Address</th>
            <td>
                <input type="text" size="40" maxlength="70" name="CEmail'.$C.'"
                    value="'.$_POST["CEmail$C"].'" />
            </td> 
        </tr>
        <tr>
            <th class="right">Affiliation</th>
            <td>
                <select name="AffiliationID'.$C.'">
                    <option value="fromteamdef">'.affName('fromteamdef').
                            '</option>';
        foreach ($SCHOOLINFO as $k => $v) {
            echo '
                    <option value="'.$k.'"' .    
                            ($_POST["AffiliationID$C"] == $k ?    
                             ' selected="selected"' : '') . '>'
                            . affName($k) . '</option>';
        }
        echo '
                </select>
                <br />
                <i>If you selected "Country: Other", specify:</i>
                <br />
                <input type="text" size="30" maxlength="30"
                        name="AffiliationOther'.$C.'" 
                        value="'.escapeHTML($_POST["AffiliationOther$C"]).'" />
            </td>
        </tr>
        <tr>
            <th class="right">Kattis login</th>
            <td>
                <input type="radio" name="HaveKattisLogin'.$C.'" value="yes" 
                        ' . ($_POST["HaveKattisLogin$C"] == 'yes' ? 
                                 'checked="checked"' : '') . '/>
                I <b>have</b> a 
                <a href="https://kattis.csc.kth.se/">Kattis</a> account: 
                <input type="text" size="30" maxlength="30"
                        name="KattisLogin'.$C.'" 
                        value="'.escapeHTML($_POST["KattisLogin$C"]).'" />
                <br />
                <input type="radio" name="HaveKattisLogin'.$C.'" value="no"
                        ' . ($_POST["HaveKattisLogin$C"] == 'no' ? 
                                 'checked="checked"' : '') . '/>
                I <b>need</b> a 
                <a href="https://kattis.csc.kth.se/">Kattis</a> account
                (All team members will get one since they need it for the 
                contest).
            </td>
        </tr>';
        if ($C < 3) {
            echo '
        <tr><td>&nbsp;</td></tr>';
        }
    }
    echo '
        <tr><td>&nbsp;</td></tr>
        <tr> 
            <td>&nbsp;</td>
            <td><input type="submit" name="Add" value="Register Team" /></td>
        </tr> 
    </table> 
</form> 

<p class="discreet">
    Registrations will be sent to:
</p>

<table>';
    foreach ($SCHOOLINFO as $k => $v) {
        echo '
    <tr>
        <td class="discreet">'.$COUNTRIES[$v['country']].': '.$v['name'].'</td>
        <td class="discreet">'.htmlspecialchars(str_replace('@', ' ', $v['email'])).'</td>
    </tr>';
    }
    echo '
</table>';
    */
}


?>
