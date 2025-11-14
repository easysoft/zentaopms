#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

zenData('user')->gen(5);
su('admin');

/**

title=测试 customModel->saveRequiredFields();
timeout=0
cid=15923

- 测试moduleName为product，requiredFields为空属性value @name
- 测试moduleName为product，requiredFields中的create存在一个值属性value @name,PO
- 测试moduleName为product，requiredFields中的create存在多个值属性value @name,PO,RD
- 测试moduleName为product，requiredFields中的edit存在一个值属性value @name,PO
- 测试moduleName为product，requiredFields中的edit存在多个值属性value @name,PO,RD
- 测试moduleName为product，requiredFields中的create,edit各存在多个值属性value @name,PO,RD
- 测试moduleName为release，requiredFields为空属性value @name,date,releasedDate
- 测试moduleName为release，requiredFields中的create存在一个值属性value @name,date,releasedDate,desc
- 测试moduleName为release，requiredFields中的edit存在一个值属性value @name,date,releasedDate,desc
- 测试moduleName为release，requiredFields中的create,edit各存在一个值属性value @name,date,releasedDate,desc
- 测试moduleName为execution，requiredFields为空属性value @name,begin,end
- 测试moduleName为execution，requiredFields中的create存在一个值属性value @name,begin,end,desc
- 测试moduleName为execution，requiredFields中的create存在多个值属性value @name,begin,end,desc,days
- 测试moduleName为execution，requiredFields中的edit存在一个值属性value @name,begin,end,desc
- 测试moduleName为execution，requiredFields中的edit存在多个值属性value @name,begin,end,desc,days
- 测试moduleName为execution，requiredFields中的create,edit各存在多个值属性value @name,begin,end,desc,days
- 测试moduleName为task，requiredFields为空属性value @execution,name,type
- 测试moduleName为task，requiredFields中的create存在一个值属性value @execution,name,type,story
- 测试moduleName为task，requiredFields中的create存在多个值属性value @execution,name,type,story,desc
- 测试moduleName为task，requiredFields中的edit存在一个值属性value @execution,name,type,pri
- 测试moduleName为task，requiredFields中的edit存在多个值属性value @execution,name,type,pri,estimate
- 测试moduleName为task，requiredFields中的activate存在一个值属性value @left,comment
- 测试moduleName为task，requiredFields中的activate存在多个值属性value @left,comment,assignedTo
- 测试moduleName为task，requiredFields中的create,edit各存在一个值属性value @execution,name,type,story
- 测试moduleName为task，requiredFields中的create,edit,finsh各存在一个值属性value @execution,name,type,story
- 测试moduleName为task，requiredFields中的create,edit,finsh,activate各存在一个值属性value @execution,name,type,story
- 测试moduleName为bug，requiredFields为空属性value @title,openedBuild
- 测试moduleName为bug，requiredFields中的create存在一个值属性value @title,openedBuild,type
- 测试moduleName为bug，requiredFields中的create存在多个值属性value @title,openedBuild,type,os
- 测试moduleName为bug，requiredFields中的edit存在一个值属性value @title,openedBuild,plan
- 测试moduleName为bug，requiredFields中的edit存在多个值属性value @title,openedBuild,plan,type
- 测试moduleName为bug，requiredFields中的resolve存在一个值属性value @resolution,comment
- 测试moduleName为bug，requiredFields中的resolve存在多个值属性value @resolution,comment,assignedTo
- 测试moduleName为bug，requiredFields中的create,edit各存在一个值属性value @title,openedBuild,type
- 测试moduleName为bug，requiredFields中的create,edit,resolve各存在一个值属性value @title,openedBuild,type
- 测试moduleName为testcase，requiredFields为空属性value @product,title,type
- 测试moduleName为testcase，requiredFields中的create存在一个值属性value @product,title,type,stage
- 测试moduleName为testcase，requiredFields中的create存在多个值属性value @product,title,type,stage,story
- 测试moduleName为testcase，requiredFields中的edit存在一个值属性value @title,type,stage
- 测试moduleName为testcase，requiredFields中的edit存在多个值属性value @title,type,stage,story
- 测试moduleName为testcase，requiredFields中的create,edit各存在多个值属性value @product,title,type,stage,story
- 测试moduleName为task，requiredFields中的finish存在一个值属性value @realStarted,finishedDate,currentConsumed,comment

*/

