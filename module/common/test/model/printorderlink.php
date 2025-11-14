#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printOrderLink();
timeout=0
cid=15700

- 步骤1：基本排序链接生成 @<a href='/user-browse-orderBy=id_asc.html'  class='header' data-app=system>ID</a>
- 步骤2：当前字段升序排序链接 @<a href='/user-browse-orderBy=id_desc.html'  class='sort-up' data-app=system>ID</a>
- 步骤3：当前字段降序排序链接 @<a href='/user-browse-orderBy=id_asc.html'  class='sort-down' data-app=system>ID</a>
- 步骤4：移动端排序链接生成 @<a href='/task-browse-orderBy=name_asc.html'  class='header' data-app=system>Name</a>
- 步骤5：带反引号字段名处理 @<a href='/bug-browse-orderBy=status_asc.html'  class='sort-down' data-app=system>Status</a>

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

$commonTest = new commonTest();

r($commonTest->printOrderLinkTest('id', 'name_asc', 'orderBy=%s', 'ID', 'user', 'browse')) && p() && e("<a href='/user-browse-orderBy=id_asc.html'  class='header' data-app=system>ID</a>"); // 步骤1：基本排序链接生成
r($commonTest->printOrderLinkTest('id', 'id_asc', 'orderBy=%s', 'ID', 'user', 'browse')) && p() && e("<a href='/user-browse-orderBy=id_desc.html'  class='sort-up' data-app=system>ID</a>"); // 步骤2：当前字段升序排序链接
r($commonTest->printOrderLinkTest('id', 'id_desc', 'orderBy=%s', 'ID', 'user', 'browse')) && p() && e("<a href='/user-browse-orderBy=id_asc.html'  class='sort-down' data-app=system>ID</a>"); // 步骤3：当前字段降序排序链接
r($commonTest->printOrderLinkTest('name', 'id_asc', 'orderBy=%s', 'Name', 'task', 'browse', 'mhtml')) && p() && e("<a href='/task-browse-orderBy=name_asc.html'  class='header' data-app=system>Name</a>"); // 步骤4：移动端排序链接生成
r($commonTest->printOrderLinkTest('status', '`status`_desc', 'orderBy=%s', 'Status', 'bug', 'browse')) && p() && e("<a href='/bug-browse-orderBy=status_asc.html'  class='sort-down' data-app=system>Status</a>"); // 步骤5：带反引号字段名处理