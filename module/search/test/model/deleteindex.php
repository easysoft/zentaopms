#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';
su('admin');

zdTable('searchindex')->gen(6);

/**

title=测试 searchModel->deleteIndex();
timeout=0
cid=1

- 测试删除ID为1的数据后剩余的数量 @0
- 测试删除ID为2的数据后剩余的数量 @0
- 测试删除ID为3的数据后剩余的数量 @0
- 测试删除ID为4的数据后剩余的数量 @0
- 测试删除ID为5的数据后剩余的数量 @0

*/

$search = new searchTest();

$objectType   = 'project';
$objectIDList = array(1, 2, 3, 4, 5);

r($search->deleteIndexTest('project', $objectIDList[0])) && p() && e('0'); //测试删除ID为1的数据后剩余的数量
r($search->deleteIndexTest('project', $objectIDList[1])) && p() && e('0'); //测试删除ID为2的数据后剩余的数量
r($search->deleteIndexTest('project', $objectIDList[2])) && p() && e('0'); //测试删除ID为3的数据后剩余的数量
r($search->deleteIndexTest('project', $objectIDList[3])) && p() && e('0'); //测试删除ID为4的数据后剩余的数量
r($search->deleteIndexTest('project', $objectIDList[4])) && p() && e('0'); //测试删除ID为5的数据后剩余的数量