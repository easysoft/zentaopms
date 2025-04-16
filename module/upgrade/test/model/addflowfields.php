#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

$result = zenData('workflowfield')->gen(0);

/**

title=upgradeModel->addFlowFields();
cid=1
pid=1

*/

global $tester;
$upgrade = $tester->loadModel('upgrade');
$upgrade->addFlowFields('biz7.4');
$fields = $upgrade->dao->select('*')->from(TABLE_WORKFLOWFIELD)->fetchAll('id');
r($fields) && p('1:name,module,type,control') && e('由谁创建,productplan,varchar,select');    // 获取ID为1的工作流字段名称、所属模块、类型、控件。
r($fields) && p('2:name,module,type,control') && e('创建时间,productplan,datetime,datetime'); // 获取ID为2的工作流字段名称、所属模块、类型、控件。
r($fields) && p('3:name,module,type,control') && e('由谁创建,testtask,varchar,select');       // 获取ID为3的工作流字段名称、所属模块、类型、控件。
r($fields) && p('4:name,module,type,control') && e('创建时间,testtask,datetime,datetime');    // 获取ID为4的工作流字段名称、所属模块、类型、控件。
r($fields) && p('5:name,module,type,control') && e('由谁创建,build,varchar,select');          // 获取ID为5的工作流字段名称、所属模块、类型、控件。
r($fields) && p('6:name,module,type,control') && e('创建时间,build,datetime,datetime');       // 获取ID为6的工作流字段名称、所属模块、类型、控件。
r($fields) && p('7:name,module,type,control') && e('由谁创建,release,varchar,select');        // 获取ID为7的工作流字段名称、所属模块、类型、控件。
r($fields) && p('8:name,module,type,control') && e('创建时间,release,datetime,datetime');     // 获取ID为8的工作流字段名称、所属模块、类型、控件。
