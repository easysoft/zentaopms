#!/usr/bin/env php
<?php

/**

title=测试 programZen::prepareStartExtras();
timeout=0
cid=0

- 执行programTest模块的prepareStartExtrasTest方法，参数是array
 - 属性status @doing
 - 属性lastEditedBy @admin
- 执行programTest模块的prepareStartExtrasTest方法，参数是array
 - 属性id @1
 - 属性status @doing
 - 属性lastEditedBy @admin
- 执行programTest模块的prepareStartExtrasTest方法，参数是array
 - 属性id @2
 - 属性name @Test Program
 - 属性type @program
 - 属性status @doing
 - 属性lastEditedBy @admin
- 执行programTest模块的prepareStartExtrasTest方法，参数是array 属性status @doing
- 执行programTest模块的prepareStartExtrasTest方法，参数是array 属性lastEditedBy @admin
- 执行lastEditedDate) && preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $result模块的lastEditedDate方法  @1
- 执行programTest模块的prepareStartExtrasTest方法，参数是array 属性status @doing

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programzen.unittest.class.php';

zenData('user')->gen(5);

su('admin');

$programTest = new programTest();

r($programTest->prepareStartExtrasTest(array())) && p('status,lastEditedBy') && e('doing,admin');
r($programTest->prepareStartExtrasTest(array('id' => 1))) && p('id,status,lastEditedBy') && e('1,doing,admin');
r($programTest->prepareStartExtrasTest(array('id' => 2, 'name' => 'Test Program', 'type' => 'program'))) && p('id,name,type,status,lastEditedBy') && e('2,Test Program,program,doing,admin');
r($programTest->prepareStartExtrasTest(array('name' => 'Status Test'))) && p('status') && e('doing');
r($programTest->prepareStartExtrasTest(array('name' => 'User Test'))) && p('lastEditedBy') && e('admin');
$result = $programTest->prepareStartExtrasTest(array('name' => 'Date Test'));
r(!empty($result->lastEditedDate) && preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $result->lastEditedDate)) && p() && e('1');
r($programTest->prepareStartExtrasTest(array('name' => 'Override Test', 'status' => 'wait'))) && p('status') && e('doing');