$moduleName = array('product', 'release', 'execution', 'task', 'bug', 'testcase');
$fieldsType = array('create', 'edit', 'finish', 'activate', 'resolve');
$requiredFields = array(
    'productFields1'   => array(),
    'productFields2'   => array('requiredFields' => array('create'   => array('PO'))),
    'productFields3'   => array('requiredFields' => array('create'   => array('PO', 'RD'))),
    'productFields4'   => array('requiredFields' => array('edit'     => array('PO'))),
    'productFields5'   => array('requiredFields' => array('edit'     => array('PO', 'RD'))),
    'productFields6'   => array('requiredFields' => array('create'   => array('PO', 'RD'), 'edit' => array('PO', 'RD'))),
    'releaseFields1'   => array(),
    'releaseFields2'   => array('requiredFields' => array('create'   => array('desc'))),
    'releaseFields3'   => array('requiredFields' => array('edit'     => array('desc'))),
    'releaseFields4'   => array('requiredFields' => array('create'   => array('desc'), 'edit' => array('desc'))),
    'executionFields1' => array(),
    'executionFields2' => array('requiredFields' => array('create'   => array('desc'))),
    'executionFields3' => array('requiredFields' => array('create'   => array('desc', 'days'))),
    'executionFields4' => array('requiredFields' => array('edit'     => array('desc'))),
    'executionFields5' => array('requiredFields' => array('edit'     => array('desc', 'days'))),
    'executionFields6' => array('requiredFields' => array('create'   => array('desc', 'days'), 'edit' => array('desc', 'days'))),
    'taskFields1'      => array(),
    'taskFields2'      => array('requiredFields' => array('create'   => array('story'))),
    'taskFields3'      => array('requiredFields' => array('create'   => array('story', 'desc'))),
    'taskFields4'      => array('requiredFields' => array('edit'     => array('pri'))),
    'taskFields5'      => array('requiredFields' => array('edit'     => array('pri', 'estimate'))),
    'taskFields6'      => array('requiredFields' => array('finish'   => array('comment'))),
    'taskFields7'      => array('requiredFields' => array('activate' => array('comment'))),
    'taskFields8'      => array('requiredFields' => array('activate' => array('comment', 'assignedTo'))),
    'taskFields9'      => array('requiredFields' => array('create'   => array('story'), 'edit' => array('pri'))),
    'taskFields10'     => array('requiredFields' => array('create'   => array('story'), 'edit' => array('pri'), 'finsh' => array('comment'))),
    'taskFields11'     => array('requiredFields' => array('create'   => array('story'), 'edit' => array('pri'), 'finsh' => array('comment'), 'activate' => array('comment'))),
    'bugFields1'       => array(),
    'bugFields2'       => array('requiredFields' => array('create'   => array('type'))),
    'bugFields3'       => array('requiredFields' => array('create'   => array('type', 'os'))),
    'bugFields4'       => array('requiredFields' => array('edit'     => array('plan'))),
    'bugFields5'       => array('requiredFields' => array('edit'     => array('plan', 'type'))),
    'bugFields6'       => array('requiredFields' => array('resolve'  => array('comment'))),
    'bugFields7'       => array('requiredFields' => array('resolve'  => array('comment', 'assignedTo'))),
    'bugFields8'       => array('requiredFields' => array('create'   => array('type'), 'edit' => array('plan'))),
    'bugFields9'       => array('requiredFields' => array('create'   => array('type'), 'edit' => array('plan'), 'resolve' => array('comment'))),
    'testcaseFields1'  => array(),
    'testcaseFields2'  => array('requiredFields' => array('create'   => array('stage'))),
    'testcaseFields3'  => array('requiredFields' => array('create'   => array('stage', 'story'))),
    'testcaseFields4'  => array('requiredFields' => array('edit'     => array('stage'))),
    'testcaseFields5'  => array('requiredFields' => array('edit'     => array('stage', 'story'))),
    'testcaseFields6'  => array('requiredFields' => array('create'   => array('stage', 'story'), 'edit' => array('stage', 'story'))),
);

