#!/usr/bin/env php
<?php

/**

title=测试 searchModel->getListCount();
timeout=0
cid=1

- 测试索引中所有类型的条数
 - 属性project @4
 - 属性story @3
 - 属性task @3
- 测试索引中指定类型的条数
 - 属性project @4
 - 属性story @3
- 测试索引中指定类型为空的条数属性bug @

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/search.class.php';
su('admin');

zdTable('searchindex')->gen(10);

$typeList = array('all', array('project', 'story'), array('bug'));

$search = new searchTest();
r($search->getListCountTest($typeList[0])) && p('project,story,task') && e('4,3,3'); //测试索引中所有类型的条数
r($search->getListCountTest($typeList[1])) && p('project,story') && e('4,3');        //测试索引中指定类型的条数
