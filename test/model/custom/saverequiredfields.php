#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/custom.class.php';
su('admin');

/**

title=测试 customModel->saveRequiredFields();
cid=1
pid=1

测试moduleName为product，requiredFields为空 >> name,code
测试moduleName为product，requiredFields中的create存在一个值 >> PO,name,code
测试moduleName为product，requiredFields中的create存在多个值 >> PO,RD,name,code
测试moduleName为product，requiredFields中的edit存在一个值 >> PO,name,code
测试moduleName为product，requiredFields中的edit存在多个值 >> PO,RD,name,code
测试moduleName为product，requiredFields中的create,edit各存在多个值 >> PO,RD,name,code
测试moduleName为release，requiredFields为空 >> name,date
测试moduleName为release，requiredFields中的create存在一个值 >> desc,name,date
测试moduleName为release，requiredFields中的edit存在一个值 >> desc,name,build,date
测试moduleName为release，requiredFields中的create,edit各存在一个值 >> desc,name,date
测试moduleName为execution，requiredFields为空 >> name,code,begin,end
测试moduleName为execution，requiredFields中的create存在一个值 >> desc,name,code,begin,end
测试moduleName为execution，requiredFields中的create存在多个值 >> desc,days,name,code,begin,end
测试moduleName为execution，requiredFields中的edit存在一个值 >> desc,name,code,begin,end
测试moduleName为execution，requiredFields中的edit存在多个值 >> desc,days,name,code,begin,end
测试moduleName为execution，requiredFields中的create,edit各存在多个值 >> desc,days,name,code,begin,end
测试moduleName为task，requiredFields为空 >> execution,name,type
测试moduleName为task，requiredFields中的create存在一个值 >> story,execution,name,type
测试moduleName为task，requiredFields中的create存在多个值 >> story,desc,execution,name,type
测试moduleName为task，requiredFields中的edit存在一个值 >> pri,execution,name,type
测试moduleName为task，requiredFields中的edit存在多个值 >> pri,estimate,execution,name,type
测试moduleName为task，requiredFields中的activate存在一个值 >> comment,left
测试moduleName为task，requiredFields中的activate存在多个值 >> comment,assignedTo,left
测试moduleName为task，requiredFields中的create,edit各存在一个值 >> story,execution,name,type
测试moduleName为task，requiredFields中的create,edit,finsh各存在一个值 >> story,execution,name,type
测试moduleName为task，requiredFields中的create,edit,finsh,activate各存在一个值 >> story,execution,name,type
测试moduleName为bug，requiredFields为空 >> title,openedBuild
测试moduleName为bug，requiredFields中的create存在一个值 >> type,title,openedBuild
测试moduleName为bug，requiredFields中的create存在多个值 >> type,os,title,openedBuild
测试moduleName为bug，requiredFields中的edit存在一个值 >> plan,title,openedBuild
测试moduleName为bug，requiredFields中的edit存在多个值 >> plan,type,title,openedBuild
测试moduleName为bug，requiredFields中的resolve存在一个值 >> comment,resolution
测试moduleName为bug，requiredFields中的resolve存在多个值 >> comment,assignedTo,resolution
测试moduleName为bug，requiredFields中的create,edit各存在一个值 >> type,title,openedBuild
测试moduleName为bug，requiredFields中的create,edit,resolve各存在一个值 >> type,title,openedBuild
测试moduleName为testcase，requiredFields为空 >> title,type
测试moduleName为testcase，requiredFields中的create存在一个值 >> stage,title,type
测试moduleName为testcase，requiredFields中的create存在多个值 >> stage,story,title,type
测试moduleName为testcase，requiredFields中的edit存在一个值 >> stage,title,type
测试moduleName为testcase，requiredFields中的edit存在多个值 >> stage,story,title,type
测试moduleName为testcase，requiredFields中的create,edit各存在多个值 >> stage,story,title,type
测试moduleName为task，requiredFields中的finish存在一个值 >> comment,realStarted,finishedDate,currentConsumed

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

$custom = new customTest();

r($custom->saveRequiredFieldsTest($moduleName[0], $requiredFields['productFields1'], $fieldsType[0]))   && p('value') && e('name,code');                        //测试moduleName为product，requiredFields为空
r($custom->saveRequiredFieldsTest($moduleName[0], $requiredFields['productFields2'], $fieldsType[0]))   && p('value') && e('PO,name,code');                     //测试moduleName为product，requiredFields中的create存在一个值
r($custom->saveRequiredFieldsTest($moduleName[0], $requiredFields['productFields3'], $fieldsType[0]))   && p('value') && e('PO,RD,name,code');                  //测试moduleName为product，requiredFields中的create存在多个值
r($custom->saveRequiredFieldsTest($moduleName[0], $requiredFields['productFields4'], $fieldsType[1]))   && p('value') && e('PO,name,code');                     //测试moduleName为product，requiredFields中的edit存在一个值
r($custom->saveRequiredFieldsTest($moduleName[0], $requiredFields['productFields5'], $fieldsType[1]))   && p('value') && e('PO,RD,name,code');                  //测试moduleName为product，requiredFields中的edit存在多个值
r($custom->saveRequiredFieldsTest($moduleName[0], $requiredFields['productFields6'], $fieldsType[0]))   && p('value') && e('PO,RD,name,code');                  //测试moduleName为product，requiredFields中的create,edit各存在多个值
r($custom->saveRequiredFieldsTest($moduleName[1], $requiredFields['releaseFields1'], $fieldsType[0]))   && p('value') && e('name,date');                        //测试moduleName为release，requiredFields为空
r($custom->saveRequiredFieldsTest($moduleName[1], $requiredFields['releaseFields2'], $fieldsType[0]))   && p('value') && e('desc,name,date');                   //测试moduleName为release，requiredFields中的create存在一个值
r($custom->saveRequiredFieldsTest($moduleName[1], $requiredFields['releaseFields3'], $fieldsType[1]))   && p('value') && e('desc,name,build,date');             //测试moduleName为release，requiredFields中的edit存在一个值
r($custom->saveRequiredFieldsTest($moduleName[1], $requiredFields['releaseFields4'], $fieldsType[0]))   && p('value') && e('desc,name,date');                   //测试moduleName为release，requiredFields中的create,edit各存在一个值
r($custom->saveRequiredFieldsTest($moduleName[2], $requiredFields['executionFields1'], $fieldsType[0])) && p('value') && e('name,code,begin,end');              //测试moduleName为execution，requiredFields为空
r($custom->saveRequiredFieldsTest($moduleName[2], $requiredFields['executionFields2'], $fieldsType[0])) && p('value') && e('desc,name,code,begin,end');         //测试moduleName为execution，requiredFields中的create存在一个值
r($custom->saveRequiredFieldsTest($moduleName[2], $requiredFields['executionFields3'], $fieldsType[0])) && p('value') && e('desc,days,name,code,begin,end');    //测试moduleName为execution，requiredFields中的create存在多个值
r($custom->saveRequiredFieldsTest($moduleName[2], $requiredFields['executionFields4'], $fieldsType[1])) && p('value') && e('desc,name,code,begin,end');         //测试moduleName为execution，requiredFields中的edit存在一个值
r($custom->saveRequiredFieldsTest($moduleName[2], $requiredFields['executionFields5'], $fieldsType[1])) && p('value') && e('desc,days,name,code,begin,end');    //测试moduleName为execution，requiredFields中的edit存在多个值
r($custom->saveRequiredFieldsTest($moduleName[2], $requiredFields['executionFields6'], $fieldsType[0])) && p('value') && e('desc,days,name,code,begin,end');    //测试moduleName为execution，requiredFields中的create,edit各存在多个值
r($custom->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields1'], $fieldsType[0]))      && p('value') && e('execution,name,type');              //测试moduleName为task，requiredFields为空
r($custom->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields2'], $fieldsType[0]))      && p('value') && e('story,execution,name,type');        //测试moduleName为task，requiredFields中的create存在一个值
r($custom->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields3'], $fieldsType[0]))      && p('value') && e('story,desc,execution,name,type');   //测试moduleName为task，requiredFields中的create存在多个值
r($custom->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields4'], $fieldsType[1]))      && p('value') && e('pri,execution,name,type');          //测试moduleName为task，requiredFields中的edit存在一个值
r($custom->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields5'], $fieldsType[1]))      && p('value') && e('pri,estimate,execution,name,type'); //测试moduleName为task，requiredFields中的edit存在多个值
r($custom->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields7'], $fieldsType[3]))      && p('value') && e('comment,left');                     //测试moduleName为task，requiredFields中的activate存在一个值
r($custom->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields8'], $fieldsType[3]))      && p('value') && e('comment,assignedTo,left');          //测试moduleName为task，requiredFields中的activate存在多个值
r($custom->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields9'], $fieldsType[0]))      && p('value') && e('story,execution,name,type');        //测试moduleName为task，requiredFields中的create,edit各存在一个值
r($custom->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields10'], $fieldsType[0]))     && p('value') && e('story,execution,name,type');        //测试moduleName为task，requiredFields中的create,edit,finsh各存在一个值
r($custom->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields11'], $fieldsType[0]))     && p('value') && e('story,execution,name,type');        //测试moduleName为task，requiredFields中的create,edit,finsh,activate各存在一个值
r($custom->saveRequiredFieldsTest($moduleName[4], $requiredFields['bugFields1'], $fieldsType[0]))       && p('value') && e('title,openedBuild');                //测试moduleName为bug，requiredFields为空
r($custom->saveRequiredFieldsTest($moduleName[4], $requiredFields['bugFields2'], $fieldsType[0]))       && p('value') && e('type,title,openedBuild');           //测试moduleName为bug，requiredFields中的create存在一个值
r($custom->saveRequiredFieldsTest($moduleName[4], $requiredFields['bugFields3'], $fieldsType[0]))       && p('value') && e('type,os,title,openedBuild');        //测试moduleName为bug，requiredFields中的create存在多个值
r($custom->saveRequiredFieldsTest($moduleName[4], $requiredFields['bugFields4'], $fieldsType[1]))       && p('value') && e('plan,title,openedBuild');           //测试moduleName为bug，requiredFields中的edit存在一个值
r($custom->saveRequiredFieldsTest($moduleName[4], $requiredFields['bugFields5'], $fieldsType[1]))       && p('value') && e('plan,type,title,openedBuild');      //测试moduleName为bug，requiredFields中的edit存在多个值
r($custom->saveRequiredFieldsTest($moduleName[4], $requiredFields['bugFields6'], $fieldsType[4]))       && p('value') && e('comment,resolution');               //测试moduleName为bug，requiredFields中的resolve存在一个值
r($custom->saveRequiredFieldsTest($moduleName[4], $requiredFields['bugFields7'], $fieldsType[4]))       && p('value') && e('comment,assignedTo,resolution');    //测试moduleName为bug，requiredFields中的resolve存在多个值
r($custom->saveRequiredFieldsTest($moduleName[4], $requiredFields['bugFields8'], $fieldsType[0]))       && p('value') && e('type,title,openedBuild');           //测试moduleName为bug，requiredFields中的create,edit各存在一个值
r($custom->saveRequiredFieldsTest($moduleName[4], $requiredFields['bugFields9'], $fieldsType[0]))       && p('value') && e('type,title,openedBuild');           //测试moduleName为bug，requiredFields中的create,edit,resolve各存在一个值
r($custom->saveRequiredFieldsTest($moduleName[5], $requiredFields['testcaseFields1'], $fieldsType[0]))  && p('value') && e('title,type');                       //测试moduleName为testcase，requiredFields为空
r($custom->saveRequiredFieldsTest($moduleName[5], $requiredFields['testcaseFields2'], $fieldsType[0]))  && p('value') && e('stage,title,type');                 //测试moduleName为testcase，requiredFields中的create存在一个值
r($custom->saveRequiredFieldsTest($moduleName[5], $requiredFields['testcaseFields3'], $fieldsType[0]))  && p('value') && e('stage,story,title,type');           //测试moduleName为testcase，requiredFields中的create存在多个值
r($custom->saveRequiredFieldsTest($moduleName[5], $requiredFields['testcaseFields4'], $fieldsType[1]))  && p('value') && e('stage,title,type');                 //测试moduleName为testcase，requiredFields中的edit存在一个值
r($custom->saveRequiredFieldsTest($moduleName[5], $requiredFields['testcaseFields5'], $fieldsType[1]))  && p('value') && e('stage,story,title,type');           //测试moduleName为testcase，requiredFields中的edit存在多个值
r($custom->saveRequiredFieldsTest($moduleName[5], $requiredFields['testcaseFields6'], $fieldsType[0]))  && p('value') && e('stage,story,title,type');           //测试moduleName为testcase，requiredFields中的create,edit各存在多个值
r($custom->saveRequiredFieldsTest($moduleName[3], $requiredFields['taskFields6'], $fieldsType[2]))      && p('value') && e('comment,realStarted,finishedDate,currentConsumed');//测试moduleName为task，requiredFields中的finish存在一个值

