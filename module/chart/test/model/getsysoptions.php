#!/usr/bin/env php
<?php
/**

title=测试 chartModel::getSysOptions();
timeout=0
cid=15576

- 测试参数全部为空 @0
- 测试查询用户属性admin @admin
- 测试查询产品属性1 @正常产品1
- 测试查询项目属性11 @项目11
- 测试查询部门属性1 @/产品部1
- 测试查询bug状态属性active @激活
- 测试查询bug名称属性1 @BUG1
- 测试使用sql查询属性BUG1 @BUG1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('user')->gen(5);
zenData('product')->gen(5);
zenData('project')->gen(20);
zenData('dept')->gen(5);
zenData('bug')->gen(5);
su('admin');

global $tester;
$chart = $tester->loadModel('chart');

r($chart->getSysOptions('',        '', '', ''))    && p()        && e('0');         //测试参数全部为空
r($chart->getSysOptions('user',    '', '', ''))    && p('admin') && e('admin');     //测试查询用户
r($chart->getSysOptions('product', '', '', ''))    && p('1')     && e('正常产品1'); //测试查询产品
r($chart->getSysOptions('project', '', '', ''))    && p('11')    && e('项目11');    //测试查询项目
r($chart->getSysOptions('dept',    '', '', ''))    && p('1')     && e('/产品部1');  //测试查询部门

r($chart->getSysOptions('option', 'bug', 'status', '')) && p('active') && e('激活'); //测试查询bug状态
r($chart->getSysOptions('object', 'bug', 'title', ''))  && p('1') && e('BUG1');      //测试查询bug名称

r($chart->getSysOptions('string', '',    'title', 'select * from zt_bug')) && p('BUG1') && e('BUG1'); //测试使用sql查询
