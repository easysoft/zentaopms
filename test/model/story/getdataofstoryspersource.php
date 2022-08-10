#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/story.class.php';
su('admin');

/**

title=测试 storyModel->getDataOfStorysPerSource();
cid=1
pid=1

按照需求来源分组，获取分组后的需求数量 >> 14
按照需求来源分组，获取各个需求来源的需求数量，查看support下的数据 >> 技术支持,33
按照需求来源分组，获取各个需求来源的需求数量，查看market下的数据 >> 市场,33

*/

global $tester;
$tester->loadModel('story');
$tester->loadModel('report');

$data = $tester->story->getDataOfStorysPerSource();

r(count($data)) && p()                     && e('14');          // 按照需求来源分组，获取分组后的需求数量
r($data)        && p('support:name,value') && e('技术支持,33'); // 按照需求来源分组，获取各个需求来源的需求数量，查看support下的数据
r($data)        && p('market:name,value')  && e('市场,33');     // 按照需求来源分组，获取各个需求来源的需求数量，查看market下的数据