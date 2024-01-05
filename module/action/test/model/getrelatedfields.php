#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';
su('admin');

zdTable('action')->config('action')->gen(39);
zdTable('product')->gen(1);
zdTable('project')->config('project')->gen(2);
zdTable('projectproduct')->config('projectproduct')->gen(2);
zdTable('story')->config('story')->gen(5);
zdTable('build')->config('build')->gen(2);
zdTable('projectstory')->config('projectstory')->gen(1);
zdTable('branch')->config('branch')->config(1);
zdTable('case')->config('case')->gen(1);
zdTable('testtask')->config('testtask')->gen(1);
zdTable('doc')->gen(1);
zdTable('repo')->gen(1);
zdTable('task')->gen(1);
zdTable('kanbanlane')->config('kanbanlane')->gen(1);
zdTable('team')->gen(1);
zdTable('module')->config('module')->gen(1);
zdTable('review')->config('review')->gen(1);
zdTable('productplan')->gen(1);

/**

title=测试 actionModel->getRelatedFields();
timeout=0
cid=1

- 测试获取objectType program      ojbectID 1 actionType common                 extra 1  的动态信息
 - 属性product @,0,
 - 属性project @0
 - 属性execution @0
- 测试获取objectType product      objectId 2 actionType extra                  extra 1  的动态信息
 - 属性product @,2,
 - 属性project @0
 - 属性execution @0
- 测试获取objectType project      objectId 3 actionType opened                 extra 1  的动态信息
 - 属性product @,1,
 - 属性project @3
 - 属性execution @0
- 测试获取objectType execution    objectId 4 actionType created                extra 1  的动态信息
 - 属性product @,1,
 - 属性project @0
 - 属性execution @4
- 测试获取objectType story        objectId 5 actionType linked2build           extra 11 的动态信息
 - 属性product @,1,
 - 属性project @0
 - 属性execution @0
- 测试获取objectType story        objectId 6 actionType unlinkedfrombuild      extra 11 的动态信息
 - 属性product @,1,
 - 属性project @0
 - 属性execution @0
- 测试获取objectType story        objectId 7 actionType estimated              extra 11 的动态信息
 - 属性product @,1,
 - 属性project @0
 - 属性execution @0
- 测试获取objectType story        objectId 8 actionType edited                 extra 11 的动态信息
 - 属性product @,2,
 - 属性project @0
 - 属性execution @11
- 测试获取objectType productplan  objectId 9 actionType assigned               extra 1  的动态信息
 - 属性product @,1,
 - 属性project @0
 - 属性execution @0
- 测试获取objectType branch       objectId 10 actionType closed                extra 1  的动态信息
 - 属性product @,41,
 - 属性project @0
 - 属性execution @0
- 测试获取objectType testcase     objectId 11 actionType deleted               extra 1  的动态信息
 - 属性product @,1,
 - 属性project @~~
 - 属性execution @101
- 测试获取objectType case         objectId 12 actionType deletedfile           extra 1  的动态信息
 - 属性product @,1,
 - 属性project @~~
 - 属性execution @101
- 测试获取objectType testtask     objectId 13 actionType linked2testtask       extra 11 的动态信息
 - 属性product @,2,
 - 属性project @12
 - 属性execution @0
- 测试获取objectType testtask     objectId 14 actionType unlinkedfromtesttask  extra 11 的动态信息
 - 属性product @,1,
 - 属性project @11
 - 属性execution @101
- 测试获取objectType testtask     objectId 15 actionType assigned              extra 11 的动态信息
 - 属性product @,1,
 - 属性project @11
 - 属性execution @101
- 测试获取objectType doc          objectId 16 actionType run                   extra 1  的动态信息
 - 属性product @,1,
 - 属性project @0
 - 属性execution @0
- 测试获取objectType repo         objectId 17 actionType commented             extra 1  的动态信息
 - 属性product @,0,
 - 属性project @0
 - 属性execution @0
- 测试获取objectType release      objectId 18 actionType activated             extra 1  的动态信息
 - 属性product @,1,
 - 属性project @11
 - 属性execution @0
- 测试获取objectType task         objectId 19 actionType blocked               extra 11 的动态信息
 - 属性product @,1,
 - 属性project @11
 - 属性execution @101
- 测试获取objectType kanbanlane   objectId 20 actionType moved                 extra 1 的动态信息
 - 属性product @,0,
 - 属性project @~~
 - 属性execution @1
- 测试获取objectType kanbancolumn objectId 21 actionType confirmed             extra 1 的动态信息
 - 属性product @,0,
 - 属性project @~~
 - 属性execution @1
- 测试获取objectType team         objectId 22 actionType caseconfirmed         extra 11 的动态信息
 - 属性product @,0,
 - 属性project @0
 - 属性execution @11
- 测试获取objectType whitelist    objectId 23 actionType bugconfirmed          extra 1  的动态信息
 - 属性product @,1,
 - 属性project @0
 - 属性execution @0
- 测试获取objectType module       objectId 24 actionType deleted               extra 1  的动态信息
 - 属性product @,1,
 - 属性project @0
 - 属性execution @0
- 测试获取objectType review       objectId 25 actionType started               extra 1  的动态信息
 - 属性product @,0,
 - 属性project @1
 - 属性execution @0
- 测试获取objectType story        objectId 26 actionType unlinkedfromproject   extra 11 的动态信息
 - 属性product @,1,
 - 属性project @1
 - 属性execution @0
- 测试获取objectType story        objectId 27 actionType linked2project        extra 11 的动态信息
 - 属性product @,1,
 - 属性project @1
 - 属性execution @0
- 测试获取objectType story        objectId 28 actionType unlinkedfromexecution extra 11 的动态信H息H
 - 属性product @,1,
 - 属性project @~~
 - 属性execution @33
- 测试获取objectType story        objectId 29 actionType linked2execution      extra 11 的动态信息
 - 属性product @,1,
 - 属性project @~~
 - 属性execution @1
- 测试获取objectType story        objectId 30 actionType linked2kanban         extra 1  的动态信息
 - 属性product @,1,
 - 属性project @~~
 - 属性execution @1

*/

