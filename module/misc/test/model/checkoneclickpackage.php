#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/misc.class.php';

$misc = new Misc('admin');

r($misc->checkOneClickPackage()) && p() && e('');
?>
