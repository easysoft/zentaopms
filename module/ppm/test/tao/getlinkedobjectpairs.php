#!/usr/bin/env php
<?php

/**

title=测试 mrTao::getLinkedObjectPairs();
timeout=0
cid=17262

- 测试步骤1：查询MR ID为1关联的story对象 @1|4|7
- 测试步骤2：查询MR ID为1关联的task对象 @2|5|8
- 测试步骤3：查询MR ID为1关联的bug对象 @3|6|9
- 测试步骤4：查询不存在的MR ID @0
- 测试步骤5：查询无效MR ID为0 @0
- 测试步骤6：查询MR ID为2但无story关联的情况 @0
- 测试步骤7：使用无效对象类型参数 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

global $tester, $app;
$app->rawModule = 'mr';

// 手动准备测试数据
$db = $tester->loadModel('mr')->dao;
$db->delete()->from(TABLE_RELATION)->where('AType')->eq('mr')->exec();

$testData = array(
    array('product' => 1, 'project' => 0, 'AType' => 'mr', 'AID' => 1, 'AVersion' => 1, 'relation' => 'linkedto', 'BType' => 'story', 'BID' => 1, 'BVersion' => 1, 'extra' => ''),
    array('product' => 1, 'project' => 0, 'AType' => 'mr', 'AID' => 1, 'AVersion' => 1, 'relation' => 'linkedto', 'BType' => 'story', 'BID' => 4, 'BVersion' => 1, 'extra' => ''),
    array('product' => 1, 'project' => 0, 'AType' => 'mr', 'AID' => 1, 'AVersion' => 1, 'relation' => 'linkedto', 'BType' => 'story', 'BID' => 7, 'BVersion' => 1, 'extra' => ''),
    array('product' => 1, 'project' => 0, 'AType' => 'mr', 'AID' => 1, 'AVersion' => 1, 'relation' => 'linkedto', 'BType' => 'task', 'BID' => 2, 'BVersion' => 1, 'extra' => ''),
    array('product' => 1, 'project' => 0, 'AType' => 'mr', 'AID' => 1, 'AVersion' => 1, 'relation' => 'linkedto', 'BType' => 'task', 'BID' => 5, 'BVersion' => 1, 'extra' => ''),
    array('product' => 1, 'project' => 0, 'AType' => 'mr', 'AID' => 1, 'AVersion' => 1, 'relation' => 'linkedto', 'BType' => 'task', 'BID' => 8, 'BVersion' => 1, 'extra' => ''),
    array('product' => 1, 'project' => 0, 'AType' => 'mr', 'AID' => 1, 'AVersion' => 1, 'relation' => 'linkedto', 'BType' => 'bug', 'BID' => 3, 'BVersion' => 1, 'extra' => ''),
    array('product' => 1, 'project' => 0, 'AType' => 'mr', 'AID' => 1, 'AVersion' => 1, 'relation' => 'linkedto', 'BType' => 'bug', 'BID' => 6, 'BVersion' => 1, 'extra' => ''),
    array('product' => 1, 'project' => 0, 'AType' => 'mr', 'AID' => 1, 'AVersion' => 1, 'relation' => 'linkedto', 'BType' => 'bug', 'BID' => 9, 'BVersion' => 1, 'extra' => ''),
    array('product' => 1, 'project' => 0, 'AType' => 'mr', 'AID' => 2, 'AVersion' => 1, 'relation' => 'linkedto', 'BType' => 'task', 'BID' => 10, 'BVersion' => 1, 'extra' => '')
);

foreach($testData as $data) {
    $db->insert(TABLE_RELATION)->data($data)->exec();
}

$mrTest = new mrTaoTest();

r(implode('|', $mrTest->getLinkedObjectPairsTest(1, 'story'))) && p() && e('1|4|7'); // 测试步骤1：查询MR ID为1关联的story对象
r(implode('|', $mrTest->getLinkedObjectPairsTest(1, 'task'))) && p() && e('2|5|8'); // 测试步骤2：查询MR ID为1关联的task对象
r(implode('|', $mrTest->getLinkedObjectPairsTest(1, 'bug'))) && p() && e('3|6|9'); // 测试步骤3：查询MR ID为1关联的bug对象
r($mrTest->getLinkedObjectPairsTest(999, 'story')) && p() && e('0'); // 测试步骤4：查询不存在的MR ID
r($mrTest->getLinkedObjectPairsTest(0, 'story')) && p() && e('0'); // 测试步骤5：查询无效MR ID为0
r($mrTest->getLinkedObjectPairsTest(2, 'story')) && p() && e('0'); // 测试步骤6：查询MR ID为2但无story关联的情况
r($mrTest->getLinkedObjectPairsTest(1, 'invalid_type')) && p() && e('0'); // 测试步骤7：使用无效对象类型参数