#!/usr/bin/env php
<?php

/**

title=测试 weeklyModel::getPageNav();
timeout=0
cid=19725

- 执行weekly模块的getPageNavTest方法，参数是'19', '2022-05-07'  @报告-项目19
- 执行weekly模块的getPageNavTest方法，参数是'13', '2022-05-07'  @报告-项目13
- 执行weekly模块的getPageNavTest方法，参数是'17', '2022-05-07'  @报告-项目17
- 执行weekly模块的getPageNavTest方法，参数是'18', '2022-05-07'  @报告-项目18
- 执行weekly模块的getPageNavTest方法，参数是'19', ''  @报告-项目19

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/weekly.unittest.class.php';

// 无需准备数据库数据，使用模拟对象

su('admin');

$weekly = new weeklyTest();

r($weekly->getPageNavTest('19', '2022-05-07')) && p() && e('报告-项目19');
r($weekly->getPageNavTest('13', '2022-05-07')) && p() && e('报告-项目13');
r($weekly->getPageNavTest('17', '2022-05-07')) && p() && e('报告-项目17');
r($weekly->getPageNavTest('18', '2022-05-07')) && p() && e('报告-项目18');
r($weekly->getPageNavTest('19', '')) && p() && e('报告-项目19');