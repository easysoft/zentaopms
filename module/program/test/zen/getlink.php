#!/usr/bin/env php
<?php

/**

title=测试 programZen::getLink();
timeout=0
cid=0

- 执行programTest模块的getLinkTest方法，参数是'program', 'browse', '1'  @program-browse-1.html
- 执行programTest模块的getLinkTest方法，参数是'project', 'browse', '1'  @program-project-1.html
- 执行programTest模块的getLinkTest方法，参数是'product', 'browse', '1'  @program-product-1.html
- 执行programTest模块的getLinkTest方法，参数是'task', 'browse', '1', '', 'product'  @product-all-1.html
- 执行programTest模块的getLinkTest方法，参数是'program', 'browse', '1', '&status=doing'  @program-browse-1-doing.html

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/program.unittest.class.php';

su('admin');

$programTest = new programTest();

r($programTest->getLinkTest('program', 'browse', '1')) && p() && e('program-browse-1.html');
r($programTest->getLinkTest('project', 'browse', '1')) && p() && e('program-project-1.html');
r($programTest->getLinkTest('product', 'browse', '1')) && p() && e('program-product-1.html');
r($programTest->getLinkTest('task', 'browse', '1', '', 'product')) && p() && e('product-all-1.html');
r($programTest->getLinkTest('program', 'browse', '1', '&status=doing')) && p() && e('program-browse-1-doing.html');