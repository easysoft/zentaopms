#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/doc.class.php';
su('admin');

/**

title=测试 docModel->getAllLibGroups();
cid=1
pid=1

查询产品库 >> 正常产品1
查询项目库 >> 项目主库
查询执行库 >> 子阶段1
查询产品库统计 >> 100
查询项目库统计 >> 90
查询执行库统计 >> 603

*/
global $tester;
$doc = $tester->loadModel('doc');

$appendLibs = array('17', '1');

r($doc->getAllLibGroups($appendLibs)['product'])          && p('1:name')   && e('正常产品1');//查询产品库
r($doc->getAllLibGroups($appendLibs)['project'])          && p('117')      && e('项目主库'); //查询项目库
r($doc->getAllLibGroups($appendLibs)['execution'])        && p('701:name') && e('子阶段1');  //查询执行库
r(count($doc->getAllLibGroups($appendLibs)['product']))   && p()           && e('100');      //查询产品库统计
r(count($doc->getAllLibGroups($appendLibs)['project']))   && p()           && e('90');       //查询项目库统计
r(count($doc->getAllLibGroups($appendLibs)['execution'])) && p()           && e('603');      //查询执行库统计