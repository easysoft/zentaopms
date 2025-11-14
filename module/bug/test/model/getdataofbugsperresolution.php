#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';
su('admin');

zenData('bug')->loadYaml('resolution')->gen(15);

/**

title=bugModel->getDataOfBugsPerResolution();
timeout=0
cid=15370

- 获取解决方案为fixed的数据
 - 第fixed条的name属性 @已解决
 - 第fixed条的value属性 @5

- 获取解决方案为duplicate的数据
 - 第duplicate条的name属性 @重复Bug
 - 第duplicate条的value属性 @2

- 获取解决方案为external的数据
 - 第bydesign条的name属性 @设计如此
 - 第bydesign条的value属性 @2

- 获取解决方案为external的数据
 - 第external条的name属性 @外部原因
 - 第external条的value属性 @2

- 获取解决方案为willnotfix的数据
 - 第willnotfix条的name属性 @不予解决
 - 第willnotfix条的value属性 @1

- 获取解决方案为postponed的数据
 - 第postponed条的name属性 @延期处理
 - 第postponed条的value属性 @1

- 获取解决方案为notrepro的数据
 - 第notrepro条的name属性 @无法重现
 - 第notrepro条的value属性 @1

*/

$bug = new bugTest();
r($bug->getDataOfBugsPerResolutionTest()) && p('fixed:name,value')      && e('已解决,5');   //获取解决方案为 fixed 的数据
r($bug->getDataOfBugsPerResolutionTest()) && p('duplicate:name,value')  && e('重复Bug,2');  //获取解决方案为 duplicate 的数据
r($bug->getDataOfBugsPerResolutionTest()) && p('bydesign:name,value')   && e('设计如此,2'); //获取解决方案为 external 的数据
r($bug->getDataOfBugsPerResolutionTest()) && p('external:name,value')   && e('外部原因,2'); //获取解决方案为 external 的数据
r($bug->getDataOfBugsPerResolutionTest()) && p('willnotfix:name,value') && e('不予解决,1'); //获取解决方案为 willnotfix 的数据
r($bug->getDataOfBugsPerResolutionTest()) && p('postponed:name,value')  && e('延期处理,1'); //获取解决方案为 postponed 的数据
r($bug->getDataOfBugsPerResolutionTest()) && p('notrepro:name,value')   && e('无法重现,1'); //获取解决方案为 notrepro 的数据
