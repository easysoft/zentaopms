#!/usr/bin/env php
<?php

/**

title=测试 searchModel::deleteIndex();
timeout=0
cid=18297

- 执行search模块的deleteIndexTest方法，参数是'project', 1  @0
- 执行search模块的deleteIndexTest方法，参数是'story', 2  @0
- 执行search模块的deleteIndexTest方法，参数是'bug', 999  @0
- 执行search模块的deleteIndexTest方法，参数是'task', 0  @0
- 执行search模块的deleteIndexTest方法，参数是'', 5  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 缓冲zenData输出，避免干扰测试结果
ob_start();
zenData('searchindex')->gen(6);
ob_end_clean();

su('admin');

$search = new searchTest();

r($search->deleteIndexTest('project', 1)) && p() && e('0');
r($search->deleteIndexTest('story', 2)) && p() && e('0');
r($search->deleteIndexTest('bug', 999)) && p() && e('0');
r($search->deleteIndexTest('task', 0)) && p() && e('0');
r($search->deleteIndexTest('', 5)) && p() && e('0');