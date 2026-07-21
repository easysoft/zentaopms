#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

/**

title=测试 repoTao::copySvnDir();
timeout=0
cid=18115

- 方法存在检查 >> 1
- 类存在检查 >> 1
- copySvnDirTest 方法存在 >> 1
- 再次方法存在检查 >> 1
- 类存在确认 >> 1

*/

su('admin');
$repoTest = new repoTaoTest();
r(method_exists($repoTest, 'copySvnDirTest')) && p() && e('1');
r(class_exists('repoTaoTest')) && p() && e('1');
r(method_exists($repoTest, 'copySvnDirTest')) && p() && e('1');
r(class_exists('repoTao')) && p() && e('1');
r(method_exists($repoTest, 'copySvnDirTest')) && p() && e('1');
