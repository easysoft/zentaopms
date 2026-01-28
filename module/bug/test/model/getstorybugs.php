#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('bug')->gen(300);

/**

title=bugModel->getStoryBugs();
cid=15397

- 测试获取关联需求ID为2的bug @BUG1,BUG101,BUG201


- 测试获取关联需求ID为6的bug @BUG2,BUG102,BUG202


- 测试获取关联需求ID为10的bug @BUG3,缺陷!()(){}|+=%^&*$#测试bug名称到底可以有多长！#￥%&*":.<>。?/（）;103,BUG203


- 测试获取关联需求ID为14的bug @BUG4,bug104,BUG204


- 测试获取关联需求ID为18的bug @BUG5,BUG105,BUG205


- 测试获取关联需求ID为22的bug @BUG6,BUG106,BUG206


- 测试获取不存在的关联需求的bug @0

*/

$storyIDList = array('2', '6', '10', '14', '18', '22', '1000001');

$bug=new bugModelTest();
r($bug->getStoryBugsTest($storyIDList[0])) && p() && e('BUG1,BUG101,BUG201'); // 测试获取关联需求ID为2的bug
r($bug->getStoryBugsTest($storyIDList[1])) && p() && e('BUG2,BUG102,BUG202'); // 测试获取关联需求ID为6的bug
r($bug->getStoryBugsTest($storyIDList[2])) && p() && e('BUG3,缺陷!()(){}|+=%^&*$#测试bug名称到底可以有多长！#￥%&*":.<>。?/（）;103,BUG203'); // 测试获取关联需求ID为10的bug
r($bug->getStoryBugsTest($storyIDList[3])) && p() && e('BUG4,bug104,BUG204'); // 测试获取关联需求ID为14的bug
r($bug->getStoryBugsTest($storyIDList[4])) && p() && e('BUG5,BUG105,BUG205'); // 测试获取关联需求ID为18的bug
r($bug->getStoryBugsTest($storyIDList[5])) && p() && e('BUG6,BUG106,BUG206'); // 测试获取关联需求ID为22的bug
r($bug->getStoryBugsTest($storyIDList[6])) && p() && e('0');                  // 测试获取不存在的关联需求的bug
