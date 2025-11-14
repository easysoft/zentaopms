#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

su('admin');

$result = zenData('workflowfield')->gen(0);

/**

title=upgradeModel->addFlowFields();
cid=19497
pid=1

- 获取ID为1的工作流字段名称、所属模块、类型、控件。
 - 第1条的name属性 @由谁创建
 - 第1条的module属性 @productplan
 - 第1条的type属性 @varchar
 - 第1条的control属性 @select
- 获取ID为2的工作流字段名称、所属模块、类型、控件。
 - 第2条的name属性 @创建时间
 - 第2条的module属性 @productplan
 - 第2条的type属性 @datetime
 - 第2条的control属性 @datetime
- 获取ID为3的工作流字段名称、所属模块、类型、控件。
 - 第3条的name属性 @由谁创建
 - 第3条的module属性 @testtask
 - 第3条的type属性 @varchar
 - 第3条的control属性 @select
- 获取ID为4的工作流字段名称、所属模块、类型、控件。
 - 第4条的name属性 @创建时间
 - 第4条的module属性 @testtask
 - 第4条的type属性 @datetime
 - 第4条的control属性 @datetime
- 获取ID为5的工作流字段名称、所属模块、类型、控件。
 - 第5条的name属性 @由谁创建
 - 第5条的module属性 @build
 - 第5条的type属性 @varchar
 - 第5条的control属性 @select
- 获取ID为6的工作流字段名称、所属模块、类型、控件。
 - 第6条的name属性 @创建时间
 - 第6条的module属性 @build
 - 第6条的type属性 @datetime
 - 第6条的control属性 @datetime
- 获取ID为7的工作流字段名称、所属模块、类型、控件。
 - 第7条的name属性 @由谁创建
 - 第7条的module属性 @release
 - 第7条的type属性 @varchar
 - 第7条的control属性 @select
- 获取ID为8的工作流字段名称、所属模块、类型、控件。
 - 第8条的name属性 @创建时间
 - 第8条的module属性 @release
 - 第8条的type属性 @datetime
 - 第8条的control属性 @datetime

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
