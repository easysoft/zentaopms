#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 chartModel->getPairs().
timeout=0
cid=1

- 判断获取的键值对是否正确。
 - 属性1 @node1
 - 属性5 @node3
 - 属性6 @physics3
 - 属性10 @physics5
- 判断第一位的值是否正确，验证按照id正序排序是否生效。 @node1
- 判断获取的键值对是否正确。
 - 属性1 @node1
 - 属性5 @node3
 - 属性6 @physics3
 - 属性10 @physics5
- 判断第一位的值是否正确，验证按照id倒序排序是否生效。 @physics5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/zanode.class.php';

zdTable('host')->config('host')->gen(10);

$orderByList = array('', 'id_desc');

$zanode = new zanodeTest();

$result = $zanode->getPairs($orderByList[0]);
r($result) && p('1,5,6,10') && e('node1,node3,physics3,physics5');   //判断获取的键值对是否正确。
r(current($result)) && p('') && e('node1');                          //判断第一位的值是否正确，验证按照id正序排序是否生效。

$result1 = $zanode->getPairs($orderByList[1]);
r($result1) && p('1,5,6,10') && e('node1,node3,physics3,physics5');   //判断获取的键值对是否正确。
r(current($result1)) && p('') && e('physics5');                        //判断第一位的值是否正确，验证按照id倒序排序是否生效。