$objectType = array('program', 'product', 'project', 'execution', 'story', 'story', 'story', 'story', 'productplan', 'branch', 'testcase', 'case', 'build', 'bug', 'testtask', 'doc', 'repo', 'release', 'task', 'kanbanlane', 'kanbancolumn', 'team', 'whitelist', 'module', 'review', 'story', 'story', 'story', 'story', 'story');
$objectId   = array(1,2,3,4,5,6,7,8,1,1,1,1,11,1,1,1,1,1,1,1,1,11,1,1,1,1,1,1,1,1,1,1,1,35,36,37,38,39);
$actionType = array('common', 'extra', 'opened', 'created', 'linked2build', 'unlinkedfrombuild', 'estimated', 'edited', 'assigned', 'closed', 'deleted', 'deletedfile', 'linked2testtask', 'unlinkedfromtesttask', 'assigned', 'run', 'commented', 'activated', 'blocked', 'moved', 'confirmed', 'caseconfirmed', 'bugconfirmed', 'deleted', 'started', 'unlinkedfromproject', 'linked2project', 'unlinkedfromexecution', 'linked2execution', 'linked2kanban');
$extra      = array('1', '1', '1', '1', '11', '11', '11', '11', '1', '1', '1', '1', '11', '1', '1', '1', '1', '1', '1', '1', '1', '1', 'product', '1', '1', '1', '1', '33', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');

$action = new actionTest();

r($action->getRelatedFieldsTest($objectType[0],  $objectId[0],  $actionType[0],  $extra[0]))  && p('product|project|execution', '|') && e(',0,|0|0');     // 测试获取objectType program      ojbectID 1 actionType common                 extra 1  的动态信息
r($action->getRelatedFieldsTest($objectType[1],  $objectId[1],  $actionType[1],  $extra[1]))  && p('product|project|execution', '|') && e(',2,|0|0');     // 测试获取objectType product      objectId 2 actionType extra                  extra 1  的动态信息
r($action->getRelatedFieldsTest($objectType[2],  $objectId[2],  $actionType[2],  $extra[2]))  && p('product|project|execution', '|') && e(',1,|3|0');     // 测试获取objectType project      objectId 3 actionType opened                 extra 1  的动态信息
r($action->getRelatedFieldsTest($objectType[3],  $objectId[3],  $actionType[3],  $extra[3]))  && p('product|project|execution', '|') && e(',1,|0|4');     // 测试获取objectType execution    objectId 4 actionType created                extra 1  的动态信息
r($action->getRelatedFieldsTest($objectType[4],  $objectId[4],  $actionType[4],  $extra[4]))  && p('product|project|execution', '|') && e(',1,|0|0');     // 测试获取objectType story        objectId 5 actionType linked2build           extra 11 的动态信息
r($action->getRelatedFieldsTest($objectType[5],  $objectId[5],  $actionType[5],  $extra[5]))  && p('product|project|execution', '|') && e(',1,|0|0');     // 测试获取objectType story        objectId 6 actionType unlinkedfrombuild      extra 11 的动态信息
r($action->getRelatedFieldsTest($objectType[6],  $objectId[6],  $actionType[6],  $extra[6]))  && p('product|project|execution', '|') && e(',1,|0|0');     // 测试获取objectType story        objectId 7 actionType estimated              extra 11 的动态信息
r($action->getRelatedFieldsTest($objectType[7],  $objectId[7],  $actionType[7],  $extra[7]))  && p('product|project|execution', '|') && e(',2,|0|11');    // 测试获取objectType story        objectId 8 actionType edited                 extra 11 的动态信息
r($action->getRelatedFieldsTest($objectType[8],  $objectId[8],  $actionType[8],  $extra[8]))  && p('product|project|execution', '|') && e(',1,|0|0');     // 测试获取objectType productplan  objectId 9 actionType assigned               extra 1  的动态信息
r($action->getRelatedFieldsTest($objectType[9],  $objectId[9],  $actionType[9],  $extra[9]))  && p('product|project|execution', '|') && e(',41,|0|0');    // 测试获取objectType branch       objectId 10 actionType closed                extra 1  的动态信息
r($action->getRelatedFieldsTest($objectType[10], $objectId[10], $actionType[10], $extra[10])) && p('product|project|execution', '|') && e(',1,|~~|101');  // 测试获取objectType testcase     objectId 11 actionType deleted               extra 1  的动态信息
r($action->getRelatedFieldsTest($objectType[11], $objectId[11], $actionType[11], $extra[11])) && p('product|project|execution', '|') && e(',1,|~~|101');  // 测试获取objectType case         objectId 12 actionType deletedfile           extra 1  的动态信息
r($action->getRelatedFieldsTest($objectType[12], $objectId[12], $actionType[12], $extra[12])) && p('product|project|execution', '|') && e(',2,|12|0');    // 测试获取objectType testtask     objectId 13 actionType linked2testtask       extra 11 的动态信息
r($action->getRelatedFieldsTest($objectType[13], $objectId[13], $actionType[13], $extra[13])) && p('product|project|execution', '|') && e(',1,|11|101');  // 测试获取objectType testtask     objectId 14 actionType unlinkedfromtesttask  extra 11 的动态信息
r($action->getRelatedFieldsTest($objectType[14], $objectId[14], $actionType[14], $extra[14])) && p('product|project|execution', '|') && e(',1,|11|101');  // 测试获取objectType testtask     objectId 15 actionType assigned              extra 11 的动态信息
r($action->getRelatedFieldsTest($objectType[15], $objectId[15], $actionType[15], $extra[15])) && p('product|project|execution', '|') && e(',1,|0|0');     // 测试获取objectType doc          objectId 16 actionType run                   extra 1  的动态信息
r($action->getRelatedFieldsTest($objectType[16], $objectId[16], $actionType[16], $extra[16])) && p('product|project|execution', '|') && e(',0,|0|0');     // 测试获取objectType repo         objectId 17 actionType commented             extra 1  的动态信息
r($action->getRelatedFieldsTest($objectType[17], $objectId[17], $actionType[17], $extra[17])) && p('product|project|execution', '|') && e(',1,|11|0');    // 测试获取objectType release      objectId 18 actionType activated             extra 1  的动态信息
r($action->getRelatedFieldsTest($objectType[18], $objectId[18], $actionType[18], $extra[18])) && p('product|project|execution', '|') && e(',1,|11|101');  // 测试获取objectType task         objectId 19 actionType blocked               extra 11 的动态信息
r($action->getRelatedFieldsTest($objectType[19], $objectId[19], $actionType[19], $extra[19])) && p('product|project|execution', '|') && e(',0,|~~|1');    // 测试获取objectType kanbanlane   objectId 20 actionType moved                 extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[20], $objectId[20], $actionType[20], $extra[20])) && p('product|project|execution', '|') && e(',0,|~~|1');    // 测试获取objectType kanbancolumn objectId 21 actionType confirmed             extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[21], $objectId[21], $actionType[21], $extra[21])) && p('product|project|execution', '|') && e(',0,|0|11');   // 测试获取objectType team         objectId 22 actionType caseconfirmed         extra 11 的动态信息
r($action->getRelatedFieldsTest($objectType[22], $objectId[22], $actionType[22], $extra[22])) && p('product|project|execution', '|') && e(',1,|0|0');     // 测试获取objectType whitelist    objectId 23 actionType bugconfirmed          extra 1  的动态信息
r($action->getRelatedFieldsTest($objectType[23], $objectId[23], $actionType[23], $extra[23])) && p('product|project|execution', '|') && e(',1,|0|0');     // 测试获取objectType module       objectId 24 actionType deleted               extra 1  的动态信息
r($action->getRelatedFieldsTest($objectType[24], $objectId[24], $actionType[24], $extra[24])) && p('product|project|execution', '|') && e(',0,|1|0');     // 测试获取objectType review       objectId 25 actionType started               extra 1  的动态信息
r($action->getRelatedFieldsTest($objectType[25], $objectId[25], $actionType[25], $extra[25])) && p('product|project|execution', '|') && e(',1,|1|0');     // 测试获取objectType story        objectId 26 actionType unlinkedfromproject   extra 11 的动态信息
r($action->getRelatedFieldsTest($objectType[26], $objectId[26], $actionType[26], $extra[26])) && p('product|project|execution', '|') && e(',1,|1|0');     // 测试获取objectType story        objectId 27 actionType linked2project        extra 11 的动态信息
r($action->getRelatedFieldsTest($objectType[27], $objectId[27], $actionType[27], $extra[27])) && p('product|project|execution', '|') && e(',1,|~~|33');   // 测试获取objectType story        objectId 28 actionType unlinkedfromexecution extra 11 的动态信H息H
r($action->getRelatedFieldsTest($objectType[28], $objectId[28], $actionType[28], $extra[28])) && p('product|project|execution', '|') && e(',1,|~~|1');    // 测试获取objectType story        objectId 29 actionType linked2execution      extra 11 的动态信息
r($action->getRelatedFieldsTest($objectType[29], $objectId[29], $actionType[29], $extra[29])) && p('product|project|execution', '|') && e(',1,|~~|1');    // 测试获取objectType story        objectId 30 actionType linked2kanban         extra 1  的动态信息