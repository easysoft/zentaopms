#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

$result = zenData('relation')->gen(0);

$result = zenData('story');
$result->linkStories->range('0{6},1-3,0{3}');
$result->linkRequirements->range('0{9},4-6');
$result->gen(12);

/**

title=upgradeModel->processLinkStories();
cid=1
pid=1

*/

global $tester;
$upgrade = $tester->loadModel('upgrade');
$upgrade->processLinkStories();
$relations = $upgrade->dao->select('*')->from(TABLE_RELATION)->fetchAll('id');
r($relations) && p('1:AType,AID,relation,BType,BID') && e('story,7,linkedto,story,1');                // 获取ID为1的关联关系。
r($relations) && p('2:AType,AID,relation,BType,BID') && e('story,1,linkedfrom,story,7');              // 获取ID为1的关联关系。
r($relations) && p('3:AType,AID,relation,BType,BID') && e('story,8,linkedto,story,2');                // 获取ID为1的关联关系。
r($relations) && p('7:AType,AID,relation,BType,BID') && e('requirement,10,linkedto,requirement,4');   // 获取ID为1的关联关系。
r($relations) && p('8:AType,AID,relation,BType,BID') && e('requirement,4,linkedfrom,requirement,10'); // 获取ID为1的关联关系。
r($relations) && p('9:AType,AID,relation,BType,BID') && e('requirement,11,linkedto,requirement,5');   // 获取ID为1的关联关系。
