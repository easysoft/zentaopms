#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/bug.class.php';
su('admin');

/**

title=bugModel->getByList();
cid=1
pid=1

*/
function initData()
{
    $bug = zdTable('bug');
    $bug->id->range('1-10');
    $bug->status->range("resolved,active,closed");
    $bug->title->prefix("BUG")->range('1-10');
    $bug->plan->range('0,1,2');
    $bug->module->range('0,1,2');
    $bug->story->range('0,1,2');

    $bug->gen(10);
}

initData();

$bugIDList  = array('1,2,3', '1,4', '2,7', '1000001');
$fieldsList = array('*', 'id,title,status,plan,module,story', 'title,status', 'title');

$bug = new bugTest();

r($bug->getByIdListTest($bugIDList[0], $fieldsList[0])) && p('1:title')  && e('BUG1');     // 查询id为1的bug title
r($bug->getByIdListTest($bugIDList[0], $fieldsList[1])) && p('2:status') && e('active');   // 查询id为2的bug status
r($bug->getByIdListTest($bugIDList[0], $fieldsList[1])) && p('2:module') && e('1');        // 查询id为2的bug module
r($bug->getByIdListTest($bugIDList[0], $fieldsList[1])) && p('3:plan')   && e('2');        // 查询id为2的bug plan
r($bug->getByIdListTest($bugIDList[0], $fieldsList[1])) && p('3:story')  && e('2');        // 查询id为2的bug story
r($bug->getByIdListTest($bugIDList[0], $fieldsList[2])) && p('3:title')  && e('BUG3');     // 查询id为3的bug title
r($bug->getByIdListTest($bugIDList[0], $fieldsList[3])) && p('1:title')  && e('BUG1');     // 查询id为1的bug title
r($bug->getByIdListTest($bugIDList[1], $fieldsList[0])) && p('4:title')  && e('BUG4');     // 查询id为4的bug title
r($bug->getByIdListTest($bugIDList[1], $fieldsList[1])) && p('4:status') && e('resolved'); // 查询id为4的bug status
r($bug->getByIdListTest($bugIDList[1], $fieldsList[3])) && p('1:title')  && e('BUG1');     // 查询id为1的bug title
r($bug->getByIdListTest($bugIDList[2], $fieldsList[0])) && p('2:title')  && e('BUG2');     // 查询id为2的bug title
r($bug->getByIdListTest($bugIDList[2], $fieldsList[1])) && p('2:status') && e('active');   // 查询id为2的bug status
r($bug->getByIdListTest($bugIDList[2], $fieldsList[2])) && p('7:status') && e('resolved'); // 查询id为7的bug title
r($bug->getByIdListTest($bugIDList[3], $fieldsList[0])) && p('')         && e('0');        // 查询不存在的ID
