#!/usr/bin/env php
<?php

/**

title=测试 productZen::saveAndModifyCookie4Browse();
timeout=0
cid=17605

- 执行productTest模块的saveAndModifyCookie4BrowseTest方法，参数是2, 'all', 0, '', 'id_desc', '1', 'all', '100' 属性storyModule @0
- 执行productTest模块的saveAndModifyCookie4BrowseTest方法，参数是1, 'branch2', 0, '', 'id_desc', '1', 'branch1', '100' 属性storyModule @0
- 执行productTest模块的saveAndModifyCookie4BrowseTest方法，参数是1, 'all', 10, 'bymodule', 'id_desc', '1', 'all', '0' 属性storyModule @10
- 执行productTest模块的saveAndModifyCookie4BrowseTest方法，参数是1, 'all', 20, 'bymodule', 'id_desc', '1', 'all', '0', 'project' 属性storyModuleParam @20
- 执行productTest模块的saveAndModifyCookie4BrowseTest方法，参数是1, 'branch1', 0, 'bybranch', 'id_desc', '1', 'all', '0' 属性storyBranch @branch1
- 执行productTest模块的saveAndModifyCookie4BrowseTest方法，参数是1, 'all', 0, '', 'pri_desc', '1', 'all', '0' 属性productStoryOrder @pri_desc
- 执行productTest模块的saveAndModifyCookie4BrowseTest方法，参数是5, 'all', 0, '', 'id_desc', '1', 'all', '0' 属性preProductID @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

/* 测试步骤1:产品切换时清除storyModule cookie。*/
r($productTest->saveAndModifyCookie4BrowseTest(2, 'all', 0, '', 'id_desc', '1', 'all', '100')) && p('storyModule') && e('0');

/* 测试步骤2:分支切换时清除storyModule cookie。*/
r($productTest->saveAndModifyCookie4BrowseTest(1, 'branch2', 0, '', 'id_desc', '1', 'branch1', '100')) && p('storyModule') && e('0');

/* 测试步骤3:按模块浏览时设置storyModule cookie。*/
r($productTest->saveAndModifyCookie4BrowseTest(1, 'all', 10, 'bymodule', 'id_desc', '1', 'all', '0')) && p('storyModule') && e('10');

/* 测试步骤4:项目视图中按模块浏览时设置storyModuleParam。*/
r($productTest->saveAndModifyCookie4BrowseTest(1, 'all', 20, 'bymodule', 'id_desc', '1', 'all', '0', 'project')) && p('storyModuleParam') && e('20');

/* 测试步骤5:按分支浏览时设置storyBranch cookie。*/
r($productTest->saveAndModifyCookie4BrowseTest(1, 'branch1', 0, 'bybranch', 'id_desc', '1', 'all', '0')) && p('storyBranch') && e('branch1');

/* 测试步骤6:设置产品需求列表排序cookie。*/
r($productTest->saveAndModifyCookie4BrowseTest(1, 'all', 0, '', 'pri_desc', '1', 'all', '0')) && p('productStoryOrder') && e('pri_desc');

/* 测试步骤7:设置preProductID cookie。*/
r($productTest->saveAndModifyCookie4BrowseTest(5, 'all', 0, '', 'id_desc', '1', 'all', '0')) && p('preProductID') && e('5');