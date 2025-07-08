#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';
su('admin');

zenData('bug')->gen(10);

/**

title=测试 searchModel->buildAllIndex();
timeout=0
cid=1

- 创建ID为1的bug的索引
 - 第0条的title属性 @bug1_
 - 第0条的objectID属性 @1
- 创建ID为2的bug的索引
 - 第1条的title属性 @bug2_
 - 第1条的objectID属性 @2
- 创建ID为3的bug的索引
 - 第2条的title属性 @bug3_
 - 第2条的objectID属性 @3
- 创建ID为4的bug的索引
 - 第3条的title属性 @bug4_
 - 第3条的objectID属性 @4
- 创建ID为5的bug的索引
 - 第4条的title属性 @bug5_
 - 第4条的objectID属性 @5

*/

$search = new searchTest();

$indexs = $search->buildAllIndexTest('bug');
$indexs = array_values($indexs);

r($indexs) && p('0:title,objectID') && e('bug1_,1');   //创建ID为1的bug的索引
r($indexs) && p('1:title,objectID') && e('bug2_,2');   //创建ID为2的bug的索引
r($indexs) && p('2:title,objectID') && e('bug3_,3');   //创建ID为3的bug的索引
r($indexs) && p('3:title,objectID') && e('bug4_,4');   //创建ID为4的bug的索引
r($indexs) && p('4:title,objectID') && e('bug5_,5');   //创建ID为5的bug的索引
