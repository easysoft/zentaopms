#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';

/**

title=测试 personnelModel->buildSearchForm();
cid=1
pid=1



*/

$personnel = new personnelTest('admin');

//r($personnel->buildSearchFormTest()) && p() && e();