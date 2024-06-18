#!/usr/bin/env php
<?php

/**

title=测试 docModel->updateDocFile();
cid=1

- 测试将文件ID为1的文件从文档ID为1的文档中移除属性files @2
- 测试将文件ID为4的文件从文档ID为1的文档中移除属性files @2
- 测试将文件ID为7的文件从文档ID为1的文档中移除属性files @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('file')->gen(6);
