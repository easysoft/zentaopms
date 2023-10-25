#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';
su('admin');

zdTable('bug')->gen(50);
zdTable('story')->gen(50);
zdTable('product')->gen(10);

/**

title=测试 personnelModel->getBugAndStoryInvest();
cid=1
pid=1

admin在项目集1下创建的bug数量      >> 3
admin在项目集1下解决的bug数量      >> 0
admin在项目集1下待处理的bug数量    >> 3
admin在项目集1下创建的用户需求数量 >> 1
admin在项目集1下创建的软件需求数量 >> 0

*/

global $tester;
$personnel = $tester->loadModel('personnel');
$accountList = array('admin' => 'admin');

r($personnel->getBugAndStoryInvest($accountList, 1)) && p('admin:createdBug')  && e('3');  //admin在项目集1下创建的bug数量
r($personnel->getBugAndStoryInvest($accountList, 1)) && p('admin:resolvedBug') && e('0');  //admin在项目集1下解决的bug数量
r($personnel->getBugAndStoryInvest($accountList, 1)) && p('admin:pendingBug')  && e('3');  //admin在项目集1下待处理的bug数量
r($personnel->getBugAndStoryInvest($accountList, 1)) && p('admin:UR')          && e('1');  //admin在项目集1下创建的用户需求数量
r($personnel->getBugAndStoryInvest($accountList, 1)) && p('admin:SR')          && e('0');  //admin在项目集1下创建的软件需求数量

