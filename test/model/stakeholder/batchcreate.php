#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/stakeholder.class.php';
su('admin');

/**

title=测试 stakeholderModel->batchCreate();
cid=1
pid=1

 >> 6
 >> 6
 >> 6
 >> 6

*/
$projectIDList = array('11', '31', '61');
$realnames     = array('开发38', '开发29', '开发30');
$accounts      = array('test28', 'test29', 'test30', 'dev1', 'dev13', 'dev12');

$normalStakeholder = array('realnames' => $realnames, 'accounts' => $accounts);
$noRealnames       = array('accounts' => $accounts);

$stakeholder = new stakeholderTest();
r(count($stakeholder->batchCreateTest($projectIDList[0], $normalStakeholder))) && p() && e('6');
r(count($stakeholder->batchCreateTest($projectIDList[1], $normalStakeholder))) && p() && e('6');
r(count($stakeholder->batchCreateTest($projectIDList[2], $normalStakeholder))) && p() && e('6');
r(count($stakeholder->batchCreateTest($projectIDList[0], $noRealnames)))       && p() && e('6');

