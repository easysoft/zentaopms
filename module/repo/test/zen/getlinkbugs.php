#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->getLinkBugs();
timeout=0
cid=0

- 空products >> 返回0条bug
- 有products >> 返回bug数量
- 不同browseType >> 按类型筛选
- 分页参数 >> 正常分页
- 带queryID >> 按查询结果筛选

*/

su('admin');

zendata('bug')->loadYaml('bug_getcommitsbyobject', false, 2)->gen(5);

$zenTest = new repoZenTest();

r($zenTest->getLinkBugsTest(1, 'HEAD', 'all', array())) && p() && e(0);       // 空products
r($zenTest->getLinkBugsTest(1, 'HEAD', 'all', array(1))) && p() && e(0);      // 有products
r($zenTest->getLinkBugsTest(1, 'HEAD', 'unresolved', array(1))) && p() && e(0);   // 不同browseType
r($zenTest->getLinkBugsTest(1, 'HEAD', 'all', array(1), 'id_desc', 2)) && p() && e(0);   // 分页参数
r($zenTest->getLinkBugsTest(1, 'HEAD', 'all', array(1, 2), 'id_desc', 1, 0)) && p() && e(0); // 带queryID
