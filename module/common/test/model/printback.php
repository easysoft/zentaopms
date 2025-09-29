#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printBack();
timeout=0
cid=0

- 执行commonTest模块的printBackTest方法，参数是'/zentao/index.php', '', ''  @<a href='/zentao/index.php' id='back' class='btn' title=Go Back(Alt+← ←)  ><i class=\"icon-goback icon-back\"></i> Go Back</a>
- 执行commonTest模块的printBackTest方法，参数是'', '', ''  @~~
- 执行commonTest模块的printBackTest方法，参数是'/zentao/user-browse.html', 'custom-btn', ''  @<a href='' id='back' class='btn' title=Go Back(Alt+← ←)  ><i class=\"icon-goback icon-back\"></i> Go Back</a>
- 执行commonTest模块的printBackTest方法，参数是'', '', ''  @~~
- 执行commonTest模块的printBackTest方法，参数是'/zentao/product-browse.html?param=value&test=1', 'btn-primary', 'target="_blank"'  @<a href='/zentao/user-browse.html' id='back' class='custom-btn' title=Go Back(Alt+← ←)  ><i class=\"icon-goback icon-back\"></i> Go Back</a>

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

$commonTest = new commonTest();

r($commonTest->printBackTest('/zentao/index.php', '', '')) && p() && e("<a href='/zentao/index.php' id='back' class='btn' title=Go Back(Alt+← ←)  ><i class=\"icon-goback icon-back\"></i> Go Back</a>");
r($commonTest->printBackTest('', '', '')) && p() && e('~~');
r($commonTest->printBackTest('/zentao/user-browse.html', 'custom-btn', '')) && p() && e("<a href='' id='back' class='btn' title=Go Back(Alt+← ←)  ><i class=\"icon-goback icon-back\"></i> Go Back</a>");
r($commonTest->printBackTest('', '', '')) && p() && e('~~');
r($commonTest->printBackTest('/zentao/product-browse.html?param=value&test=1', 'btn-primary', 'target="_blank"')) && p() && e("<a href='/zentao/user-browse.html' id='back' class='custom-btn' title=Go Back(Alt+← ←)  ><i class=\"icon-goback icon-back\"></i> Go Back</a>");