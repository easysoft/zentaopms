#!/usr/bin/env php
<?php

/**

title=测试 screenZen::prepareCardList();
timeout=0
cid=18290

- 测试空数组输入 >> 返回空数组
- 测试已发布大屏无封面 >> 使用默认发布封面图
- 测试草稿状态大屏 >> 使用默认草稿封面图
- 测试内置大屏有封面 >> 使用自定义封面
- 测试普通大屏有封面 >> 使用自定义封面
- 测试多个大屏混合 >> 正确处理各大屏属性

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zendata('screen')->loadYaml('screen_preparecardlist', false, 2)->gen(10);

su('admin');

$screenTest = new screenZenTest();

r($screenTest->prepareCardListTest([])) && p() && e('0');
r($screenTest->prepareCardListTest([1 => (object)['id' => 1, 'name' => '测试大屏1', 'status' => 'published', 'builtin' => '0', 'cover' => '']])) && p('1:src') && e('static/images/screen_published.png');
r($screenTest->prepareCardListTest([2 => (object)['id' => 2, 'name' => '测试大屏2', 'status' => 'draft', 'builtin' => '0', 'cover' => '/cover/test.png']])) && p('2:src') && e('static/images/screen_draft.png');
r($screenTest->prepareCardListTest([3 => (object)['id' => 3, 'name' => '内置大屏', 'status' => 'published', 'builtin' => '1', 'cover' => '/cover/builtin.png']])) && p('3:src') && e('/cover/builtin.png');
r($screenTest->prepareCardListTest([4 => (object)['id' => 4, 'name' => '测试大屏4', 'status' => 'published', 'builtin' => '0', 'cover' => '/cover/custom.png']])) && p('4:src') && e('/cover/custom.png');
r($screenTest->prepareCardListTest([5 => (object)['id' => 5, 'name' => '测试大屏5', 'status' => 'published', 'builtin' => '0', 'cover' => ''], 6 => (object)['id' => 6, 'name' => '测试大屏6', 'status' => 'draft', 'builtin' => '1', 'cover' => '/test.png']])) && p('5:status;6:builtin') && e('published;1');
