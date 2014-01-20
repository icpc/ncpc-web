<?
    $DBHANDLE = new PDO('sqlite:registrations.sqlite3');
    $OTHER_EMAIL = 'frehe@ida.liu.se';

    function _p($obj) {
        echo "<pre>";
        print_r($obj);
        echo "</pre>";
    }

    function _die($reason) {
        echo "</p></pre></tr></td></table>";
        die("FATAL ERROR: $reason");
    }

    function rjust($str, $len) {
        return str_pad($str, $len, ' ', STR_PAD_LEFT);
        $ret = '';
        for ($i = 0; $i < $len - strlen($str); $i++) {
            $ret .= ' ';
        }
        $ret .= $str; 
        return $ret;
    }
    
    function ljust($str, $len) {
        return str_pad($str, $len, ' ', STR_PAD_RIGHT);
        $ret = $str; 
        for ($i = 0; $i < $len - strlen($str); $i++) {
            $ret .= ' ';
        }
        return $ret;
    }

    function dbQuery($query) {
        global $DBHANDLE;
        $res = $DBHANDLE->query($query);
        if ($res === FALSE) {
            _p("Database query failed:");
            _p(preg_replace("/\n\\s+/", "\n", $query));
            _die("Dying.");
        }
        $rows = array();
        foreach ($res as $row) {
            $rows[] = $row;
        }
        return $rows;
    }

    function quoteForDB($str) {
        global $DBHANDLE;
        return $DBHANDLE->quote($str);
    }

    function formToForm($str) {
        $str = trim($str);
        $str = preg_replace('/\\\\\\\\/', '\\', $str);
        $str = preg_replace('/\\\\"/', '"', $str);
        $str = preg_replace("/\\\\'/", "'", $str);
        return $str;
    }

    function escapeHTML($str) {
        $str = htmlspecialchars($str);
        return $str;
    }

    function _dbcreate($name, $def) {
        return "CREATE TABLE $name ($def)";
    }

    $CHECKS = array(
            'TeamName'          => array('name',   4, 30, true),
            'Eliglible'         => array('radio',  0, 30, true),
            'AffiliationID'     => array('siteid', 0, 30, true),
            'AffiliationOther'  => array('name',   0, 30, false),
            'SiteID'            => array('siteid', 0, 30, true),
    );
    $MEMBER_CHECKS = array(
            'FirstMiddle'       => array('name',   1, 30, true),
            'LastName'          => array('name',   1, 30, true),
            'Email'             => array('email',  3, 70, true),
            'CEmail'            => array('email',  3, 70, true),
            'AffiliationID'     => array('siteid', 0, 30, true),
            'AffiliationOther'  => array('name',   0, 30, false),
            'HaveKattisLogin'   => array('radio',  0, 30, true),
            'KattisLogin'       => array('name',   0, 30, false),
    );
    foreach (array(1,2,3) as $C) {
        foreach ($MEMBER_CHECKS as $k => $v) {
                $CHECKS["$k$C"] = $v;
        }
    }
    $FIELD_NAMES = array_keys($CHECKS);
    $PATTERNS = array(
            'name'   => '/^(.*)$/',
            'login'  => '/^(\w*)$/',
            'siteid' => '/^(\w+(\.\w+)?)$/',
            'email'  => '/^(.*)$/',
            //   '/^([\w\-]+([\.\+][\w\-]+)*@[\w\-]+(\.[\w\-]+)*\.\w+)$/',
            'radio'  => '/^(yes|no)$/',
    );

    $DB_CHECK = TRUE;
    $DB_FIX = FALSE;
    
    $DB_TABLES = array(
        'teams' => "\n    " . ljust("id", 20) . "INTEGER PRIMARY KEY"
    );
    foreach ($FIELD_NAMES as $f) {
        $DB_TABLES['teams'] .= ",\n    " . ljust($f, 20) . 
                "VARCHAR({$CHECKS[$f][2]}) DEFAULT '' NOT NULL";
    }

    $DB_TABLES['teams'] .= ",\n    " . ljust('Country', 20) . 
                "VARCHAR(30) DEFAULT '' NOT NULL";
                
    foreach ($DB_TABLES as $name => $def) {
        $q = "SELECT * FROM sqlite_master WHERE name='$name'";
        $r = dbQuery($q);
        if (count($r) == 0) {
            dbQuery(_dbcreate($name, $def));
        }
        else if ($DB_CHECK && $r[0]['sql'] != _dbcreate($name, $def)) {
            echo "
                <p>
                    Wrong definition of table $name:
                </p>
                <pre>'".$r[0]['sql']."' != '\n"._dbcreate($name, $def)."'</pre>";
            if ($DB_FIX) {
                dbQuery("DROP table $name");
                dbQuery(_dbcreate($name, $def));
            }
            else {
                die("Giving up...");
            }
        }
    }

    $SCHOOLINFO = array(
        // Denmark
        'aau'      => array('country' => 'dk',
                            'nosite' => TRUE,
                            'name' => 'Aalborg University',
                            'email' => $OTHER_EMAIL),
        'au'       => array('country' => 'dk',
                            'name' => 'University of Aarhus',
                            'email' => 'bjarke@imf.au.dk'),
        'diku'     => array('country' => 'dk',
                            'nosite' => TRUE,
                            'name' => 'University of Copenhagen',
                            'email' => $OTHER_EMAIL),
        'dk.other' => array('country' => 'dk',
                            'nosite' => TRUE,
                            'name' => 'Other',
                            'email' => $OTHER_EMAIL),
        // Finland
        'hut'      => array('country' => 'fi',
                            'name' => 'Helsinki University of Technology',
                            'email' => 'harri.haanpaa@tkk.fi'),
        'fi.other' => array('country' => 'fi',
                            'nosite' => TRUE,
                            'name' => 'Other',
                            'email' => $OTHER_EMAIL),
        // Iceland
        'ru'       => array('country' => 'is',
                            'nosite' => TRUE,
                            'name' => 'Reykjavik University',
                            'email' => $OTHER_EMAIL),
        'hi'       => array('country' => 'is',
                            'nosite' => TRUE,
                            'name' => 'University of Iceland',
                            'email' => $OTHER_EMAIL),
        'is.other' => array('country' => 'is',
                            'nosite' => TRUE,
                            'name' => 'Other',
                            'email' => $OTHER_EMAIL),
        // Norway
        'himolde'  => array('country' => 'no',
                            'name' => 'Molde University College',
                            'email' => 'hans.f.nordhaug@himolde.no'),
        'ntnu'     => array('country' => 'no',
                            'name' => 'Norwegian University of Science and Technology',
                            'email' => 'houeland@idi.ntnu.no'),
        'hist'     => array('country' => 'no',
                            'nosite' => TRUE,
                            'name' => 'Sør-Trøndelag University College',
                            'email' => 'grethe@aitel.hist.no'),
        'uib'      => array('country' => 'no',
                            'name' => 'University of Bergen',
                            'email' => 'fredrik.manne@ii.uib.no'),
        'uio'      => array('country' => 'no',
                            'name' => 'University of Oslo',
                            'email' => 'jarleso@ifi.uio.no'),
        'uit'      => array('country' => 'no',
                            'name' => 'University of Tromsø',
                            'email' => 'aage@cs.uit.no'),
        'hive'     => array('country' => 'no',
                            'name' => 'Vestfold University College',
                            'email' => 'helge.herheim@hive.no'),
        'hiof'     => array('country' => 'no',
                            'name' => 'Østfold University College',
                            'email' => 'jan.hoiberg@hiof.no'),
        'no.other' => array('country' => 'no',
                            'nosite' => TRUE,
                            'name' => 'Other',
                            'email' => $OTHER_EMAIL),
        // Sweden
        'chal'     => array('country' => 'se',
                            'name' => 'Chalmers Tekniska Högskola',
                            'email' => 'maris@math.chalmers.se'),
        'liu'      => array('country' => 'se',
                            'name' => 'Linköping University',
                            'email' => 'frehe@ida.liu.se'),
        'lth'      => array('country' => 'se',
                            'name' => 'Lund Institute of Technology',
                            'email' => 'roy.andersson@cs.lth.se'),
        'kth'      => array('country' => 'se',
                            'name' => 'Royal Institute of Technology',
                            'email' => 'niemela@nada.kth.se'),
        'umu'      => array('country' => 'se',
                            'name' => 'Umeå University',
                            'email' => 'hager@cs.umu.se'),
        'se.other' => array('country' => 'se',
                            'nosite' => TRUE,
                            'name' => 'Other',
                            'email' => $OTHER_EMAIL),
    );

    $COUNTRIES = array(
        'dk'    => 'Denmark',
        'fi'    => 'Finland',
        'is'    => 'Iceland',
        'no'    => 'Norway',
        'se'    => 'Sweden',
        //'multi' => 'Multiple countries',
        //'none'  => '',
    );

    $SCHOOLS_ALL = array_keys($SCHOOLINFO);
    sort($SCHOOLS_ALL);

    $SCHOOLS_BY_COUNTRY = array();
    foreach ($COUNTRIES as $k => $v) {
        $SCHOOLS_BY_COUNTRY[$k] = array();
    }
    foreach ($SCHOOLINFO as $k => $v) {
        $SCHOOLS_BY_COUNTRY[$v['country']][] = $k;
    }

    function affName($aff) {
        global $SCHOOLINFO, $COUNTRIES;
        if ($aff === 'fromteamdef') {
            return '(Same as team)';
        } else {
            $c = $COUNTRIES[$SCHOOLINFO[$aff]['country']];
            $s = $SCHOOLINFO[$aff]['name'];
            if ($c !== '') return "$c: $s";
            else           return $s;
        }
    }
?>
