#!/usr/bin/env php
<?php

/**

title=测试 zanodeModel::setMenu();
timeout=0
cid=19845

- 执行zanodeTest模块的setMenuTest方法，参数是true
 - 属性beforeCall @1
 - 属性afterCall @1
 - 属性createHost @1
- 执行zanodeTest模块的setMenuTest方法，参数是false
 - 属性beforeCall @1
 - 属性afterCall @0
 - 属性createHost @0
- 执行zanodeTest模块的setMenuTest方法，参数是true 属性beforeCall @1
- 执行zanodeTest模块的setMenuTest方法，参数是true 属性afterCall @1
- 执行zanodeTest模块的setMenuTest方法，参数是false 属性afterCall @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$zanodeTest = new zanodeModelTest();

r($zanodeTest->setMenuTest(true)) && p('beforeCall,afterCall,createHost') && e('1,1,1');
r($zanodeTest->setMenuTest(false)) && p('beforeCall,afterCall,createHost') && e('1,0,0');
r($zanodeTest->setMenuTest(true)) && p('beforeCall') && e('1');
r($zanodeTest->setMenuTest(true)) && p('afterCall') && e('1');
r($zanodeTest->setMenuTest(false)) && p('afterCall') && e('0');