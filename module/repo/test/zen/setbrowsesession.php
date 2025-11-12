#!/usr/bin/env php
<?php

/**

title=测试 repoZen::setBrowseSession();
timeout=0
cid=0

- 测试步骤1: 正常情况下设置session >> 验证revisionList被正确设置为/repo-browse-1-master.html
- 测试步骤2: 带参数URI的情况 >> 验证包含参数的URI被正确存储,uriContainsParams为1
- 测试步骤3: session已存在旧数据的情况 >> 验证旧数据被新数据覆盖,dataUpdated为1
- 测试步骤4: URI为空的情况 >> 验证空URI被正确处理,isEmpty为1
- 测试步骤5: 复杂URI包含特殊字符的情况 >> 验证特殊字符URI被正确存储,hasSpecialChars为1
- 测试步骤6: 验证gitlabBranchList被正确设置 >> 验证gitlabBranchList值与revisionList相同
- 测试步骤7: 验证两个session变量值相同 >> 验证revisionList和gitlabBranchList值完全一致

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

su('admin');

$repoZenTest = new repoZenTest();

r($repoZenTest->setBrowseSessionTest('normal')) && p('revisionList') && e('/repo-browse-1-master.html');
r($repoZenTest->setBrowseSessionTest('with_params')) && p('uriContainsParams') && e('1');
r($repoZenTest->setBrowseSessionTest('session_exists')) && p('dataUpdated') && e('1');
r($repoZenTest->setBrowseSessionTest('empty_uri')) && p('isEmpty') && e('1');
r($repoZenTest->setBrowseSessionTest('complex_uri')) && p('hasSpecialChars') && e('1');
r($repoZenTest->setBrowseSessionTest('normal')) && p('gitlabBranchList') && e('/repo-browse-1-master.html');
r($repoZenTest->setBrowseSessionTest('with_params')) && p('revisionList,gitlabBranchList') && e('/repo-browse-2-develop-product-10.html,/repo-browse-2-develop-product-10.html');
