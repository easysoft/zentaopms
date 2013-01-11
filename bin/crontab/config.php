<?php
$crontab = new stdClass();
$crontab->backup      = array('schema' => '1 1 * * *',  'script' => '../php/backup.php');
$crontab->computeburn = array('schema' => '1 23 * * *', 'script' => '../php/computeburn.php');
