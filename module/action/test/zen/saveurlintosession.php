#!/usr/bin/env php
<?php

/**

title=测试 actionZen::saveUrlIntoSession();
timeout=0
cid=14975

- 执行actionTest模块的saveUrlIntoSessionTest方法，参数是'/project-browse-1.html' 属性productList @/project-browse-1.html
- 执行actionTest模块的saveUrlIntoSessionTest方法，参数是'/bug-browse.html' 属性bugList @/bug-browse.html
- 执行actionTest模块的saveUrlIntoSessionTest方法，参数是'/test.html' 
 - 属性productList @/test.html
 - 属性bugList @/test.html
 - 属性docList @/test.html
- 执行actionTest模块的saveUrlIntoSessionTest方法，参数是'' 属性productList @~~
- 执行actionTest模块的saveUrlIntoSessionTest方法，参数是'/product-browse.html'  @26

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$actionTest = new actionZenTest();

r($actionTest->saveUrlIntoSessionTest('/project-browse-1.html'))      && p('productList')                 && e('/project-browse-1.html');
r($actionTest->saveUrlIntoSessionTest('/bug-browse.html'))            && p('bugList')                     && e('/bug-browse.html');
r($actionTest->saveUrlIntoSessionTest('/test.html'))                  && p('productList,bugList,docList') && e('/test.html,/test.html,/test.html');
r($actionTest->saveUrlIntoSessionTest(''))                            && p('productList')                 && e('~~');
r(count($actionTest->saveUrlIntoSessionTest('/product-browse.html'))) && p()                              && e(26);