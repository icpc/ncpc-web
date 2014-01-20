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

    $SITEINFO = array(
        // Denmark
        "aau"     => array("name" => "Aalborg University",
                           "email" => "torp@cs.aau.dk"),
        "au"      => array("name" => "University of Aarhus",
                           "email" => "thomasm@daimi.au.dk"),
        // Finland
        "hut"     => array("name" => "Helsinki University of Technology",
                           "email" => "antti.honkela@hut.fi"),
        // Iceland
        /*
        "hi"      => array("name" => "University of Iceland",
                           "email" => "mmh@hi.is"),
         */
        // Norway
        "himolde" => array("name" => "Molde University College",
                           "email" => "hans.f.nordhaug@himolde.no"),
        "ntnu"    => array("name" => "Norwegian University of Science and Technology",
                           "email" => "nils.grimsmo@idi.ntnu.no"),
        "hist"    => array("name" => "Sør-Trøndelag University College",
                           "email" => "grethe@aitel.hist.no"),
        "uib"     => array("name" => "University of Bergen",
                           "email" => "fredrik.manne@ii.uib.no"),
        "uio"     => array("name" => "University of Oslo",
                           "email" => "erek@ifi.uio.no"),
        "uit"     => array("name" => "University of Tromsø",
                           "email" => "aage@cs.uit.no"),
        "hive"    => array("name" => "Vestfold University College",
                           "email" => "helge.herheim@hive.no"),
        "hiof"    => array("name" => "Østfold University College",
                           "email" => "jan.hoiberg@hiof.no"),
        // Sweden
        "lth"     => array("name" => "Lund Institute of Technology",
                           "email" => "roy.andersson@cs.lth.se"),
        "kth"     => array("name" => "Royal Institute of Technology",
                           "email" => "niemela@nada.kth.se"),
        "liu"     => array("name" => "Linköping University",
                           "email" => "frehe@ida.liu.se"),
        "umu"     => array("name" => "Umeå University",
                           "email" => "hager@cs.umu.se"),
        // Unlisted
        /*
        "unlisted"=> array("name" => "(Unlisted)",
                           "email" => "nils.grimsmo@idi.ntnu.no"),
        */
    );
    $SITES = array_keys($SITEINFO);
    sort($SITES);
?>
