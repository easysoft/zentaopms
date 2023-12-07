#!/usr/bin/env php
<?php
/**

title=测试 chartModel::getFieldOptions();
cid=1
pid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('user')->gen(5);
zdTable('product')->gen(5);
zdTable('project')->gen(20);
zdTable('dept')->gen(5);
zdTable('bug')->gen(5);
su('admin');

global $tester;
$chart = $tester->loadModel('chart');

r($chart->getFieldOptions('',        '', '', ''))    && p()        && e('0');         //测试参数全部为空
r($chart->getFieldOptions('user',    '', '', ''))    && p('admin') && e('A:admin');   //测试查询用户
r($chart->getFieldOptions('product', '', '', ''))    && p('1')     && e('正常产品1'); //测试查询产品
r($chart->getFieldOptions('project', '', '', ''))    && p('11')    && e('项目11');    //测试查询项目
r($chart->getFieldOptions('dept',    '', '', ''))    && p('1')     && e('/产品部1');  //测试查询部门

r($chart->getFieldOptions('option', 'bug', 'status', '')) && p('active') && e('激活'); //测试查询bug状态
r($chart->getFieldOptions('object', 'bug', 'title', ''))  && p('1') && e('BUG1');      //测试查询bug名称

r($chart->getFieldOptions('string', '',    'title', 'select * from zt_bug')) && p('BUG1') && e('BUG1'); //测试使用sql查询
