#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getRepo();
timeout=0
cid=19461

- 执行tutorialTest模块的getRepoTest方法
 - 属性id @1
 - 属性name @Test repo
- 执行tutorialTest模块的getRepoTest方法
 - 属性SCM @Git
 - 属性encoding @utf-8
- 执行tutorialTest模块的getRepoTest方法 属性product @1
- 执行tutorialTest模块的getRepoTest方法 属性deleted @0
- 执行tutorialTest模块的getRepoTest方法
 - 属性commits @1
 - 属性synced @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

su('admin');

$tutorialTest = new tutorialTest();

r($tutorialTest->getRepoTest()) && p('id,name') && e('1,Test repo');
r($tutorialTest->getRepoTest()) && p('SCM,encoding') && e('Git,utf-8');
r($tutorialTest->getRepoTest()) && p('product') && e('1');
r($tutorialTest->getRepoTest()) && p('deleted') && e('0');
r($tutorialTest->getRepoTest()) && p('commits,synced') && e('1,0');