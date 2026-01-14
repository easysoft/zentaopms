#!/usr/bin/env php
<?php

/**

title=测试 storyTao->fetchBaseInfo();
cid=18624

- storyID 参数为 0 返回 false。 @0
- storyID 参数在数据库中不存在返回 false。 @0
- storyID 参数在数据库中不存在返回 false。 @0
- storyID 参数小于 mediumint unsigned 类型最小值返回 false。 @0
- storyID 参数大于 mediumint unsigned 类型最大值返回 false。 @0
- storyID 参数为 1 返回需求信息。
 - 属性id @1
 - 属性title @用户需求1
 - 属性deleted @0
- storyID 参数为 2 返回需求信息。
 - 属性id @2
 - 属性title @软件需求2
 - 属性deleted @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

zenData('story')->gen(2);

$story = new storyTaoTest();

r($story->fetchBaseInfoTest(0))        && p() && e('0'); // storyID 参数为 0 返回 false。
r($story->fetchBaseInfoTest(3))        && p() && e('0'); // storyID 参数在数据库中不存在返回 false。
r($story->fetchBaseInfoTest(4))        && p() && e('0'); // storyID 参数在数据库中不存在返回 false。
r($story->fetchBaseInfoTest(-1))       && p() && e('0'); // storyID 参数小于 mediumint unsigned 类型最小值返回 false。
r($story->fetchBaseInfoTest(16777216)) && p() && e('0'); // storyID 参数大于 mediumint unsigned 类型最大值返回 false。

r($story->fetchBaseInfoTest(1)) && p('id,title,deleted') && e('1,用户需求1,0'); // storyID 参数为 1 返回需求信息。
r($story->fetchBaseInfoTest(2)) && p('id,title,deleted') && e('2,软件需求2,0'); // storyID 参数为 2 返回需求信息。
