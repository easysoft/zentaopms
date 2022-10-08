#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/personnel.class.php';
su('admin');

/**

title=测试 personnelModel->getBugAndStoryInvest();
cid=1
pid=1

admin在项目集1下创建的bug数量 >> 28
admin在项目集1下解决的bug数量 >> 0
admin在项目集1下待处理的bug数量 >> 6
admin在项目集1下创建的用户需求数量 >> 9
admin在项目集1下创建的软件需求数量 >> 0

*/

global $tester;
$personnel = $tester->loadModel('personnel');
$accountList = array('admin' => 'admin');

r($personnel->getBugAndStoryInvest($accountList, 1)) && p('admin:createdBug')  && e('28'); //admin在项目集1下创建的bug数量
r($personnel->getBugAndStoryInvest($accountList, 1)) && p('admin:resolvedBug') && e('0');  //admin在项目集1下解决的bug数量
r($personnel->getBugAndStoryInvest($accountList, 1)) && p('admin:pendingBug')  && e('6');  //admin在项目集1下待处理的bug数量
r($personnel->getBugAndStoryInvest($accountList, 1)) && p('admin:UR')          && e('9');  //admin在项目集1下创建的用户需求数量
r($personnel->getBugAndStoryInvest($accountList, 1)) && p('admin:SR')          && e('0');  //admin在项目集1下创建的软件需求数量

