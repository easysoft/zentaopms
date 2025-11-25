#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('bug')->gen(20);

/**

title=bugModel->summary();
timeout=0
cid=15406

- 测试获取bugID 1,2,3,4,5 的统计信息 @本页共 <strong>5</strong> 个Bug，未解决 <strong>5</strong>。

- 测试获取bugID 6,7,8,9,10 的统计信息 @本页共 <strong>5</strong> 个Bug，未解决 <strong>5</strong>。

- 测试获取bugID 11,12,13,14,15 的统计信息 @本页共 <strong>5</strong> 个Bug，未解决 <strong>5</strong>。

- 测试获取bugID 空 的统计信息 @本页共 <strong>20</strong> 个Bug，未解决 <strong>20</strong>。

- 测试获取bugID 不存在 的统计信息 @本页共 <strong>0</strong> 个Bug，未解决 <strong>0</strong>。

*/

$bugIdList = array('1,2,3,4,5', '6,7,8,9,10', '11,12,13,14,15', '', '1000001');

$bug=new bugTest();
r($bug->summaryTest($bugIdList[0])) && p() && e('本页共 <strong>5</strong> 个Bug，未解决 <strong>5</strong>。'); // 测试获取bugID 1,2,3,4,5 的统计信息
r($bug->summaryTest($bugIdList[1])) && p() && e('本页共 <strong>5</strong> 个Bug，未解决 <strong>5</strong>。'); // 测试获取bugID 6,7,8,9,10 的统计信息
r($bug->summaryTest($bugIdList[2])) && p() && e('本页共 <strong>5</strong> 个Bug，未解决 <strong>5</strong>。'); // 测试获取bugID 11,12,13,14,15 的统计信息
r($bug->summaryTest($bugIdList[3])) && p() && e('本页共 <strong>20</strong> 个Bug，未解决 <strong>20</strong>。'); // 测试获取bugID 空 的统计信息
r($bug->summaryTest($bugIdList[4])) && p() && e('本页共 <strong>0</strong> 个Bug，未解决 <strong>0</strong>。'); // 测试获取bugID 不存在 的统计信息
