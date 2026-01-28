#!/usr/bin/env php
<?php

/**

title=测试 commonModel::getDotStyle();
timeout=0
cid=15673

- 执行commonTest模块的getDotStyleTest方法，参数是true, 15
 - 属性top @-3px
 - 属性right @-10px
 - 属性aspect-ratio @0
 - 属性padding @2px
- 执行commonTest模块的getDotStyleTest方法，参数是true, 5
 - 属性top @-3px
 - 属性right @-5px
 - 属性aspect-ratio @0
 - 属性padding @2px
- 执行commonTest模块的getDotStyleTest方法，参数是false, 15
 - 属性top @-2px
 - 属性right @-2px
 - 属性aspect-ratio @1 / 1
 - 属性width @5px
 - 属性height @5px
- 执行commonTest模块的getDotStyleTest方法，参数是false, 5
 - 属性top @-2px
 - 属性right @-2px
 - 属性aspect-ratio @1 / 1
 - 属性width @5px
 - 属性height @5px
- 执行commonTest模块的getDotStyleTest方法，参数是false, 0
 - 属性top @-2px
 - 属性right @-2px
 - 属性aspect-ratio @1 / 1
 - 属性width @5px
 - 属性height @5px

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$commonTest = new commonModelTest();

r($commonTest->getDotStyleTest(true, 15)) && p('top,right,aspect-ratio,padding') && e('-3px,-10px,0,2px');
r($commonTest->getDotStyleTest(true, 5)) && p('top,right,aspect-ratio,padding') && e('-3px,-5px,0,2px');
r($commonTest->getDotStyleTest(false, 15)) && p('top,right,aspect-ratio,width,height') && e('-2px,-2px,1 / 1,5px,5px');
r($commonTest->getDotStyleTest(false, 5)) && p('top,right,aspect-ratio,width,height') && e('-2px,-2px,1 / 1,5px,5px');
r($commonTest->getDotStyleTest(false, 0)) && p('top,right,aspect-ratio,width,height') && e('-2px,-2px,1 / 1,5px,5px');