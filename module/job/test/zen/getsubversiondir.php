#!/usr/bin/env php
<?php

/**

title=测试 jobZen::getSubversionDir();
timeout=0
cid=0

- 步骤1：Subversion类型有prefix属性/ @/trunk/src
- 步骤2：Subversion类型无prefix属性/ @/
- 步骤3：Git类型 @0
- 步骤4：空SCM字段 @0
- 步骤5：Subversion类型有tags属性/ @/trunk

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/job.unittest.class.php';

zenData('repo')->loadYaml('repo_getsubversiondir', false, 2)->gen(5);

su('admin');

$jobTest = new jobTest();

$svnRepo1 = new stdclass();
$svnRepo1->SCM = 'Subversion';
$svnRepo1->prefix = '/trunk/src';

$svnRepo2 = new stdclass();
$svnRepo2->SCM = 'Subversion';
$svnRepo2->prefix = '';

$gitRepo = new stdclass();
$gitRepo->SCM = 'Git';
$gitRepo->prefix = '';

$emptyRepo = new stdclass();
$emptyRepo->SCM = '';
$emptyRepo->prefix = '';

$svnRepoNoTags = new stdclass();
$svnRepoNoTags->SCM = 'Subversion';
$svnRepoNoTags->prefix = '/trunk';

r($jobTest->getSubversionDirTest($svnRepo1)) && p('/') && e('/trunk/src'); // 步骤1：Subversion类型有prefix
r($jobTest->getSubversionDirTest($svnRepo2)) && p('/') && e('/'); // 步骤2：Subversion类型无prefix
r($jobTest->getSubversionDirTest($gitRepo)) && p() && e('0'); // 步骤3：Git类型
r($jobTest->getSubversionDirTest($emptyRepo)) && p() && e('0'); // 步骤4：空SCM字段
r($jobTest->getSubversionDirTest($svnRepoNoTags)) && p('/') && e('/trunk'); // 步骤5：Subversion类型有tags