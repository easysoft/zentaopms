#!/usr/bin/env php
<?php

/**

title=测试 searchModel::setQuery();
timeout=0
cid=18311

- 执行searchTest模块的setQueryTest方法，参数是'task', 0  @1 = 1
- 执行searchTest模块的setQueryTest方法，参数是'bug', 0  @1 = 1
- 执行searchTest模块的setQueryTest方法，参数是'story', 0  @1 = 1
- 执行searchTest模块的setQueryTest方法，参数是'product', 0  @1 = 1
- 执行searchTest模块的setQueryTest方法，参数是'project', 0  @1 = 1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 用户登录
su('admin');

// 创建测试实例
$searchTest = new searchTest();

r($searchTest->setQueryTest('task', 0)) && p('') && e('1 = 1');
r($searchTest->setQueryTest('bug', 0)) && p('') && e('1 = 1');
r($searchTest->setQueryTest('story', 0)) && p('') && e('1 = 1');
r($searchTest->setQueryTest('product', 0)) && p('') && e('1 = 1');
r($searchTest->setQueryTest('project', 0)) && p('') && e('1 = 1');