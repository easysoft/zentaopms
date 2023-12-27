#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=测试 commonTao->searchPreAndNextFromList();
cid=1
pid=1

*/

global $tester;
$tester->loadModel('common');

$objectList = array('sql' => 'SELECT * FROM `zt_story` WHERE id < 5', 'idkey' => 'id', 'objectList' => array(1 => 1, 2 => 2, 3 => 3, 4 => 4));

r(1) && p() && e(1);
r((array)$tester->common->searchPreAndNextFromList(0, array())) && p('pre,next') && e('~~,~~');
r((array)$tester->common->searchPreAndNextFromList(3, array())) && p('pre,next') && e('~~,~~');
r((array)$tester->common->searchPreAndNextFromList(3, $objectList)) && p('pre,next') && e('2,4');
