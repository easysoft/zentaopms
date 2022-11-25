#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/block.class.php';
su('admin');

/**

title=测试 blockModel->getProjectParams();
cid=1
pid=1

获取计划参数 >> {"type":{"name":"\u7c7b\u578b","options":{"all":"\u6240\u6709","undone":"\u672a\u5b8c\u6210","wait":"\u672a\u5f00\u59cb","doing":"\u8fdb\u884c\u4e2d","suspended":"\u5df2\u6302\u8d77","closed":"\u5df2\u5173\u95ed"},"control":"select"},"orderBy":{"name":"\u6392\u5e8f","options":{"id_asc":"ID \u9012\u589e","id_desc":"ID \u9012\u51cf","status_asc":"\u72b6\u6001\u6b63\u5e8f","status_desc":"\u72b6\u6001\u5012\u5e8f"},"control":"select"},"count":{"name":"\u6570\u91cf","default":20,"control":"input"}}
*/

$block = new blockTest();

r($block->getProjectParamsTest()) && p() && e('{"type":{"name":"\u7c7b\u578b","options":{"all":"\u6240\u6709","undone":"\u672a\u5b8c\u6210","wait":"\u672a\u5f00\u59cb","doing":"\u8fdb\u884c\u4e2d","suspended":"\u5df2\u6302\u8d77","closed":"\u5df2\u5173\u95ed"},"control":"select"},"orderBy":{"name":"\u6392\u5e8f","options":{"id_asc":"ID \u9012\u589e","id_desc":"ID \u9012\u51cf","status_asc":"\u72b6\u6001\u6b63\u5e8f","status_desc":"\u72b6\u6001\u5012\u5e8f"},"control":"select"},"count":{"name":"\u6570\u91cf","default":20,"control":"input"}}'); //获取计划参数
