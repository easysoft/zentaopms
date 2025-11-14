#!/usr/bin/env php
<?php

/**

title=测试 docModel->setDocPrivError();
cid=16151

- 判断文档1是否有正常产品1的权限属性doc_1_nopriv @您暂无 正常产品1 的访问权限，无法查看该文档，如需调整权限可联系相关人员处理。
- 判断文档2是否有敏捷项目1的权限属性doc_2_nopriv @您暂无 敏捷项目1 的访问权限，无法查看该文档，如需调整权限可联系相关人员处理。
- 判断文档3是否有瀑布项目2的权限属性doc_3_nopriv @您暂无 瀑布项目2 的访问权限，无法查看该文档，如需调整权限可联系相关人员处理。
- 判断文档4是否有看板项目4的权限属性doc_4_nopriv @您暂无 看板项目4 的访问权限，无法查看该文档，如需调整权限可联系相关人员处理。
- 判断文档5是否有迭代5的权限属性doc_5_nopriv @您暂无 迭代5 的访问权限，无法查看该文档，如需调整权限可联系相关人员处理。
- 判断文档6是否有阶段10的权限属性doc_6_nopriv @您暂无 阶段10 的访问权限，无法查看该文档，如需调整权限可联系相关人员处理。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

zenData('product')->gen(10);
zenData('project')->loadYaml('execution')->gen(10);
zenData('user')->gen(5);

$docTester = new docTest();
r($docTester->setDocPrivErrorTest(1, 1,   'product')) && p('doc_1_nopriv') && e('您暂无 正常产品1 的访问权限，无法查看该文档，如需调整权限可联系相关人员处理。'); // 判断文档1是否有正常产品1的权限
r($docTester->setDocPrivErrorTest(2, 11,  'project')) && p('doc_2_nopriv') && e('您暂无 敏捷项目1 的访问权限，无法查看该文档，如需调整权限可联系相关人员处理。'); // 判断文档2是否有敏捷项目1的权限
r($docTester->setDocPrivErrorTest(3, 60,  'project')) && p('doc_3_nopriv') && e('您暂无 瀑布项目2 的访问权限，无法查看该文档，如需调整权限可联系相关人员处理。'); // 判断文档3是否有瀑布项目2的权限
r($docTester->setDocPrivErrorTest(4, 100, 'project')) && p('doc_4_nopriv') && e('您暂无 看板项目4 的访问权限，无法查看该文档，如需调整权限可联系相关人员处理。'); // 判断文档4是否有看板项目4的权限
r($docTester->setDocPrivErrorTest(5, 101, 'project')) && p('doc_5_nopriv') && e('您暂无 迭代5 的访问权限，无法查看该文档，如需调整权限可联系相关人员处理。');     // 判断文档5是否有迭代5的权限
r($docTester->setDocPrivErrorTest(6, 106, 'project')) && p('doc_6_nopriv') && e('您暂无 阶段10 的访问权限，无法查看该文档，如需调整权限可联系相关人员处理。');    // 判断文档6是否有阶段10的权限
