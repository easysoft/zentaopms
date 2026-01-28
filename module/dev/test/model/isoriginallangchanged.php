#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 devModel::isOriginalLangChanged();
cid=16015
pid=1


检查没有自定义语言的情况 >> FALSE
检查原始语言和自定义语言都没有公共语言项的情况 >> FALSE
检查原始语言没有公共语言，自定义语言有公共语言的情况 >> TRUE
检查原始语言有公共语言，自定义语言没有公共语言的情况 >> TRUE
检查原始语言有公共语言，自定义语言有公共语言，公共语言是一样的情况 >> FALSE
检查原始语言有一个公共语言，自定义语言有多个公共语言的情况 >> TRUE
检查原始语言有一个公共语言，自定义语言有一个公共语言，但两个公共语言不一样情况 >> TRUE
检查原始语言有一个公共语言，自定义语言有一个公共语言，但两个公共语言位置不一样情况 >> TRUE
检查原始语言有一个公共语言，自定义语言有一个公共语言，但两个公共语言位置不一样情况 >> TRUE

*/

$devTester = new devModelTest();

$defaultValue = '产品列表';
$customedLang = '';

r($devTester->isOriginalLangChangedTest($defaultValue, $customedLang)) && p()  && e("FALSE");    // 检查没有自定义语言的情况

$customedLang = '产品列表';
r($devTester->isOriginalLangChangedTest($defaultValue, $customedLang)) && p()  && e("FALSE");    // 检查原始语言和自定义语言都没有公共语言项的情况
$customedLang = array('$PRODUCTCOMMON', '列表');
r($devTester->isOriginalLangChangedTest($defaultValue, $customedLang)) && p()  && e("TRUE");     // 检查原始语言没有公共语言，自定义语言有公共语言的情况

$defaultValue = array('$PRODUCTCOMMON', '列表');
$customedLang = '产品列表';
r($devTester->isOriginalLangChangedTest($defaultValue, $customedLang)) && p()  && e("TRUE");     // 检查原始语言有公共语言，自定义语言没有公共语言的情况

$customedLang = array('$PRODUCTCOMMON', '列表');
r($devTester->isOriginalLangChangedTest($defaultValue, $customedLang)) && p()  && e("FALSE");    // 检查原始语言有公共语言，自定义语言有公共语言的情况

$customedLang = array('$PRODUCTCOMMON', '列表', '$PROJECTCOMMON');
r($devTester->isOriginalLangChangedTest($defaultValue, $customedLang)) && p()  && e("TRUE");     // 检查原始语言有一个公共语言，自定义语言有多个公共语言的情况

$customedLang = array('$PROJECTCOMMON', '列表');
r($devTester->isOriginalLangChangedTest($defaultValue, $customedLang)) && p()  && e("TRUE");     // 检查原始语言有一个公共语言，自定义语言有一个公共语言，但两个公共语言不一样情况

$customedLang = array('列表', '$PROJECTCOMMON');
r($devTester->isOriginalLangChangedTest($defaultValue, $customedLang)) && p()  && e("TRUE");     // 检查原始语言有一个公共语言，自定义语言有一个公共语言，但两个公共语言位置不一样情况

$defaultValue = array('列表', '$PRODUCTCOMMON');
$customedLang = array('$PRODUCTCOMMON', '列表');
r($devTester->isOriginalLangChangedTest($defaultValue, $customedLang)) && p()  && e("TRUE");     // 检查原始语言有一个公共语言，自定义语言有一个公共语言，但两个公共语言位置不一样情况