$customTester = new customTest();
r($customTester->saveRequiredFieldsTest($moduleName[0], $requiredFields['productFields1'], $fieldsType[0]))   && p('value', ';') && e('name');                                             // 测试moduleName为product，requiredFields为空
r($customTester->saveRequiredFieldsTest($moduleName[0], $requiredFields['productFields2'], $fieldsType[0]))   && p('value', ';') && e('name,PO');                                          // 测试moduleName为product，requiredFields中的create存在一个值
r($customTester->saveRequiredFieldsTest($moduleName[0], $requiredFields['productFields3'], $fieldsType[0]))   && p('value', ';') && e('name,PO,RD');                                       // 测试moduleName为product，requiredFields中的create存在多个值
r($customTester->saveRequiredFieldsTest($moduleName[0], $requiredFields['productFields4'], $fieldsType[1]))   && p('value', ';') && e('name,PO');                                          // 测试moduleName为product，requiredFields中的edit存在一个值
r($customTester->saveRequiredFieldsTest($moduleName[0], $requiredFields['productFields5'], $fieldsType[1]))   && p('value', ';') && e('name,PO,RD');                                       // 测试moduleName为product，requiredFields中的edit存在多个值
r($customTester->saveRequiredFieldsTest($moduleName[0], $requiredFields['productFields6'], $fieldsType[0]))   && p('value', ';') && e('name,PO,RD');                                       // 测试moduleName为product，requiredFields中的create,edit各存在多个值
r($customTester->saveRequiredFieldsTest($moduleName[1], $requiredFields['releaseFields1'], $fieldsType[0]))   && p('value', ';') && e('name,date,releasedDate');                           // 测试moduleName为release，requiredFields为空
r($customTester->saveRequiredFieldsTest($moduleName[1], $requiredFields['releaseFields2'], $fieldsType[0]))   && p('value', ';') && e('name,date,releasedDate,desc');                      // 测试moduleName为release，requiredFields中的create存在一个值
r($customTester->saveRequiredFieldsTest($moduleName[1], $requiredFields['releaseFields3'], $fieldsType[1]))   && p('value', ';') && e('name,date,releasedDate,desc');                      // 测试moduleName为release，requiredFields中的edit存在一个值
r($customTester->saveRequiredFieldsTest($moduleName[1], $requiredFields['releaseFields4'], $fieldsType[0]))   && p('value', ';') && e('name,date,releasedDate,desc');                      // 测试moduleName为release，requiredFields中的create,edit各存在一个值
r($customTester->saveRequiredFieldsTest($moduleName[2], $requiredFields['executionFields1'], $fieldsType[0])) && p('value', ';') && e('name,begin,end');                                   // 测试moduleName为execution，requiredFields为空
r($customTester->saveRequiredFieldsTest($moduleName[2], $requiredFields['executionFields2'], $fieldsType[0])) && p('value', ';') && e('name,begin,end,desc');                              // 测试moduleName为execution，requiredFields中的create存在一个值
r($customTester->saveRequiredFieldsTest($moduleName[2], $requiredFields['executionFields3'], $fieldsType[0])) && p('value', ';') && e('name,begin,end,desc,days');                         // 测试moduleName为execution，requiredFields中的create存在多个值
r($customTester->saveRequiredFieldsTest($moduleName[2], $requiredFields['executionFields4'], $fieldsType[1])) && p('value', ';') && e('name,begin,end,desc');                              // 测试moduleName为execution，requiredFields中的edit存在一个值
r($customTester->saveRequiredFieldsTest($moduleName[2], $requiredFields['executionFields5'], $fieldsType[1])) && p('value', ';') && e('name,begin,end,desc,days');                         // 测试moduleName为execution，requiredFields中的edit存在多个值
r($customTester->saveRequiredFieldsTest($moduleName[2], $requiredFields['executionFields6'], $fieldsType[0])) && p('value', ';') && e('name,begin,end,desc,days');                         // 测试moduleName为execution，requiredFields中的create,edit各存在多个值
r($customTester->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields1'], $fieldsType[0]))      && p('value', ';') && e('execution,name,type');                              // 测试moduleName为task，requiredFields为空
r($customTester->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields2'], $fieldsType[0]))      && p('value', ';') && e('execution,name,type,story');                        // 测试moduleName为task，requiredFields中的create存在一个值
r($customTester->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields3'], $fieldsType[0]))      && p('value', ';') && e('execution,name,type,story,desc');                   // 测试moduleName为task，requiredFields中的create存在多个值
r($customTester->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields4'], $fieldsType[1]))      && p('value', ';') && e('execution,name,type,pri');                          // 测试moduleName为task，requiredFields中的edit存在一个值
r($customTester->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields5'], $fieldsType[1]))      && p('value', ';') && e('execution,name,type,pri,estimate');                 // 测试moduleName为task，requiredFields中的edit存在多个值
r($customTester->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields7'], $fieldsType[3]))      && p('value', ';') && e('left,comment');                                     // 测试moduleName为task，requiredFields中的activate存在一个值
r($customTester->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields8'], $fieldsType[3]))      && p('value', ';') && e('left,comment,assignedTo');                          // 测试moduleName为task，requiredFields中的activate存在多个值
r($customTester->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields9'], $fieldsType[0]))      && p('value', ';') && e('execution,name,type,story');                        // 测试moduleName为task，requiredFields中的create,edit各存在一个值
r($customTester->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields10'], $fieldsType[0]))     && p('value', ';') && e('execution,name,type,story');                        // 测试moduleName为task，requiredFields中的create,edit,finsh各存在一个值
r($customTester->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields11'], $fieldsType[0]))     && p('value', ';') && e('execution,name,type,story');                        // 测试moduleName为task，requiredFields中的create,edit,finsh,activate各存在一个值
r($customTester->saveRequiredFieldsTest($moduleName[4], $requiredFields['bugFields1'], $fieldsType[0]))       && p('value', ';') && e('title,openedBuild');                                // 测试moduleName为bug，requiredFields为空
r($customTester->saveRequiredFieldsTest($moduleName[4], $requiredFields['bugFields2'], $fieldsType[0]))       && p('value', ';') && e('title,openedBuild,type');                           // 测试moduleName为bug，requiredFields中的create存在一个值
r($customTester->saveRequiredFieldsTest($moduleName[4], $requiredFields['bugFields3'], $fieldsType[0]))       && p('value', ';') && e('title,openedBuild,type,os');                        // 测试moduleName为bug，requiredFields中的create存在多个值
r($customTester->saveRequiredFieldsTest($moduleName[4], $requiredFields['bugFields4'], $fieldsType[1]))       && p('value', ';') && e('title,openedBuild,plan');                           // 测试moduleName为bug，requiredFields中的edit存在一个值
r($customTester->saveRequiredFieldsTest($moduleName[4], $requiredFields['bugFields5'], $fieldsType[1]))       && p('value', ';') && e('title,openedBuild,plan,type');                      // 测试moduleName为bug，requiredFields中的edit存在多个值
r($customTester->saveRequiredFieldsTest($moduleName[4], $requiredFields['bugFields6'], $fieldsType[4]))       && p('value', ';') && e('resolution,comment');                               // 测试moduleName为bug，requiredFields中的resolve存在一个值
r($customTester->saveRequiredFieldsTest($moduleName[4], $requiredFields['bugFields7'], $fieldsType[4]))       && p('value', ';') && e('resolution,comment,assignedTo');                    // 测试moduleName为bug，requiredFields中的resolve存在多个值
r($customTester->saveRequiredFieldsTest($moduleName[4], $requiredFields['bugFields8'], $fieldsType[0]))       && p('value', ';') && e('title,openedBuild,type');                           // 测试moduleName为bug，requiredFields中的create,edit各存在一个值
r($customTester->saveRequiredFieldsTest($moduleName[4], $requiredFields['bugFields9'], $fieldsType[0]))       && p('value', ';') && e('title,openedBuild,type');                           // 测试moduleName为bug，requiredFields中的create,edit,resolve各存在一个值
r($customTester->saveRequiredFieldsTest($moduleName[5], $requiredFields['testcaseFields1'], $fieldsType[0]))  && p('value', ';') && e('product,title,type');                               // 测试moduleName为testcase，requiredFields为空
r($customTester->saveRequiredFieldsTest($moduleName[5], $requiredFields['testcaseFields2'], $fieldsType[0]))  && p('value', ';') && e('product,title,type,stage');                         // 测试moduleName为testcase，requiredFields中的create存在一个值
r($customTester->saveRequiredFieldsTest($moduleName[5], $requiredFields['testcaseFields3'], $fieldsType[0]))  && p('value', ';') && e('product,title,type,stage,story');                   // 测试moduleName为testcase，requiredFields中的create存在多个值
r($customTester->saveRequiredFieldsTest($moduleName[5], $requiredFields['testcaseFields4'], $fieldsType[1]))  && p('value', ';') && e('title,type,stage');                                 // 测试moduleName为testcase，requiredFields中的edit存在一个值
r($customTester->saveRequiredFieldsTest($moduleName[5], $requiredFields['testcaseFields5'], $fieldsType[1]))  && p('value', ';') && e('title,type,stage,story');                           // 测试moduleName为testcase，requiredFields中的edit存在多个值
r($customTester->saveRequiredFieldsTest($moduleName[5], $requiredFields['testcaseFields6'], $fieldsType[0]))  && p('value', ';') && e('product,title,type,stage,story');                   // 测试moduleName为testcase，requiredFields中的create,edit各存在多个值
r($customTester->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields6'], $fieldsType[2]))      && p('value', ';') && e('realStarted,finishedDate,currentConsumed,comment'); // 测试moduleName为task，requiredFields中的finish存在一个值
