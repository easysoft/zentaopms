#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dev.class.php';

/**

title=测试 devModel::loadDefaultLang();
cid=1
pid=1

检查没有传值的情况    >> $URCOMMON
检查传入common的情况  >> $PRODUCTCOMMON
检查传入project的情况 >> 0

*/


$moduleList     = array('common', 'project');

global $tester;
$tester->loadModel('dev');
r($tester->dev->loadDefaultLang())                        && p('URCommon')      && e('$URCOMMON');      // 检查没有传值的情况
r($tester->dev->loadDefaultLang('zh-cn', $moduleList[0])) && p('productCommon') && e('$PRODUCTCOMMON'); // 检查传入common的情况
r($tester->dev->loadDefaultLang('zh-cn', $moduleList[1])) && p()                && e('0');              // 检查传入project的情况
