#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/misc.class.php';

$misc = new Misc('admin');

r($misc->hello()) && p() && e('hello world from hello()<br />'); //简单打印，maybe test function。
?>
