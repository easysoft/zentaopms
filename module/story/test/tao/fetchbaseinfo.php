#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/story.class.php';
su('admin');

zdTable('story')->gen(2);

/**

title=测试 storyTao->fetchBaseInfo();
cid=1
pid=1

*/

$story = new storyTest();

r($story->fetchBaseInfoTest(0))        && p() && e('0'); // storyID 参数为 0 返回 false。
r($story->fetchBaseInfoTest(3))        && p() && e('0'); // storyID 参数在数据库中不存在返回 false。
r($story->fetchBaseInfoTest(4))        && p() && e('0'); // storyID 参数在数据库中不存在返回 false。
r($story->fetchBaseInfoTest(-1))       && p() && e('0'); // storyID 参数小于 mediumint unsigned 类型最小值返回 false。
r($story->fetchBaseInfoTest(16777216)) && p() && e('0'); // storyID 参数大于 mediumint unsigned 类型最大值返回 false。

r($story->fetchBaseInfoTest(1)) && p('id,title,deleted') && e('1,用户需求1,0'); // storyID 参数为 1 返回需求信息。
r($story->fetchBaseInfoTest(2)) && p('id,title,deleted') && e('2,软件需求2,0'); // storyID 参数为 2 返回需求信息。
