<?php 
  echo '<?xml version="1.0" encoding="ISO-8859-1"?>'; 
  $current = intval(date("Y"));
  if (!file_exists("ncpc$current")) {
    $current = intval(current - 1);
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">
  <head>
    <title></title>
    <meta http-equiv="refresh" content="0; URL=ncpc<?php echo $current;?>/"/>
  </head>
  <body>
  </body>
</html>
