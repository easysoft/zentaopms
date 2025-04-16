#!/usr/bin/env php
<?php

/**

title=测试 docModel->upgradeTemplate();
timeout=0
cid=1

- 更新模板1的字段值
 - 属性lib @1
 - 属性module @1
 - 属性templateType @softwareProductPlan
- 更新模板1的字段值
 - 属性lib @1
 - 属性module @2
 - 属性templateType @hardwareProductPlan
- 更新模板1的字段值
 - 属性lib @1
 - 属性module @3
 - 属性templateType @qualityAssurancePlan
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
 - 属性templateType @projectUserRequirementsSpecifi
- 更新模板7的字段值
 - 属性lib @2
 - 属性module @6
 - 属性templateType @projectSoftwareRequirementsSpe
- 更新模板8的字段值
 - 属性lib @2
 - 属性module @7
 - 属性templateType @projectSummaryDesignSpecificat
- 更新模板9的字段值
 - 属性lib @2
 - 属性module @8
 - 属性templateType @projectDetailedDesignSpecifica
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
r($docTester->upgradeTemplateTest(1))  && p('lib,module,templateType') && e('1,1,softwareProductPlan');            // 更新模板1的字段值
r($docTester->upgradeTemplateTest(2))  && p('lib,module,templateType') && e('1,2,hardwareProductPlan');            // 更新模板1的字段值
r($docTester->upgradeTemplateTest(3))  && p('lib,module,templateType') && e('1,3,qualityAssurancePlan');           // 更新模板1的字段值
r($docTester->upgradeTemplateTest(4))  && p('lib,module,templateType') && e('1,4,custom1');                        // 更新模板4的字段值
r($docTester->upgradeTemplateTest(5))  && p('lib,module,templateType') && e('1,4,custom1');                        // 更新模板5的字段值
r($docTester->upgradeTemplateTest(6))  && p('lib,module,templateType') && e('2,5,projectUserRequirementsSpecifi'); // 更新模板6的字段值
r($docTester->upgradeTemplateTest(7))  && p('lib,module,templateType') && e('2,6,projectSoftwareRequirementsSpe'); // 更新模板7的字段值
r($docTester->upgradeTemplateTest(8))  && p('lib,module,templateType') && e('2,7,projectSummaryDesignSpecificat'); // 更新模板8的字段值
r($docTester->upgradeTemplateTest(9))  && p('lib,module,templateType') && e('2,8,projectDetailedDesignSpecifica'); // 更新模板9的字段值
r($docTester->upgradeTemplateTest(10)) && p('lib,module,templateType') && e('2,9,custom2');                        // 更新模板10的字段值
