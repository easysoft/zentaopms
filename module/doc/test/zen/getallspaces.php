#!/usr/bin/env php
<?php

/**

title=测试 docZen::getAllSpaces();
timeout=0
cid=0

- 步骤1:默认参数属性mine @我的空间
- 步骤2:nomine参数属性mine @0
- 步骤3:onlymine参数属性mine @我的空间
- 步骤4:其他参数属性mine @我的空间
- 步骤5:onlymine不返回custom属性custom @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$docTest = new docZenTest();

r($docTest->getAllSpacesTest('')) && p('mine') && e('我的空间'); // 步骤1:默认参数
r($docTest->getAllSpacesTest('nomine')) && p('mine') && e('0'); // 步骤2:nomine参数
r($docTest->getAllSpacesTest('onlymine')) && p('mine') && e('我的空间'); // 步骤3:onlymine参数
r($docTest->getAllSpacesTest('other')) && p('mine') && e('我的空间'); // 步骤4:其他参数
r($docTest->getAllSpacesTest('onlymine')) && p('custom') && e('~~'); // 步骤5:onlymine不返回custom