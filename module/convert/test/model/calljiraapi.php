#!/usr/bin/env php
<?php

/**

title=测试 convertModel::callJiraApi();
timeout=0
cid=15763

- 执行convertTest模块的callJiraAPITest方法，参数是'www.ztf.com'  @0
- 执行convertTest模块的callJiraAPITest方法，参数是'www.zentao.com'  @0
- 执行convertTest模块的callJiraAPITest方法，参数是'www.zsite.com'  @0
- 执行convertTest模块的callJiraAPITest方法，参数是'www.zdoo.com'  @0
- 执行convertTest模块的callJiraAPITest方法，参数是'www.xuanim.com'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$convertTest = new convertModelTest();

r($convertTest->callJiraAPITest('www.ztf.com'))    && p() && e('0');
r($convertTest->callJiraAPITest('www.zentao.com')) && p() && e('0');
r($convertTest->callJiraAPITest('www.zsite.com'))  && p() && e('0');
r($convertTest->callJiraAPITest('www.zdoo.com'))   && p() && e('0');
r($convertTest->callJiraAPITest('www.xuanim.com')) && p() && e('0');
