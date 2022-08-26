#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php'; su('admin');
include dirname(dirname(dirname(__FILE__))) . '/class/bug.class.php';

/**

title=bugModel->getDataOfBugsPerResolution();
cid=1
pid=1

获取状态为fixed的数据 >> 已解决,45
获取状态为duplicate的数据 >> 重复Bug,30
获取状态为external的数据 >> 设计如此,18
获取状态为external的数据 >> 外部原因,18
获取状态为willnotfix的数据 >> 不予解决,18
获取状态为postponed的数据 >> 延期处理,12
获取状态为notrepro的数据 >> 无法重现,9

*/

$bug=new bugTest();
r($bug->getDataOfBugsPerResolutionTest()) && p('fixed:name,value')      && e('已解决,45');   // 获取状态为fixed的数据
r($bug->getDataOfBugsPerResolutionTest()) && p('duplicate:name,value')  && e('重复Bug,30');  // 获取状态为duplicate的数据
r($bug->getDataOfBugsPerResolutionTest()) && p('bydesign:name,value')   && e('设计如此,18'); // 获取状态为external的数据
r($bug->getDataOfBugsPerResolutionTest()) && p('external:name,value')   && e('外部原因,18'); // 获取状态为external的数据
r($bug->getDataOfBugsPerResolutionTest()) && p('willnotfix:name,value') && e('不予解决,18'); // 获取状态为willnotfix的数据
r($bug->getDataOfBugsPerResolutionTest()) && p('postponed:name,value')  && e('延期处理,12'); // 获取状态为postponed的数据
r($bug->getDataOfBugsPerResolutionTest()) && p('notrepro:name,value')   && e('无法重现,9');  // 获取状态为notrepro的数据