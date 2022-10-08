#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 programModel::getProjectStats();
cid=1
pid=1

查看当前项目集下所有未完成的项目的个数 >> 7
查看当前项目集下所有未完成的项目的个数 >> 7
查看当前项目集下所有未完成的项目按照id倒序排的第一个项目集信息 >> 91,项目81
查看当前项目集下所有未完成的项目按照id正序排的第一个项目集信息 >> 12,项目2

*/

global $tester;
$tester->loadModel('program');
$stats1 = $tester->program->getProjectStats(1);
$stats2 = $tester->program->getProjectStats(2, 'undone', 0, 'id_asc', null);

r(count($stats1))   && p()           && e('7');         // 查看当前项目集下所有未完成的项目的个数
r(count($stats2))   && p()           && e('7');         // 查看当前项目集下所有未完成的项目的个数
r(current($stats1)) && p('id,name')  && e('91,项目81'); // 查看当前项目集下所有未完成的项目按照id倒序排的第一个项目集信息
r(current($stats2)) && p('id,name')  && e('12,项目2');  // 查看当前项目集下所有未完成的项目按照id正序排的第一个项目集信息