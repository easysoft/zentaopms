#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) .'/lib/init.php';
include dirname(dirname(dirname(__FILE__))) .'/class/misc.class.php';

$misc = new Misc('admin');

r($misc->getRemind()) && p() && e('0'); //调用方法返回值
?>
