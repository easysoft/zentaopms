#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

$doclib = zenData('doclib');
$doclib->addedBy->range('admin');
$doclib->addedDate->range('`' . date('Y-m-d H:i:s') . '`');
$doclib->gen(1);

/**

title=productModel->createmainlib();
cid=16385
pid=1

*/

$execution = new executionTest('admin');

r($execution->createMainLibTest('-1'))          && p() && e('0');
r($execution->createMainLibTest('0'))           && p() && e('0');
r($execution->createMainLibTest('2'))           && p('project,execution,name,type,main') && e('1,2,迭代主库,execution,1');
r($execution->createMainLibTest('3', 'stage'))  && p('project,execution,name,type,main') && e('1,3,阶段主库,execution,1');
