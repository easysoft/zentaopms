#!/usr/bin/env php
<?php
declare(strict_types=1);
/**

title=测试 upgradeModel->updateBISQL();
cid=1

- 判断修改的sql是否正确 @1
- 判断修改的desc是否正确 @1
- 判断修改的sql是否正确 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

zdTable('user')->gen(5);
su('admin');

$errorValue = array();
$errorValue[1025] = 'select *,if($product=\'\',0,product) as customproduct from zt_bug where deleted=\'0\' and resolution!=\'\' and if($startDate=\'\',1,resolvedDate>=$startDate) and if($endDate=\'\',1,resolvedDate<=$endDate) having customproduct=$product';
$errorValue[1021] = 'select t1.id,t1.name,t2.stories,(t2.stories-t2.undone) as doneStory,t3.bugs,t3.resolutions,round(t3.bugs/(t2.stories-t2.undone),2) as bugthanstory,t3.seriousBugs from zt_product as t1 \r\nleft join ztv_productstories as t2 on t1.id=t2.product \r\nleft join ztv_productbugs as t3 on t1.id=t3.product \r\nleft join zt_project as t4 on t1.program=t4.id \r\nwhere t1.deleted=\'0\'\r\norder by t4.`order` asc, t1.line desc, t1.`order` asc';
$errorValue[1023] = 'select t1.account,t1.consumed,t1.`date`,if($dept=\'0\',0,t2.dept) as dept from zt_effort as t1 left join zt_user as t2 on t1.account=t2.account where t1.`deleted`=\'0\' and if($startDate=\'\',1,t1.`date`>=$startDate) and if($endDate=\'\',1,t1.`date`<=$endDate) having dept=$dept order by `date` asc';

$rightValue = array();
$rightValue[1025] = 'select t1.*,if($product=\'\',0,t1.product) as customproduct from zt_bug as t1 left join zt_product as t2 on t1.product = t2.id where t1.deleted=\'0\' and t2.deleted=\'0\' and t1.resolution!=\'\' and if($startDate=\'\',1,t1.resolvedDate>=$startDate) and if($endDate=\'\',1,t1.resolvedDate<=$endDate) having customproduct=$product';
$rightValue[1021] = '{"zh-cn":"\\u5217\\u51fa\\u4ea7\\u54c1\\u7684\\u9700\\u6c42\\u6570\\uff0c\\u5b8c\\u6210\\u7684\\u9700\\u6c42\\u603b\\u6570\\uff0cBug\\u6570\\uff0c\\u89e3\\u51b3\\u7684Bug\\u603b\\u6570\\uff0cBug\\/\\u9700\\u6c42\\uff0c\\u91cd\\u8981Bug\\u6570\\u91cf(\\u4e25\\u91cd\\u7a0b\\u5ea6\\u5c0f\\u4e8e3)\\u3002","zh-tw":"\\u5217\\u51fa\\u7522\\u54c1\\u7684\\u9700\\u6c42\\u6578\\uff0c\\u5b8c\\u6210\\u7684\\u9700\\u6c42\\u7e3d\\u6578\\uff0cBug\\u6578\\uff0c\\u89e3\\u6c7a\\u7684Bug\\u7e3d\\u6578\\uff0cBug\\/\\u9700\\u6c42\\uff0c\\u91cd\\u8981Bug\\u6578\\u91cf(\\u56b4\\u91cd\\u7a0b\\u5ea6\\u5c0f\\u65bc3)\\u3002","en":"Serious Bug (severity is less than 3).","de":"Serious Bug (severity is less than 3).","fr":"Serious Bug (severity is less than 3)."}';
$rightValue[1023] = 'select t1.account, t1.consumed, t1.`date`, if($dept=\'\', 0, t2.dept) as dept from zt_effort as t1 left join zt_user as t2 on t1.account = t2.account left join zt_dept as t3 on t2.dept = t3.id where t1.`deleted` = \'0\' and if($startDate=\'\', 1, t1.`date` >= $startDate) and if($endDate=\'\', 1, t1.`date` <= $endDate) and (t3.path like concat((select path from zt_dept where id=$dept), \'%\') or $dept=0) order by t1.`date` asc';

$upgrade = new upgradeTest();
r($upgrade->updateBISQLTest(1025, $errorValue[1025], $rightValue[1025], 'sql'))  && p() && e('1'); //判断修改的sql是否正确
r($upgrade->updateBISQLTest(1021, $errorValue[1021], $rightValue[1021], 'desc')) && p() && e('1'); //判断修改的desc是否正确
r($upgrade->updateBISQLTest(1023, $errorValue[1023], $rightValue[1023], 'sql'))  && p() && e('1'); //判断修改的sql是否正确
