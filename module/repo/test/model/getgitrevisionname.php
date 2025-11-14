#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getGitRevisionName();
timeout=0
cid=18062

- 执行repoTest模块的getGitRevisionNameTest方法，参数是'd30919bdb9b4cf8e2698f4a6a30e41910427c01c', 0  @d30919bdb9
- 执行repoTest模块的getGitRevisionNameTest方法，参数是'd30919bdb9b4cf8e2698f4a6a30e41910427c01c', 2  @d30919bdb9<span title="第2次提交"> (2) </span>
- 执行repoTest模块的getGitRevisionNameTest方法，参数是'', 0  @0
- 执行repoTest模块的getGitRevisionNameTest方法，参数是'abc123', 0  @abc123
- 执行repoTest模块的getGitRevisionNameTest方法，参数是'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0', 5  @a1b2c3d4e5<span title="第5次提交"> (5) </span>
- 执行repoTest模块的getGitRevisionNameTest方法，参数是'1234567890abcdef', -1  @1234567890<span title="第-1次提交"> (-1) </span>
- 执行repoTest模块的getGitRevisionNameTest方法，参数是'abcdef1234567890', 999999  @abcdef1234<span title="第999999次提交"> (999999) </span>

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

su('admin');

$repoTest = new repoTest();

r($repoTest->getGitRevisionNameTest('d30919bdb9b4cf8e2698f4a6a30e41910427c01c', 0)) && p() && e('d30919bdb9');
r($repoTest->getGitRevisionNameTest('d30919bdb9b4cf8e2698f4a6a30e41910427c01c', 2)) && p() && e('d30919bdb9<span title="第2次提交"> (2) </span>');
r($repoTest->getGitRevisionNameTest('', 0)) && p() && e('0');
r($repoTest->getGitRevisionNameTest('abc123', 0)) && p() && e('abc123');
r($repoTest->getGitRevisionNameTest('a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6q7r8s9t0', 5)) && p() && e('a1b2c3d4e5<span title="第5次提交"> (5) </span>');
r($repoTest->getGitRevisionNameTest('1234567890abcdef', -1)) && p() && e('1234567890<span title="第-1次提交"> (-1) </span>');
r($repoTest->getGitRevisionNameTest('abcdef1234567890', 999999)) && p() && e('abcdef1234<span title="第999999次提交"> (999999) </span>');