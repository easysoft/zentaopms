#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

zdTable('project')->gen(50);
zdTable('user')->gen(20);
zdTable('issue')->config('issue')->gen(50);
zdTable('team')->gen(50);

su('admin');

/**

title=测试 personnelModel->getIssueInvest();
cid=1
pid=1

*/

$personnel = new personnelTest();
$programID = array(1, 2, 3, 4);

r($personnel->getIssueInvestTest($programID[0])) && p()  && e('admin:4,4,1;user12:0,0,0;user22:0,0,0;user32:0,0,0;');  //项目集 1 下的参与人的项目风险
r($personnel->getIssueInvestTest($programID[1])) && p()  && e('user13:0,0,0;user23:0,0,0;user3:0,0,0;user33:0,4,0;');  //项目集 2 下的参与人的项目风险
r($personnel->getIssueInvestTest($programID[2])) && p()  && e('user14:0,0,0;user24:0,0,2;user34:0,0,0;user4:0,0,0;');  //项目集 3 下的参与人的项目风险
r($personnel->getIssueInvestTest($programID[3])) && p()  && e('user15:0,0,0;user25:4,0,0;user35:0,0,0;user5:0,0,0;');  //项目集 4 下的参与人的项目风险
