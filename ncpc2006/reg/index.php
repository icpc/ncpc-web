<?
    include('defs.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">
    <head>
        <title>Registration for NCPC 2006</title>
    </head>

    <body>
        <p>
            The registration is closed.
        </p>  
    </body
</html>
<?
    exit(0);
    $checks = array(
            'FirstMiddle' => 'name',
            'LastName' => 'name',
            /*
            'Hometown' => 'name',
            'HomeCountry' => 'name',
             */
            'Email' => 'email',
            'CEmail' => 'email',
            /*
            'Voice' => 'name',
            'TShirtSize' => 'size',
            'Gender' => 'sex',
            'Institution' => 'name',
            'Major' => 'name',
            'EnteredDate' => 'date',
            'ExpectedGradDate' => 'date',
            'DateOfBirth' => 'date',
            */
    );

    //echo "<pre>";
    $POST = array();
    foreach ($_POST as $k => $v) {
        $v = tohtml(trim($v));
        $POST[$k] = $v;
        $$k = $v;
        //echo "$k => $v\n";
    }
    foreach ($checks as $k => $v) {
        foreach (array(1,2,3) as $C) {
            $k2 = "$k$C";
            if (! isset($POST[$k2])) {
                $POST[$k2] = '';
                $$k2 = '';
            }
        }
    }
    foreach (array("action", "TeamName", "SiteID") as $k) {
        if (! isset($POST[$k])) {
            $POST[$k] = '';
            $$k = '';
        }
    }
    //echo "</pre>";

    if ($action == 'AddTeam') {

        unset($POST['action']);
        unset($POST['Add']);

        $error = '';

        foreach ($POST as $k => $v) {
            if (preg_match("(http://)", $v)) {
                $error .= "No URLs, please\n";
            }
        }

        //pr($POST);

        if (strlen($POST['TeamName']) < 4) {
            $error .= "Length of team name must be at least 4.\n";
        }

        if (! array_key_exists($POST['SiteID'], $SITEINFO)) {
            $error .= "The site '" . $POST['SiteID'] . "' is not valid.\n";
        }

        $patterns = array(
            'name' => '/.+/',
            'email' => '/\w+(\w+\.)*@\w+\.(\w+\.)*.\w+/',
            'date' => '/(\d\d)\/(\d\d)\/(\d\d\d\d)/',
            'sex' => '/M|F/',
            'size' => '/S|M|L|XL|XXL/',
        );

        foreach (array(1,2,3) as $C) {
            if ($POST['FirstMiddle'.$C] == '' 
                    && $POST['LastName'.$C] == '') {
                continue;
            }
            foreach ($checks as $fi => $type) {
                $field = $fi.$C;
                $value = trim($POST[$field]);
                $POST[$field] = $value;
                $patt = $patterns[$type];

                if (strlen($value) < 1) {
                    $error .= "Field '$field' was empty.\n";
                }
                else {
                    $matches = array();
                    if (!preg_match($patt, $value, $matches)) {
                        $error .= "Error prosessing field '$field':\n".
                                  "    '" . $value . 
                                  "' does not match '$patt'\n";
                    }
                    if ($error == '') {
                        if ($type == 'date') {
                            $tim = mktime(0,0,0,$matches[1],$matches[2],$matches[3]);
                            if ($tim < mktime(0,0,0,1,1,1910) ||
                                    mktime(0,0,0,1,1,2025) < $tim) {
                                $error .= "Error prosessing field '$field':\n".
                                          "    '" . $value . 
                                          "' is not a sensible date\n";
                            }
                        }
                    }
                }
            }
            if ($error == '') {
                if ($POST['Email'.$C] != $POST['CEmail'.$C]) {
                    $error .= "Email addresses did not match: " .
                            $POST['Email'.$C] . " != " . $POST['CEmail'.$C] . "\n";
                }
            }
        }
        if ($error == '') {
            $fp = fopen($RDATA, "r+b");
            if (!file_exists($RDATA) || filesize($RDATA) == 0) {
                $registrations = array();
            }
            else {
                $registrations = unserialize(fread($fp, filesize($RDATA)));
            }
            
            $team = $POST['TeamName'];
            $site = $POST['SiteID'];

            foreach ($registrations as $r) {
                if ($r['TeamName'] == $team) {
                    $error .= "A team with the name '$team' already exists.\n";
                    break;
                }
            }
            if ($error == '') {
                $registrations[] = $POST;

                $maddr = $SITEINFO[$site]['email'];

                $pretty = "\n";
                foreach ($POST as $k => $v) {
                    $pretty .= str_pad("$k:", 20, " ", STR_PAD_RIGHT) .  "$v\n";
                }
                mail($maddr,
                     "NCPC registration for '$team' at '$site'",
                     $pretty);
                mail('nils.grimsmo@idi.ntnu.no',
                     "(backup) NCPC registration for '$team' at '$site'",
                     $pretty);

                fseek($fp, 0);
                fwrite($fp, serialize($registrations));
                fclose($fp);
                echo "
                    <p>
                        Team '$team' added.<br />
                        Email sent to &lt;$maddr&gt;.<br />
                    </p>
                    <p>
                        Go back to <a href=\"../\">NCPC 2006 page</a>.
                    </p>
                </body></html>";
                exit(0);
            }
        }
        if  ($error != '') {
            echo "
                <h1>The form has the following errors:</h1>
                <pre style=\"color: red;\">$error</pre>";
        }
    }
?>
    <form action="" method="POST"> 
        <input type="hidden" name="action" value="AddTeam" />

<!--
        <p style="color: red;">
            The official registration period is now over. Ask your local
            organiser if it is possible to register late.
        </p>
-->
        
        <h1>Registration for NCPC 2006</h1>
        <p>
            <b>How the registration works:</b>
        </p>
        <ol>
            <li>You fill out the form below.</li> 
            <ul>
                <li>If you do not qualify as a collegiate contestant (see 
                    <a href="../#Rules/">Rules</a>), write <code>[OPEN 
                    CLASS]</code>after your team name.</li>
                <!--
                <li>If your university is not listed, use 
                    <code>(Unlisted)</code>, and send an e-mail to the 
                    <a href="mailto:nils.grimsmo@idi.ntnu.no">contest organiser</a>
                    giving your school name.</li>
                -->
            </ul>
            <li>The contest organiser at your site receives an e-mail with your 
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
            Registration open until Thursday 28th of September 23:59.
        </p>
        <table>
            <tr> 
                <td>
<table border="0" style="background-color: rgb(247,251,251);
        color: rgb(0,128,128)" cellspacing="0" cellpadding="2"
        width="100%"> 
    <tr>
        <td align="right" checked="false" valign="middle" bgcolor="#008080"
                nowrap>
            <div width="150"><p>&nbsp;</p></div>
        </td>
        <td bgcolor="#008080"></td>
        <td valign="middle" align="left" bgcolor="#008080">
            <font color="#F7FBFB"><strong>Enter Team Information</strong></font>
        </td>
    </tr>
    <tr>
        <td align="right" valign="top" checked="false" bgcolor="#008080"
                nowrap>
            <font color="#F7FBFB">Team Name</font>
        </td>
        <td valign="top"></td>
        <td valign="top"><input type="text" size="30" maxlength="30"
                name="TeamName" value="<?echo $TeamName;?>">
            <br />
            <em>If you compete in an open class at your university, 
            write </em><code>[OPEN CLASS]</code><em> after your team name.</em>
        </td>
    </tr>
    <tr>
        <td align="right" valign="top" checked="false" bgcolor="#008080"
                nowrap>
            <font color="#F7FBFB">Select Site</font>
        </td>
        <td valign="top"></td>
        <td valign="top">
            <select name="SiteID" size="1">
                <option value =""></option>
<?
    foreach ($SITEINFO as $key => $value) {
        echo '
                <option value="'.$key.'"' . ($SiteID==$key?' selected':'') . 
                        '>'.$value['name'].'</option>';
    }
?>
            </select>
        </td>
    </tr>
<?
    foreach (array(1,2,3) as $C) {
        echo '
    <tr> 
        <td align="right" valign="top" bgcolor="#008080" nowrap></td> 
        <td valign="top" bgcolor="#008080"></td> 
        <td valign="top" bgcolor="#008080">
            <font color="#F7FBFB"><strong>Contestant '.$C.'</strong></font>
        </td> 
    </tr> 
    <tr> 
        <td align="right" style="margin-left: 4pt; margin-right: 4pt" 
              valign="middle" bgcolor="#008080" nowrap>
            <font color="#F7FBFB">First Name</font>
        </td> 
        <td style="margin-left: 4pt; margin-right: 4pt"></td> 
        <td style="margin-left: 4pt; margin-right: 4pt">
            <input type="text" size="30" name="FirstMiddle'.$C.'" 
                    value="'.${'FirstMiddle'.$C}.'"
            maxlength="30" />
        </td> 
    </tr> 
    <tr> 
        <td align="right" style="margin-left: 4pt; margin-right: 4pt"
                valign="middle" bgcolor="#008080" nowrap>
            <font color="#F7FBFB">Last Name</font>
        </td> 
        <td style="margin-left: 4pt; margin-right: 4pt"></td> 
        <td style="margin-left: 4pt; margin-right: 4pt">
            <input type="text" size="30" name="LastName'.$C.'" 
                    value="'.${'LastName'.$C}.'" maxlength="30" />
        </td> 
    </tr>';
/*
    <tr> 
        <td align="right" style="margin-left: 4pt; margin-right: 4pt" 
                valign="middle" bgcolor="#008080" nowrap>
            <font color="#F7FBFB">Home City</font>
        </td> 
        <td style="margin-left: 4pt; margin-right: 4pt"></td> 
        <td style="margin-left: 4pt; margin-right: 4pt">
            <input type="text" size="40" name="Hometown'.$C.'" 
                    value="'.${'Hometown'.$C}.'" maxlength="40">
        </td> 
    </tr> 
    <tr> 
    <td align="right" style="margin-left: 4pt; margin-right: 4pt" 
            valign="middle" bgcolor="#008080" nowrap>
        <font color="#F7FBFB">Home Country</font>
    </td> 
    <td style="margin-left: 4pt; margin-right: 4pt"></td> 
    <td style="margin-left: 4pt; margin-right: 4pt">
        <input type="text" size="4" maxlength="3" name="HomeCountry'.$C.'" 
                value="'.${'HomeCountry'.$C}.'">
        &nbsp;&nbsp; <em>NOR, SWE, ... </em>
    </td> 
    </tr> 
*/
    echo '
    <tr> 
        <td align="right" style="margin-left: 4pt; margin-right: 4pt"
                valign="middle" bgcolor="#008080" nowrap>
            <font color="#F7FBFB">Email Address</font>
        </td> 
        <td style="margin-left: 4pt; margin-right: 4pt"></td> 
        <td style="margin-left: 4pt; margin-right: 4pt">
            <input type="text" size="40" maxlength="70" name="Email'.$C.'"
                    value="'.${'Email'.$C}.'">
        </td> 
    </tr> 
    <tr> 
        <td align="right" style="margin-left: 4pt; margin-right: 4pt" 
              valign="middle" bgcolor="#008080" nowrap>
            <font color="#F7FBFB">Retype Email Address</font>
        </td> 
        <td style="margin-left: 4pt; margin-right: 4pt"></td> 
        <td style="margin-left: 4pt; margin-right: 4pt">
            <input type="text" size="40" maxlength="70" name="CEmail'.$C.'"
                    value="'.${'CEmail'.$C}.'">
        </td> 
    </tr>';
/*
    <tr> 
        <td align="right" style="margin-left: 4pt; margin-right: 4pt" 
                valign="middle" bgcolor="#008080" nowrap>
            <font color="#F7FBFB">Voice Phone</font>
        </td> 
        <td style="margin-left: 4pt; margin-right: 4pt"></td> 
        <td style="margin-left: 4pt; margin-right: 4pt">
            <input type="text" size="20" name="Voice'.$C.'" 
                    value="'.${'Voice'.$C}.'" maxlength="20">
        </td> 
    </tr> 
    <tr> 
        <td align="right" style="margin-left: 4pt; margin-right: 4pt" 
                valign="middle" bgcolor="#008080" nowrap>
            <font color="#F7FBFB">T-Shirt Size</font>
        </td> 
        <td style="margin-left: 4pt; margin-right: 4pt"></td> 
        <td style="margin-left: 4pt; margin-right: 4pt">
            <input type="radio" name="TShirtSize'.$C.'" value="S"' .
                    (${'TShirtSize'.$C}=='S'?' checked':'') . '>Small 
            <input type="radio" name="TShirtSize'.$C.'" value="M"' .
                    (${'TShirtSize'.$C}=='M'?' checked':'') . '>Medium 
            <input type="radio" name="TShirtSize'.$C.'" value="L"' .
                    (${'TShirtSize'.$C}=='L'?' checked':'') . '>Large 
            <input type="radio" name="TShirtSize'.$C.'" value="XL"' .
                    (${'TShirtSize'.$C}=='XL'?' checked':'') . '>XL 
            <input type="radio" name="TShirtSize'.$C.'" value="XXL"' .
                    (${'TShirtSize'.$C}=='XXL'?' checked':'') . '>XXL 
        </td> 
    </tr> 
    <tr> 
        <td align="right" style="margin-left: 4pt; margin-right: 4pt" 
                valign="middle" bgcolor="#008080" nowrap>
            <font color="#F7FBFB">Gender</font>
        </td> 
        <td style="margin-left: 4pt; margin-right: 4pt"></td> 
        <td style="margin-left: 4pt; margin-right: 4pt">
            <input type="radio" name="Gender'.$C.'" value="F"' . 
                    (${'Gender'.$C}=='F'?' checked':'') . '>Female 
            <input type="radio" name="Gender'.$C.'" value="M"' .
                    (${'Gender'.$C}=='M'?' checked':'') . '>Male</td> 
    </tr> 
    <tr> 
        <td align="right" style="margin-left: 4pt; margin-right: 4pt" 
                valign="middle" bgcolor="#008080" nowrap>
            <font color="#F7FBFB"> Institution</font>
        </td> 
        <td style="margin-left: 4pt; margin-right: 4pt"></td> 
        <td style="margin-left: 4pt; margin-right: 4pt">
            <input type="text" size="40" maxlength="70" name="Institution'.$C.'"
            value="'.${'Institution'.$C}.'"></td> 
    </tr>
    <tr> 
        <td align="right" style="margin-left: 4pt; margin-right: 4pt"
                valign="middle" bgcolor="#008080">
            <font color="#F7FBFB">Area of Study</font>
        </td> 
        <td style="margin-left: 4pt; margin-right: 4pt"></td> 
        <td style="margin-left: 4pt; margin-right: 4pt">
            <select name="Major'.$C.'" size="1"> 
                <option value="">&nbsp;</option>';
        $majors = array(
                'Business', 
                'Computer Engineering', 
                'Computer Science',
                'Electrical Engineering',
                'Informatics',
                'Information Systems',
                'Mathematics',
                'Other',
                'Physics',
        );
        foreach ($majors as $m) {
            echo '
                <option value="'.$m.'"' . (${'Major'.$C}==$m?' selected':'') .
                        '>'.$m.'</option>';
        }
        echo '
            </select>
        </td> 
    </tr> 
    <tr> 
        <td align="right" style="margin-left: 4pt; margin-right: 4pt"
                valign="middle" bgcolor="#008080">
            <font color="#F7FBFB">Date You First Pursued<br /> 
            Your First Degree</font>
        </td> 
        <td style="margin-left: 4pt; margin-right: 4pt"></td> 
        <td style="margin-left: 4pt; margin-right: 4pt">
            <input type="text" size="10" name="EnteredDate'.$C.'" 
                    value="'.${'EnteredDate'.$C}.'" maxlength="10">
            &nbsp; <em>mm/dd/yyyy, the <b>first</b> day of the <b>first</b>
            term you <b>first</b> began pursuing your <b>first</b> degree from
            <b>any</b> institution of higher education</em>
        </td> 
    </tr> 
    <tr> 
        <td align="right" style="margin-left: 4pt; margin-right: 4pt"
                valign="middle" bgcolor="#008080">
            <font color="#F7FBFB">Expected Date<br> of Graduation</font>
        </td> 
        <td style="margin-left: 4pt; margin-right: 4pt"></td> 
        <td style="margin-left: 4pt; margin-right: 4pt">
            <input type="text" size="10" name="ExpectedGradDate'.$C.'" 
                    value="'.${'ExpectedGradDate'.$C}.'" maxlength="10">
            <em>&nbsp;&nbsp; </em>mm/dd/yyyy
        </td> 
    </tr> 
    <tr> 
        <td align="right" style="margin-left: 4pt; margin-right: 4pt"
                valign="middle" bgcolor="#008080">
            <font color="#F7FBFB">Date of Birth</font>
        </td> 
        <td style="margin-left: 4pt; margin-right: 4pt"></td> 
        <td style="margin-left: 4pt; margin-right: 4pt">
            <input type="text" size="10" name="DateOfBirth'.$C.'" 
                    value="'.${'DateOfBirth'.$C}.'" maxlength="10">
            <em>&nbsp;&nbsp; </em>mm/dd/yyyy
        </td> 
        </tr>';
*/
    }
?>
</table> 
            </td> 
        </tr> 
        <tr> 
            <td align="center"><input type="submit" name="Add" value="Register Team">
        </tr> 
    </table> 
</form> 

<p style="color: #AAAAAA">
    Registrations will be sent to:
</p>

<table>
<?

    foreach ($SITES as $site) {
        echo "
    <tr>
        <td style=\"color: #AAAAAA\">".$SITEINFO[$site]['name']."</td>
        <td style=\"color: #AAAAAA\">".$SITEINFO[$site]['email']."</td>
    </tr>";
    }

?>
</table>
</p>

</body>
</html>
