#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';
zdTable('user')->gen(5);
su('admin');

$program = zdTable('project');
$program->id->range('1-20');
$program->name->range('1-20')->prefix('项目集');
$program->type->range('program');
$program->path->range('1-20')->prefix(',')->postfix(',');
$program->grade->range('1');
$program->status->range('wait,doing,suspended,closed');
$program->openedBy->range('admin,user1');
$program->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$program->gen(20);

$query = zdTable('userquery');
$query->id->range('1');
$query->account->range('admin');
$query->module->range('program');
$query->title->range('搜索条件1');
$query->form->range('`a:41:{s:9:"fieldname";s:0:"";s:11:"fieldstatus";s:0:"";s:9:"fielddesc";s:0:"";s:7:"fieldPM";s:0:"";s:15:"fieldopenedDate";s:0:"";s:10:"fieldbegin";s:0:"";s:8:"fieldend";s:0:"";s:13:"fieldopenedBy";s:0:"";s:19:"fieldlastEditedDate";s:0:"";s:14:"fieldrealBegan";s:0:"";s:12:"fieldrealEnd";s:0:"";s:15:"fieldclosedDate";s:0:"";s:6:"andOr1";s:3:"AND";s:6:"field1";s:4:"name";s:9:"operator1";s:7:"include";s:6:"value1";s:9:"项目集";s:6:"andOr2";s:3:"and";s:6:"field2";s:6:"status";s:9:"operator2";s:1:"=";s:6:"value2";s:0:"";s:6:"andOr3";s:3:"and";s:6:"field3";s:4:"desc";s:9:"operator3";s:7:"include";s:6:"value3";s:0:"";s:10:"groupAndOr";s:3:"and";s:6:"andOr4";s:3:"AND";s:6:"field4";s:6:"status";s:9:"operator4";s:1:"=";s:6:"value4";s:4:"wait";s:6:"andOr5";s:3:"and";s:6:"field5";s:10:"openedDate";s:9:"operator5";s:1:"=";s:6:"value5";s:0:"";s:6:"andOr6";s:3:"and";s:6:"field6";s:5:"begin";s:9:"operator6";s:1:"=";s:6:"value6";s:0:"";s:6:"module";s:7:"program";s:9:"actionURL";s:56:"/program-browse-bySearch-order_asc-0-10-1-myQueryID.html";s:10:"groupItems";s:1:"3";s:8:"formType";s:4:"lite";}`');
$query->sql->range("`(( 1   AND `name`  LIKE '%项目集%' ) AND ( 1  AND `status` = 'wait'  ))`");
$query->gen(1);

/**

title=测试 programModel::getListBySearch();
cid=1
pid=1

按照id倒序查看，所有名称包含项目集并且未开始的第一个项目集 >> 项目集17
按照id正序查看，所有名称包含项目集并且进行中的第一个项目集 >> 项目集1
按照id正序查看，符合搜索条件id=1，的第一个项目集           >> 项目集1

*/

global $tester;
$programTester = new programTest();

$sql1 = "(( 1   AND `name`  LIKE '%项目集%' ) AND ( 1  AND `status` = 'wait'  ))";
$sql2 = "(( 1   AND `name`  LIKE '%项目集%' ) AND ( 1  AND `status` = 'doing'  ))";

r($programTester->getListBySearchTest('id_desc', 0, $sql)) && p('17:name') && e('项目集17'); // 按照id倒序查看，所有名称包含项目集并且未开始的第一个项目集
r($programTester->getListBySearchTest('id_asc', 0, $sql))  && p('1:name')  && e('项目集1');  // 按照id正序查看，所有名称包含项目集并且进行中的第一个项目集
r($programTester->getListBySearchTest('id_asc', 1))        && p('1:name')  && e('项目集1');  // 按照id正序查看，符合搜索条件id=1，的第一个项目集
