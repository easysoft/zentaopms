#!/usr/bin/env php
<?php
/**

title=测试 chartModel::parseSqlVars();
timeout=0
cid=1

- 测试设置了默认值的情况 @select id,name from zt_project where deleted=0 and id='1'
- 测试没有默认值时是否替换为空 @select id,name from zt_project where deleted=0 and id=''

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('user')->gen(5);
su('admin');

global $tester;
$chart = $tester->loadModel('chart');

$sql = 'select id,name from zt_project where deleted=0 and id=$execution';

$filters1 = array();
$filters1[0]['field']   = 'execution';
$filters1[0]['default'] = '1';
$filters1[0]['from']    = 'query';

$filters2 = array();
$filters2[1]['field'] = 'execution';
$filters2[1]['from']  = 'query';

r($chart->parseSqlVars($sql, $filters1)) && p('') && e("select id,name from zt_project where deleted=0 and id='1'"); //测试设置了默认值的情况
r($chart->parseSqlVars($sql, $filters2)) && p('') && e("select id,name from zt_project where deleted=0 and id=''");  //测试没有默认值时是否替换为空
