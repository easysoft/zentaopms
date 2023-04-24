#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/misc.class.php';

$misc = new Misc('admin');

r($misc->hello()) && p() && e('hello world from hello()<br />'); //简单打印，maybe test function。
?>
