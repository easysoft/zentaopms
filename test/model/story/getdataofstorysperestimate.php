#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 storyModel->getDataOfStorysPerEstimate();
cid=1
pid=1

按照需求预计工时分组，获取分组后的需求数量 >> 21
按照需求预计工时分组，获取各个工时的需求数量，查看工时为19的数据 >> 17,28
按照需求预计工时分组，获取各个工时的需求数量，查看工时为20的数据 >> 1,28

*/

global $tester;
$tester->loadModel('story');

$data = $tester->story->getDataOfStorysPerEstimate();

r(count($data)) && p()                && e('21');    // 按照需求预计工时分组，获取分组后的需求数量
r($data)        && p('19:name,value') && e('17,28'); // 按照需求预计工时分组，获取各个工时的需求数量，查看工时为19的数据
r($data)        && p('20:name,value') && e('1,28');  // 按照需求预计工时分组，获取各个工时的需求数量，查看工时为20的数据