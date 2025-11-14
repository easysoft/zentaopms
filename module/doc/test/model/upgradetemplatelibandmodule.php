#!/usr/bin/env php
<?php

/**

title=测试 docModel->upgradeTemplateLibAndModule();
timeout=0
cid=16164

- 更新模板1的字段值
 - 属性lib @1
 - 属性module @1
 - 属性templateType @PP
- 更新模板1的字段值
 - 属性lib @1
 - 属性module @2
 - 属性templateType @QAP
- 更新模板1的字段值
 - 属性lib @1
 - 属性module @3
 - 属性templateType @CMP
- 更新模板4的字段值
 - 属性lib @1
 - 属性module @4
 - 属性templateType @custom1
- 更新模板5的字段值
 - 属性lib @1
 - 属性module @4
 - 属性templateType @custom1
- 更新模板6的字段值
 - 属性lib @2
 - 属性module @5
 - 属性templateType @ITP
- 更新模板7的字段值
 - 属性lib @2
 - 属性module @6
 - 属性templateType @ERS
- 更新模板8的字段值
 - 属性lib @2
 - 属性module @7
 - 属性templateType @URS
- 更新模板9的字段值
 - 属性lib @2
 - 属性module @8
 - 属性templateType @SRS
- 更新模板10的字段值
 - 属性lib @2
 - 属性module @9
 - 属性templateType @custom2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('doc')->loadYaml('template')->gen(20);
zenData('user')->gen(5);
su('admin');

$docTester = new docTest();
r($docTester->upgradeTemplateLibAndModuleTest(1))  && p('lib,module,templateType') && e('1,1,PP');      // 更新模板1的字段值
r($docTester->upgradeTemplateLibAndModuleTest(2))  && p('lib,module,templateType') && e('1,2,QAP');     // 更新模板1的字段值
r($docTester->upgradeTemplateLibAndModuleTest(3))  && p('lib,module,templateType') && e('1,3,CMP');     // 更新模板1的字段值
r($docTester->upgradeTemplateLibAndModuleTest(4))  && p('lib,module,templateType') && e('1,4,custom1'); // 更新模板4的字段值
r($docTester->upgradeTemplateLibAndModuleTest(5))  && p('lib,module,templateType') && e('1,4,custom1'); // 更新模板5的字段值
r($docTester->upgradeTemplateLibAndModuleTest(6))  && p('lib,module,templateType') && e('2,5,ITP');     // 更新模板6的字段值
r($docTester->upgradeTemplateLibAndModuleTest(7))  && p('lib,module,templateType') && e('2,6,ERS');     // 更新模板7的字段值
r($docTester->upgradeTemplateLibAndModuleTest(8))  && p('lib,module,templateType') && e('2,7,URS');     // 更新模板8的字段值
r($docTester->upgradeTemplateLibAndModuleTest(9))  && p('lib,module,templateType') && e('2,8,SRS');     // 更新模板9的字段值
r($docTester->upgradeTemplateLibAndModuleTest(10)) && p('lib,module,templateType') && e('2,9,custom2'); // 更新模板10的字段值
