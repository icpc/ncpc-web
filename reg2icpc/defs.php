<?
    $RDATA = 'registrations.data';

    function pr($obj)
    {
        echo "<pre>";
        print_r($obj);
        echo "</pre>";
    }

    function tohtml($str) 
    {
        $str = trim($str);
        $str = preg_replace('/&/', '&amp;', $str);
        $str = preg_replace('/\\\"/', '&quot;', $str);
        $str = preg_replace("/\\\\'/", '&#039;', $str);
        $str = preg_replace('/</', '&lt;', $str);
        $str = preg_replace('/>/', '&gt;', $str);
        return $str;
    }

    $siteinfo = array(
        "aau"     => array("name" => "Aalborg University",
                           "email" => "torp@cs.aau.dk"),
        "liu"     => array("name" => "Linköping University",
                           "email" => "frehe@ida.liu.se"),
        "lth"     => array("name" => "Lund Institute of Technology",
                           "email" => "roy.andersson@cs.lth.se"),
        "himolde" => array("name" => "Molde University College",
                           "email" => "hans.f.nordhaug@himolde.no"),
        "ntnu"    => array("name" => "Norwegian University of Science and Technology",
                           "email" => "nils.grimsmo@idi.ntnu.no"),
        "kth"     => array("name" => "Royal Institute of Technology",
                           "email" => "niemela@nada.kth.se"),
        "hist"    => array("name" => "Sør-Trøndelag University College",
                           "email" => "grethe@aitel.hist.no"),
        "umu"     => array("name" => "Umeå University",
                           "email" => "hager@cs.umu.se"),
        "au"      => array("name" => "University of Aarhus",
                           "email" => "arnsfelt@daimi.au.dk"),
        "uib"     => array("name" => "University of Bergen",
                           "email" => "fredrik.manne@ii.uib.no"),
        "hi"      => array("name" => "University of Iceland",
                           "email" => "mmh@hi.is"),
        "uio"     => array("name" => "University of Oslo",
                           "email" => "erek@ifi.uio.no"),
        "uit"     => array("name" => "University of Tromsø",
                           "email" => "nils.grimsmo@idi.ntnu.no"),
        "hive"    => array("name" => "Vestfold University College",
                           "email" => "nils.grimsmo@idi.ntnu.no"),
        "hiof"    => array("name" => "Østfold University College",
                           "email" => "jan.hoiberg@hiof.no"),
    );
    $sites = array_keys($siteinfo);
?>
