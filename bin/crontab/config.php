<?php
$crontab = new stdClass();
$crontab->backup      = array('schema' => '* * * * *',  'script' => '../php/backup.php');
$crontab->computeburn = array('schema' => '1 23 * * *', 'script' => '../php/computeburn.php');
