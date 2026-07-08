#!/usr/bin/env php
<?php
/**

title=测试 repoZen::setBrowseSession();
timeout=0
cid=1

- 执行repoZenTest模块的setBrowseSessionTest方法，参数是'normal' 属性revisionList @/repo-browse-1-master.html
- 执行repoZenTest模块的setBrowseSessionTest方法，参数是'with_params' 属性uriContainsParams @1
- 执行repoZenTest模块的setBrowseSessionTest方法，参数是'session_exists' 属性dataUpdated @1
- 执行repoZenTest模块的setBrowseSessionTest方法，参数是'empty_uri' 属性isEmpty @1
- 执行repoZenTest模块的setBrowseSessionTest方法，参数是'complex_uri' 属性hasSpecialChars @1
- 执行repoZenTest模块的setBrowseSessionTest方法，参数是'normal' 属性gitlabBranchList @/repo-browse-1-master.html
- 执行repoZenTest模块的setBrowseSessionTest方法，参数是'with_params'
 - 属性revisionList @/repo-browse-2-develop-product-10.html
 - 属性gitlabBranchList @/repo-browse-2-develop-product-10.html

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

su('admin');

$repoZenTest = new repoZenTest();

r($repoZenTest->setBrowseSessionTest('normal'))         && p('revisionList')                  && e('/repo-browse-1-master.html');
r($repoZenTest->setBrowseSessionTest('with_params'))    && p('uriContainsParams')             && e('1');
r($repoZenTest->setBrowseSessionTest('session_exists')) && p('dataUpdated')                   && e('1');
r($repoZenTest->setBrowseSessionTest('empty_uri'))      && p('isEmpty')                       && e('1');
r($repoZenTest->setBrowseSessionTest('complex_uri'))    && p('hasSpecialChars')               && e('1');
r($repoZenTest->setBrowseSessionTest('normal'))         && p('gitlabBranchList')              && e('/repo-browse-1-master.html');
r($repoZenTest->setBrowseSessionTest('with_params'))    && p('revisionList,gitlabBranchList') && e('/repo-browse-2-develop-product-10.html,/repo-browse-2-develop-product-10.html');
