#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->gen(50);
zenData('user')->gen(20);
zenData('risk')->gen(50);
zenData('team')->gen(50);

su('admin');

/**

title=测试 personnelModel->getRiskInvest();
cid=17333

- 项目集 1 下的参与人的项目风险 @admin:4,0,0;user12:0,0,0;user22:0,0,0;user32:0,0,0;

- 项目集 2 下的参与人的项目风险 @user13:0,0,0;user23:0,0,0;user3:0,0,0;user33:0,0,0;

- 项目集 3 下的参与人的项目风险 @user14:0,0,0;user24:0,0,0;user34:0,0,0;user4:0,0,0;

- 项目集 4 下的参与人的项目风险 @user15:0,0,0;user25:0,0,0;user35:0,0,0;user5:0,0,0;

- 项目集 5 下的参与人的项目风险 @user16:0,0,0;user26:0,0,0;user36:0,0,0;user6:0,0,0;

*/

$personnel = new personnelModelTest();
$programID = array(1, 2, 3, 4, 5);

r($personnel->getRiskInvestTest($programID[0])) && p()  && e('admin:4,0,0;user12:0,0,0;user22:0,0,0;user32:0,0,0;');  //项目集 1 下的参与人的项目风险
r($personnel->getRiskInvestTest($programID[1])) && p()  && e('user13:0,0,0;user23:0,0,0;user3:0,0,0;user33:0,0,0;');  //项目集 2 下的参与人的项目风险
r($personnel->getRiskInvestTest($programID[2])) && p()  && e('user14:0,0,0;user24:0,0,0;user34:0,0,0;user4:0,0,0;');  //项目集 3 下的参与人的项目风险
r($personnel->getRiskInvestTest($programID[3])) && p()  && e('user15:0,0,0;user25:0,0,0;user35:0,0,0;user5:0,0,0;');  //项目集 4 下的参与人的项目风险
r($personnel->getRiskInvestTest($programID[4])) && p()  && e('user16:0,0,0;user26:0,0,0;user36:0,0,0;user6:0,0,0;');  //项目集 5 下的参与人的项目风险
