#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->batchChangeModule();
cid=1
pid=1

测试批量修改case 161 162 的module为161 >> 161;161
测试批量修改case 163 164 的module为163 >> 163;163
测试批量修改case 165 166 的module为165 >> 165;165
测试批量修改case 167 168 的module为167 >> 167;167
测试批量修改case 169 170 的module为169 >> 169;169
测试批量修改case 171 172 的module为171 >> 171;171
测试批量修改case 173 174 的module为173 >> 173;173

*/
$caseIDList = array(array(161, 162), array(163, 164), array(165, 166), array(167, 168), array(169, 170), array(171, 172), array(173, 174));
$moduleList = array(161, 163, 165, 167, 169, 171, 173);

$testcase = new testcaseTest();

r($testcase->batchChangeModuleTest($caseIDList[0], $moduleList[0])) && p('161:module;162:module') && e('161;161'); // 测试批量修改case 161 162 的module为161
r($testcase->batchChangeModuleTest($caseIDList[1], $moduleList[1])) && p('163:module;164:module') && e('163;163'); // 测试批量修改case 163 164 的module为163
r($testcase->batchChangeModuleTest($caseIDList[2], $moduleList[2])) && p('165:module;166:module') && e('165;165'); // 测试批量修改case 165 166 的module为165
r($testcase->batchChangeModuleTest($caseIDList[3], $moduleList[3])) && p('167:module;168:module') && e('167;167'); // 测试批量修改case 167 168 的module为167
r($testcase->batchChangeModuleTest($caseIDList[4], $moduleList[4])) && p('169:module;170:module') && e('169;169'); // 测试批量修改case 169 170 的module为169
r($testcase->batchChangeModuleTest($caseIDList[5], $moduleList[5])) && p('171:module;172:module') && e('171;171'); // 测试批量修改case 171 172 的module为171
r($testcase->batchChangeModuleTest($caseIDList[6], $moduleList[6])) && p('173:module;174:module') && e('173;173'); // 测试批量修改case 173 174 的module为173
