#!/usr/bin/env php
<?php

/**

title=测试 storyModel->reorderStories();
timeout=0
cid=0

- 无效重排，查看重排后的数量 @16
- 无效重排，查看重排后索引为3的需求ID属性3 @40
- 有效重排，查看重排后的数量 @22
- 有效重排，查看重排后索引为19的需求ID属性19 @19

*/
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

$onlyChildList = Array
(
    71  => (object)['id' => 71,  'parent' => 0,  'grade' => 1],
    68  => (object)['id' => 68,  'parent' => 0,  'grade' => 1],
    41  => (object)['id' => 41,  'parent' => 31, 'grade' => 2],
    40  => (object)['id' => 40,  'parent' => 28, 'grade' => 2],
    35  => (object)['id' => 35,  'parent' => 0,  'grade' => 1],
    34  => (object)['id' => 34,  'parent' => 0,  'grade' => 1],
    33  => (object)['id' => 33,  'parent' => 0,  'grade' => 1],
    31  => (object)['id' => 31,  'parent' => 0,  'grade' => 1],
    7   => (object)['id' => 7,   'parent' => 0,  'grade' => 1],
    6   => (object)['id' => 6,   'parent' => 0,  'grade' => 1],
    5   => (object)['id' => 5,   'parent' => 0,  'grade' => 1],
    4   => (object)['id' => 4,   'parent' => 0,  'grade' => 1],
    3   => (object)['id' => 3,   'parent' => 0,  'grade' => 1],
    2   => (object)['id' => 2,   'parent' => 0,  'grade' => 1],
    290 => (object)['id' => 290, 'parent' => 31, 'grade' => 2],
    1   => (object)['id' => 1,   'parent' => 0,  'grade' => 1]
);

$normalList = Array
(
    49 => (object)['id' => 49, 'parent' => 0,  'grade' => 1],
    48 => (object)['id' => 48, 'parent' => 0,  'grade' => 1],
    36 => (object)['id' => 36, 'parent' => 0,  'grade' => 1],
    34 => (object)['id' => 34, 'parent' => 0,  'grade' => 1],
    33 => (object)['id' => 33, 'parent' => 0,  'grade' => 1],
    32 => (object)['id' => 32, 'parent' => 0,  'grade' => 1],
    31 => (object)['id' => 31, 'parent' => 0,  'grade' => 1],
    30 => (object)['id' => 30, 'parent' => 0,  'grade' => 1],
    29 => (object)['id' => 29, 'parent' => 0,  'grade' => 1],
    28 => (object)['id' => 28, 'parent' => 0,  'grade' => 1],
    27 => (object)['id' => 27, 'parent' => 0,  'grade' => 1],
    26 => (object)['id' => 26, 'parent' => 0,  'grade' => 1],
    19 => (object)['id' => 19, 'parent' => 18, 'grade' => 2],
    18 => (object)['id' => 18, 'parent' => 0,  'grade' => 1],
    17 => (object)['id' => 17, 'parent' => 0,  'grade' => 1],
    16 => (object)['id' => 16, 'parent' => 0,  'grade' => 1],
    15 => (object)['id' => 15, 'parent' => 0,  'grade' => 1],
    14 => (object)['id' => 14, 'parent' => 0,  'grade' => 1],
    21 => (object)['id' => 21, 'parent' => 20, 'grade' => 4],
    13 => (object)['id' => 13, 'parent' => 0,  'grade' => 1],
    20 => (object)['id' => 20, 'parent' => 19, 'grade' => 3],
    7  => (object)['id' => 7 , 'parent' => 0,  'grade' => 1]
);

global $tester;
$tester->loadModel('story');

r(count($tester->story->reorderStories($onlyChildList))) && p('')   && e('16'); // 无效重排，查看重排后的数量
r($tester->story->reorderStories($onlyChildList))        && p('3')  && e('40'); // 无效重排，查看重排后索引为3的需求ID
r(count($tester->story->reorderStories($normalList)))    && p('')   && e('22'); // 有效重排，查看重排后的数量
r($tester->story->reorderStories($normalList))           && p('19') && e('19'); // 有效重排，查看重排后索引为19的需求ID
