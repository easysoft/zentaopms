#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::getProductPairs();
cid=1
pid=1


*/

$program = $tester->loadModel('program');
r($program->getProductPairs('1', 'assign', 'all')) && p() && e(''); // 
