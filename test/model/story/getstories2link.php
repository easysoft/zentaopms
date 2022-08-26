#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getStories2Link();
cid=1
pid=1

获取需求1可关联的需求数量 >> 2
获取需求2可关联的需求数量 >> 2
获取需求1可关联的需求id、product >> story,1
获取需求2可关联的需求id、product >> requirement,1

*/

global $tester;
$stories1 = $tester->loadModel('story')->getStories2Link(1, 'linkStories', 'bySearch', 0, 'requirement');
$stories2 = $tester->loadModel('story')->getStories2Link(2);

r(count($stories1)) && p()                 && e('2');             // 获取需求1可关联的需求数量
r(count($stories2)) && p()                 && e('2');             // 获取需求2可关联的需求数量
r($stories1)        && p('2:type,product') && e('story,1');       // 获取需求1可关联的需求id、product
r($stories2)        && p('1:type,product') && e('requirement,1'); // 获取需求2可关联的需求id、product