#!/usr/bin/env php
<?php

/**

title=测试 storyModel->reorderStories();
timeout=0
cid=18656

- 无效重排，查看重排后的数量 @16
- 无效重排，查看重排后索引为3的需求ID属性3 @3
- 有效重排，查看重排后的数量 @22
- 有效重排，查看重排后索引为19的需求ID属性19 @19
- 有效重排，查看重排后索引为20的需求ID属性20 @20

*/
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$onlyChildList = Array
(
    71  => 0,
    68  => 0,
    41  => 31,
    40  => 28,
    35  => 0,
    34  => 0,
    33  => 0,
    31  => 0,
    7   => 0,
    6   => 0,
    5   => 0,
    4   => 0,
    3   => 0,
    2   => 0,
    290 => 31,
    1   => 0,
);

$normalList = Array
(
    49 => 0,
    48 => 0,
    36 => 0,
    34 => 0,
    33 => 0,
    32 => 0,
    31 => 0,
    30 => 0,
    29 => 0,
    28 => 0,
    27 => 0,
    26 => 0,
    19 => 18,
    18 => 0,
    17 => 0,
    16 => 0,
    15 => 0,
    14 => 0,
    21 => 20,
    13 => 0,
    20 => 19,
    7  => 0,
);

global $tester;
$tester->loadModel('story');

r(count($tester->story->reorderStories($onlyChildList))) && p('')   && e('16'); // 无效重排，查看重排后的数量
r($tester->story->reorderStories($onlyChildList))        && p('3')  && e('3');  // 无效重排，查看重排后索引为3的需求ID
r(count($tester->story->reorderStories($normalList)))    && p('')   && e('22'); // 有效重排，查看重排后的数量
r($tester->story->reorderStories($normalList))           && p('19') && e('19'); // 有效重排，查看重排后索引为19的需求ID
r($tester->story->reorderStories($normalList))           && p('20') && e('20'); // 有效重排，查看重排后索引为20的需求ID