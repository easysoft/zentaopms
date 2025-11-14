#!/usr/bin/env php
<?php

/**

title=测试 programZen::getLink();
timeout=0
cid=17728

- 执行programTest模块的getLinkTest方法，参数是'product', 'browse', '1', '', 'other'  @getlink.php?m=product&f=all&programID=1
- 执行programTest模块的getLinkTest方法，参数是'project', 'browse', '1', '', 'program'  @getlink.php?m=program&f=project&programID=1
- 执行programTest模块的getLinkTest方法，参数是'product', 'browse', '1', '', 'program'  @getlink.php?m=program&f=product&programID=1
- 执行programTest模块的getLinkTest方法，参数是'program', 'browse', '1', '', 'program'  @getlink.php?m=program&f=browse&programID=1
- 执行programTest模块的getLinkTest方法，参数是'product', 'browse', '1', '&status=doing', 'other'  @getlink.php?m=product&f=all&programID=1&status=doing
- 执行programTest模块的getLinkTest方法，参数是'program', 'browse', '100', '', 'program'  @getlink.php?m=program&f=browse&programID=100

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programzen.unittest.class.php';

su('admin');

$programTest = new programTest();

r($programTest->getLinkTest('product', 'browse', '1', '', 'other')) && p() && e('getlink.php?m=product&f=all&programID=1');
r($programTest->getLinkTest('project', 'browse', '1', '', 'program')) && p() && e('getlink.php?m=program&f=project&programID=1');
r($programTest->getLinkTest('product', 'browse', '1', '', 'program')) && p() && e('getlink.php?m=program&f=product&programID=1');
r($programTest->getLinkTest('program', 'browse', '1', '', 'program')) && p() && e('getlink.php?m=program&f=browse&programID=1');
r($programTest->getLinkTest('product', 'browse', '1', '&status=doing', 'other')) && p() && e('getlink.php?m=product&f=all&programID=1&status=doing');
r($programTest->getLinkTest('program', 'browse', '100', '', 'program')) && p() && e('getlink.php?m=program&f=browse&programID=100');