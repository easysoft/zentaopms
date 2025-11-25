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
timeout=0
cid=19543

- 获取ID为1的关联关系。
 - 第1条的AType属性 @story
 - 第1条的AID属性 @7
 - 第1条的relation属性 @linkedto
 - 第1条的BType属性 @story
 - 第1条的BID属性 @1
- 获取ID为1的关联关系。
 - 第2条的AType属性 @story
 - 第2条的AID属性 @1
 - 第2条的relation属性 @linkedfrom
 - 第2条的BType属性 @story
 - 第2条的BID属性 @7
- 获取ID为1的关联关系。
 - 第3条的AType属性 @story
 - 第3条的AID属性 @8
 - 第3条的relation属性 @linkedto
 - 第3条的BType属性 @story
 - 第3条的BID属性 @2
- 获取ID为1的关联关系。
 - 第7条的AType属性 @requirement
 - 第7条的AID属性 @10
 - 第7条的relation属性 @linkedto
 - 第7条的BType属性 @requirement
 - 第7条的BID属性 @4
- 获取ID为1的关联关系。
 - 第8条的AType属性 @requirement
 - 第8条的AID属性 @4
 - 第8条的relation属性 @linkedfrom
 - 第8条的BType属性 @requirement
 - 第8条的BID属性 @10
- 获取ID为1的关联关系。
 - 第9条的AType属性 @requirement
 - 第9条的AID属性 @11
 - 第9条的relation属性 @linkedto
 - 第9条的BType属性 @requirement
 - 第9条的BID属性 @5

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
