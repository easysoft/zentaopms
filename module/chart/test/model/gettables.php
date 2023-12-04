#!/usr/bin/env php
<?php
/**

title=测试 chartModel::getTables();
cid=1
pid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('user')->gen(5);
su('admin');

global $tester;
$chart = $tester->loadModel('chart');

$sql = array();
$sql[0] = 'select * from zt_story;';
$sql[1] = 'select id,name from zt_story;';
$sql[2] = 'select t1.id,t2.name from zt_story as t1 left join zt_product as t2 on t1.product = t2.id;';
$sql[3] = 'select t1.id as storyID,t2.name as productName from zt_story as t1 left join zt_product as t2 on t1.product = t2.id;';
$sql[4] = 'select * from zt_story where id = 1;';
$sql[5] = 'select * from zt_story limit 10;';
$sql[6] = 'select * from zt_story group by product;';

r($chart->getTables($sql[0])) && p('tables:0')       && e('zt_story'); //测试普通SQL语句获取表是否正确
r($chart->getTables($sql[0])) && p('fields:*')       && e('*');        //测试普通SQL语句获取字段是否正确
r($chart->getTables($sql[1])) && p('fields:id,name') && e('id,name');  //测试普通SQL语句获取查询字段是否正确

r($chart->getTables($sql[2])) && p('tables:0,1')                 && e('zt_story,zt_product'); //测试联表SQL语句获取表是否正确
r($chart->getTables($sql[2])) && p('fields:id,name')             && e('t1.id,t2.name');       //测试联表SQL语句获取字段是否正确
r($chart->getTables($sql[3])) && p('fields:storyID,productName') && e('t1.id,t2.name');       //测试联表SQL语句获取重定义字段是否正确

r($chart->getTables($sql[4])) && p('tables:0') && e('zt_story'); //测试sql中有where获取表是否正确
r($chart->getTables($sql[4])) && p('fields:*') && e('*');        //测试sql中有where获取字段是否正确

r($chart->getTables($sql[5])) && p('tables:0') && e('zt_story'); //测试sql中有limit获取表是否正确
r($chart->getTables($sql[5])) && p('fields:*') && e('*');        //测试sql中有limit获取字段是否正确

r($chart->getTables($sql[6])) && p('tables:0') && e('zt_story'); //测试sql中有group by获取表是否正确
r($chart->getTables($sql[6])) && p('fields:*') && e('*');        //测试sql中有group by获取字段是否正确
