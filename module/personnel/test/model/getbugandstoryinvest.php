#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

zdTable('bug')->gen(50);
zdTable('story')->gen(50);
zdTable('product')->gen(10);
zdTable('team')->gen(50);
zdTable('user')->gen(20);

su('admin');

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
$accountList = array('admin' => 'admin', 'user2' => 'user2');
$programList = array(1, 2, 3);

$result1 = $personnel->getBugAndStoryInvest($accountList, $programList[0]);
$result2 = $personnel->getBugAndStoryInvest($accountList, $programList[1]);
$result3 = $personnel->getBugAndStoryInvest($accountList, $programList[2]);

r($result1) && p('admin:createdBug,resolvedBug,pendingBug,UR,SR')  && e('3,0,3,1,0');  // admin 在项目集1下的 bug 和需求数量
r($result1) && p('user2:createdBug,resolvedBug,pendingBug,UR,SR')  && e('0,0,0,0,1');  // user1 在项目集1下的 bug 和需求数量
r($result2) && p('admin:createdBug,resolvedBug,pendingBug,UR,SR')  && e('3,0,3,1,0');  // admin 在项目集2下的 bug 和需求数量
r($result2) && p('user2:createdBug,resolvedBug,pendingBug,UR,SR')  && e('0,0,0,0,1');  // user1 在项目集2下的 bug 和需求数量
r($result3) && p('admin:createdBug,resolvedBug,pendingBug,UR,SR')  && e('3,0,3,1,0');  // admin 在项目集3下的 bug 和需求数量
r($result3) && p('user2:createdBug,resolvedBug,pendingBug,UR,SR')  && e('0,0,0,0,1');  // user1 在项目集3下的 bug 和需求数量
