#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::assignCaseParamsForCreateCase();
timeout=0
cid=15542

- 执行caselibTest模块的assignCaseParamsForCreateCaseTest方法 
 - 属性type @feature
 - 属性pri @3
 - 属性caseTitle @~~
 - 属性precondition @~~
 - 属性keywords @~~
 - 属性stage @~~
- 执行caselibTest模块的assignCaseParamsForCreateCaseTest方法，参数是1 
 - 属性type @feature
 - 属性pri @1
 - 属性caseTitle @这个是测试用例1
 - 属性precondition @这是前置条件1
 - 属性keywords @这是关键词1
 - 属性stage @unittest
- 执行caselibTest模块的assignCaseParamsForCreateCaseTest方法，参数是2 
 - 属性type @performance
 - 属性pri @2
 - 属性caseTitle @这个是测试用例2
 - 属性precondition @这是前置条件2
 - 属性keywords @这是关键词2
 - 属性stage @feature
- 执行caselibTest模块的assignCaseParamsForCreateCaseTest方法，参数是3 
 - 属性type @config
 - 属性pri @3
 - 属性caseTitle @这个是测试用例3
 - 属性precondition @这是前置条件3
 - 属性keywords @这是关键词3
 - 属性stage @intergrate
- 执行caselibTest模块的assignCaseParamsForCreateCaseTest方法，参数是4 
 - 属性type @install
 - 属性pri @4
 - 属性caseTitle @这个是测试用例4
 - 属性precondition @这是前置条件4
 - 属性keywords @这是关键词4
 - 属性stage @system

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

zenData('case')->loadYaml('case_assigncaseparamsforcreatecase', false, 2)->gen(10);
zenData('casestep')->loadYaml('casestep_assigncaseparamsforcreatecase', false, 2)->gen(20);

su('admin');

$caselibTest = new caselibTest();

r($caselibTest->assignCaseParamsForCreateCaseTest(0)) && p('type,pri,caseTitle,precondition,keywords,stage') && e('feature,3,~~,~~,~~,~~');
r($caselibTest->assignCaseParamsForCreateCaseTest(1)) && p('type,pri,caseTitle,precondition,keywords,stage') && e('feature,1,这个是测试用例1,这是前置条件1,这是关键词1,unittest');
r($caselibTest->assignCaseParamsForCreateCaseTest(2)) && p('type,pri,caseTitle,precondition,keywords,stage') && e('performance,2,这个是测试用例2,这是前置条件2,这是关键词2,feature');
r($caselibTest->assignCaseParamsForCreateCaseTest(3)) && p('type,pri,caseTitle,precondition,keywords,stage') && e('config,3,这个是测试用例3,这是前置条件3,这是关键词3,intergrate');
r($caselibTest->assignCaseParamsForCreateCaseTest(4)) && p('type,pri,caseTitle,precondition,keywords,stage') && e('install,4,这个是测试用例4,这是前置条件4,这是关键词4,system');