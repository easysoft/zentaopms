#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/action.class.php';
su('admin');

/**

title=测试 actionModel->getRelatedFields();
cid=1
pid=1

测试获取objectType product objectId 1 actionType common extra 1 的动态信息 >> ,1,;0;0
测试获取objectType story objectId 2 actionType extra extra 1 的动态信息 >> ,1,;11;101
测试获取objectType productplan objectId 3 actionType opened extra 1 的动态信息 >> ,1,;0;0
测试获取objectType release objectId 4 actionType created extra 1 的动态信息 >> ,41,;14;0
测试获取objectType project objectId 5 actionType changed extra 11 的动态信息 >> ,,;5;0
测试获取objectType task objectId 6 actionType edited extra 1 的动态信息 >> ,6,;16;106
测试获取objectType build objectId 7 actionType assigned extra 1 的动态信息 >> ,7,;17;0
测试获取objectType bug objectId 8 actionType closed extra 1 的动态信息 >> ,3,;13;103
测试获取objectType testcase objectId 9 actionType deleted extra 1 的动态信息 >> ,3,;13;103
测试获取objectType case objectId 10 actionType deletedfile extra 1 的动态信息 >> ,3,;13;103
测试获取objectType testtask objectId 1 actionType editfile extra 1 的动态信息 >> ,1,;11;101
测试获取objectType user objectId 12 actionType erased extra 1 的动态信息 >> ,0,;0;0
测试获取objectType doc objectId 13 actionType undeleted extra 1 的动态信息 >> ,13,;0;0
测试获取objectType doclib objectId 14 actionType hidden extra 1 的动态信息 >> ,14,;0;0
测试获取objectType todo objectId 15 actionType commented extra 1 的动态信息 >> ,0,;0;0
测试获取objectType branch objectId 16 actionType activated extra 1 的动态信息 >> ,0,;0;0
测试获取objectType module objectId 17 actionType blocked extra 1 的动态信息 >> ,,0,,;0;0
测试获取objectType testsuite objectId 18 actionType moved extra 1 的动态信息 >> ,0,;0;0
测试获取objectType caselib objectId 19 actionType confirmed extra 1 的动态信息 >> ,0,;0;0
测试获取objectType testreport objectId 20 actionType caseconfirmed extra 1 的动态信息 >> ,,;0;0
测试获取objectType entry objectId 21 actionType bugconfirmed extra 1 的动态信息 >> ,0,;0;0
测试获取objectType webhook objectId 22 actionType frombug extra 1 的动态信息 >> ,0,;0;0
测试获取objectType review objectId 23 actionType started extra 1 的动态信息 >> ,0,;0;0
测试获取objectType product objectId 24 actionType restarted extra 1 的动态信息 >> ,24,;0;0
测试获取objectType story objectId 25 actionType delayed extra 1 的动态信息 >> ,7,;0;0
测试获取objectType productplan objectId 26 actionType suspended extra 1 的动态信息 >> ,9,;0;0
测试获取objectType release objectId 1 actionType recordestimate extra 1 的动态信息 >> ,1,;11;0
测试获取objectType project objectId 28 actionType editestimate extra 33 的动态信息 >> ,8,18,98,;28;0
测试获取objectType task objectId 29 actionType deleteestimate extra 1 的动态信息 >> ,29,;39;129
测试获取objectType build objectId 1 actionType canceled extra 1 的动态信息 >> ,1,;11;0
测试获取objectType bug objectId 31 actionType svncommited extra 1 的动态信息 >> ,11,;21;111
测试获取objectType testcase objectId 32 actionType gitcommited extra 1 的动态信息 >> ,8,;18;108
测试获取objectType case objectId 33 actionType finished extra 1 的动态信息 >> ,9,;19;109
测试获取objectType testtask objectId 1 actionType paused extra 1 的动态信息 >> ,1,;11;101
测试获取objectType user objectId 35 actionType verified extra 1 的动态信息 >> ,0,;0;0
测试获取objectType doc objectId 36 actionType diff1 extra 1 的动态信息 >> ,36,;0;0
测试获取objectType doclib objectId 37 actionType diff2 extra 1 的动态信息 >> ,37,;0;0
测试获取objectType todo objectId 38 actionType diff3 extra 1 的动态信息 >> ,0,;0;0
测试获取objectType branch objectId 39 actionType linked2bug extra 1 的动态信息 >> ,0,;0;0

*/
$objectType = array('product', 'story', 'productplan', 'release', 'project', 'task', 'build', 'bug', 'testcase', 'case', 'testtask', 'user', 'doc', 'doclib', 'todo', 'branch', 'module', 'testsuite', 'caselib', 'testreport', 'entry', 'webhook', 'review');
$objectId   = array(1,2,3,4,5,6,7,8,9,10,1,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,1,28,29,1,31,32,33,1,35,36,37,38,39);
$actionType = array('common', 'extra', 'opened', 'created', 'changed', 'edited', 'assigned', 'closed', 'deleted', 'deletedfile', 'editfile', 'erased', 'undeleted', 'hidden', 'commented', 'activated', 'blocked', 'moved', 'confirmed', 'caseconfirmed', 'bugconfirmed', 'frombug', 'started', 'restarted', 'delayed', 'suspended', 'recordestimate', 'editestimate', 'deleteestimate', 'canceled', 'svncommited', 'gitcommited', 'finished', 'paused', 'verified', 'diff1', 'diff2', 'diff3', 'linked2bug');
$extra      = array('1', '1', '1', '1', '11', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '33', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');

$action = new actionTest();

r($action->getRelatedFieldsTest($objectType[0],  $objectId[0],  $actionType[0],  $extra[0]))  && p('product;project;execution') && e(',1,;0;0');        // 测试获取objectType product objectId 1 actionType common extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[1],  $objectId[1],  $actionType[1],  $extra[1]))  && p('product;project;execution') && e(',1,;11;101');     // 测试获取objectType story objectId 2 actionType extra extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[2],  $objectId[2],  $actionType[2],  $extra[2]))  && p('product;project;execution') && e(',1,;0;0');        // 测试获取objectType productplan objectId 3 actionType opened extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[3],  $objectId[3],  $actionType[3],  $extra[3]))  && p('product;project;execution') && e(',41,;14;0');      // 测试获取objectType release objectId 4 actionType created extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[4],  $objectId[4],  $actionType[4],  $extra[4]))  && p('product;project;execution') && e(',,;5;0');         // 测试获取objectType project objectId 5 actionType changed extra 11 的动态信息
r($action->getRelatedFieldsTest($objectType[5],  $objectId[5],  $actionType[5],  $extra[5]))  && p('product;project;execution') && e(',6,;16;106');     // 测试获取objectType task objectId 6 actionType edited extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[6],  $objectId[6],  $actionType[6],  $extra[6]))  && p('product;project;execution') && e(',7,;17;0');       // 测试获取objectType build objectId 7 actionType assigned extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[7],  $objectId[7],  $actionType[7],  $extra[7]))  && p('product;project;execution') && e(',3,;13;103');     // 测试获取objectType bug objectId 8 actionType closed extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[8],  $objectId[8],  $actionType[8],  $extra[8]))  && p('product;project;execution') && e(',3,;13;103');     // 测试获取objectType testcase objectId 9 actionType deleted extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[9],  $objectId[9],  $actionType[9],  $extra[9]))  && p('product;project;execution') && e(',3,;13;103');     // 测试获取objectType case objectId 10 actionType deletedfile extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[10], $objectId[10], $actionType[10], $extra[10])) && p('product;project;execution') && e(',1,;11;101');     // 测试获取objectType testtask objectId 1 actionType editfile extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[11], $objectId[11], $actionType[11], $extra[11])) && p('product;project;execution') && e(',0,;0;0');        // 测试获取objectType user objectId 12 actionType erased extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[12], $objectId[12], $actionType[12], $extra[12])) && p('product;project;execution') && e(',13,;0;0');       // 测试获取objectType doc objectId 13 actionType undeleted extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[13], $objectId[13], $actionType[13], $extra[13])) && p('product;project;execution') && e(',14,;0;0');       // 测试获取objectType doclib objectId 14 actionType hidden extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[14], $objectId[14], $actionType[14], $extra[14])) && p('product;project;execution') && e(',0,;0;0');        // 测试获取objectType todo objectId 15 actionType commented extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[15], $objectId[15], $actionType[15], $extra[15])) && p('product;project;execution') && e(',0,;0;0');        // 测试获取objectType branch objectId 16 actionType activated extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[16], $objectId[16], $actionType[16], $extra[16])) && p('product;project;execution') && e(',,0,,;0;0');      // 测试获取objectType module objectId 17 actionType blocked extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[17], $objectId[17], $actionType[17], $extra[17])) && p('product;project;execution') && e(',0,;0;0');        // 测试获取objectType testsuite objectId 18 actionType moved extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[18], $objectId[18], $actionType[18], $extra[18])) && p('product;project;execution') && e(',0,;0;0');        // 测试获取objectType caselib objectId 19 actionType confirmed extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[19], $objectId[19], $actionType[19], $extra[19])) && p('product;project;execution') && e(',,;0;0');         // 测试获取objectType testreport objectId 20 actionType caseconfirmed extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[20], $objectId[20], $actionType[20], $extra[20])) && p('product;project;execution') && e(',0,;0;0');        // 测试获取objectType entry objectId 21 actionType bugconfirmed extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[21], $objectId[21], $actionType[21], $extra[21])) && p('product;project;execution') && e(',0,;0;0');        // 测试获取objectType webhook objectId 22 actionType frombug extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[22], $objectId[22], $actionType[22], $extra[22])) && p('product;project;execution') && e(',0,;0;0');        // 测试获取objectType review objectId 23 actionType started extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[0],  $objectId[23], $actionType[23], $extra[23])) && p('product;project;execution') && e(',24,;0;0');       // 测试获取objectType product objectId 24 actionType restarted extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[1],  $objectId[24], $actionType[24], $extra[24])) && p('product;project;execution') && e(',7,;0;0');        // 测试获取objectType story objectId 25 actionType delayed extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[2],  $objectId[25], $actionType[25], $extra[25])) && p('product;project;execution') && e(',9,;0;0');        // 测试获取objectType productplan objectId 26 actionType suspended extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[3],  $objectId[26], $actionType[26], $extra[26])) && p('product;project;execution') && e(',1,;11;0');       // 测试获取objectType release objectId 1 actionType recordestimate extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[4],  $objectId[27], $actionType[27], $extra[27])) && p('product;project;execution') && e(',8,18,98,;28;0'); // 测试获取objectType project objectId 28 actionType editestimate extra 33 的动态信息
r($action->getRelatedFieldsTest($objectType[5],  $objectId[28], $actionType[28], $extra[28])) && p('product;project;execution') && e(',29,;39;129');    // 测试获取objectType task objectId 29 actionType deleteestimate extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[6],  $objectId[29], $actionType[29], $extra[29])) && p('product;project;execution') && e(',1,;11;0');       // 测试获取objectType build objectId 1 actionType canceled extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[7],  $objectId[30], $actionType[30], $extra[30])) && p('product;project;execution') && e(',11,;21;111');    // 测试获取objectType bug objectId 31 actionType svncommited extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[8],  $objectId[31], $actionType[31], $extra[31])) && p('product;project;execution') && e(',8,;18;108');     // 测试获取objectType testcase objectId 32 actionType gitcommited extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[9],  $objectId[32], $actionType[32], $extra[32])) && p('product;project;execution') && e(',9,;19;109');     // 测试获取objectType case objectId 33 actionType finished extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[10], $objectId[33], $actionType[33], $extra[33])) && p('product;project;execution') && e(',1,;11;101');     // 测试获取objectType testtask objectId 1 actionType paused extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[11], $objectId[34], $actionType[34], $extra[34])) && p('product;project;execution') && e(',0,;0;0');        // 测试获取objectType user objectId 35 actionType verified extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[12], $objectId[35], $actionType[35], $extra[35])) && p('product;project;execution') && e(',36,;0;0');       // 测试获取objectType doc objectId 36 actionType diff1 extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[13], $objectId[36], $actionType[36], $extra[36])) && p('product;project;execution') && e(',37,;0;0');       // 测试获取objectType doclib objectId 37 actionType diff2 extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[14], $objectId[37], $actionType[37], $extra[37])) && p('product;project;execution') && e(',0,;0;0');        // 测试获取objectType todo objectId 38 actionType diff3 extra 1 的动态信息
r($action->getRelatedFieldsTest($objectType[15], $objectId[38], $actionType[38], $extra[38])) && p('product;project;execution') && e(',0,;0;0');        // 测试获取objectType branch objectId 39 actionType linked2bug extra 1 的动态信息
