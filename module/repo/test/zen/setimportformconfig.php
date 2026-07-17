#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen->setImportFormConfig();
timeout=0
cid=0

- type=Git @rray()
- type=Gitlab @rray()
- 带providerID @rray()
- type=Subversion @rray()
- acl=private @rray()

*/

su('admin');

$zenTest = new repoZenTest();

r($zenTest->setImportFormConfigTest('Git')) && p() && e(array());             // type=Git
r($zenTest->setImportFormConfigTest('Gitlab')) && p() && e(array());          // type=Gitlab
r($zenTest->setImportFormConfigTest('Git', 1)) && p() && e(array());          // 带providerID
r($zenTest->setImportFormConfigTest('Subversion')) && p() && e(array());      // type=Subversion
r($zenTest->setImportFormConfigTest('Git', 0, 'private')) && p() && e(array()); // acl=private