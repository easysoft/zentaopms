#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

zdTable('project')->gen(50);
zdTable('user')->gen(20);
zdTable('risk')->gen(50);

su('admin');

/**

title=测试 personnelModel->getRiskInvest();
cid=1
pid=1

*/

$personnel = new personnelTest();
$programID = array(1, 2, 3, 4);

r($personnel->getRiskInvestTest($programID[0])) && p()  && e('admin:4,0,0;user12:0,0,0;user22:0,0,0;user32:0,0,0;');  //项目集 1 下的参与人的项目风险
r($personnel->getRiskInvestTest($programID[1])) && p()  && e('user3:0,0,0;user13:0,0,0;user23:0,0,0;user33:0,0,0;');  //项目集 2 下的参与人的项目风险
r($personnel->getRiskInvestTest($programID[2])) && p()  && e('user4:0,0,0;user14:0,0,0;user24:0,0,0;user34:0,0,0;');  //项目集 3 下的参与人的项目风险
r($personnel->getRiskInvestTest($programID[3])) && p()  && e('user5:0,0,0;user15:0,0,0;user25:0,0,0;user35:0,0,0;');  //项目集 4 下的参与人的项目风险
