#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/weekly.class.php';
su('admin');

/**

title=测试 weeklyModel->getWorkloadByType();
cid=1
pid=1

测试project值为0 >> 577.00,568.00,563.00,556.00,557.00,571.00,563.00,566.00
测试project值为1 >> 0
测试project值为11 >> 27.00,11.00,16.00,3.00,9.00,5.00,18.00,14.00
测试project值为41 >> 13.00,12.00,3.00,7.00

*/
$projectList = array(0, 1, 11, 41);

$weekly = new weeklyTest();

r($weekly->getWorkloadByTypeTest($projectList[0])) && p('design,devel,test,study,discuss,ui,affair,misc') && e('577.00,568.00,563.00,556.00,557.00,571.00,563.00,566.00'); //测试project值为0
r($weekly->getWorkloadByTypeTest($projectList[1])) && p()                                                 && e('0');                                                       //测试project值为1
r($weekly->getWorkloadByTypeTest($projectList[2])) && p('design,devel,test,study,discuss,ui,affair,misc') && e('27.00,11.00,16.00,3.00,9.00,5.00,18.00,14.00');            //测试project值为11
r($weekly->getWorkloadByTypeTest($projectList[3])) && p('affair,test,study,discuss')                      && e('13.00,12.00,3.00,7.00');                                   //测试project值为41