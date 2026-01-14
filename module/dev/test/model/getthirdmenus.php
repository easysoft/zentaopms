#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 devModel::getThirdMenus();
cid=16013
pid=1

检查传入空值时的情况 >> 0
检查获取地盘有三级的二级导航，并高亮待处理 >> work,1
检查获取项目有三级的二级导航，并高亮测试 >> qa,1

*/


$moduleList     = array('', 'my', 'scrum');
$moduleNameList = array('', 'my', 'scrum');
$methodList     = array('', 'work', 'qa');

global $tester;
$tester->loadModel('dev');
r($tester->dev->getThirdMenus($moduleList[0], $moduleNameList[0], $methodList[0])) && p()                  && e('0');      // 检查传入空值时的情况
r($tester->dev->getThirdMenus($moduleList[1], $moduleNameList[1], $methodList[1])) && p('0:method,active') && e('work,1'); // 检查获取地盘有三级的二级导航，并高亮待处理
r($tester->dev->getThirdMenus($moduleList[2], $moduleNameList[2], $methodList[2])) && p('0:method,active') && e('qa,1');   // 检查获取项目有三级的二级导航，并高亮测试
