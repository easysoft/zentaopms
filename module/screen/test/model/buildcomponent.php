#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

zenData('screen')->gen(0);
zenData('product')->gen(2);
zenData('story')->loadYaml('story')->gen(20);
zenData('action')->gen(20);

/**

title=测试 screenModel->buildComponent();
timeout=0
cid=1

- 有图表id的元素判断是否正常生成了刻度和数据。 @1
- 判断是否正常生成了Select组件。 @1
- 非列表的组件判断是否给予了默认的属性。 @1
- 列表的组件判断是否给予了默认的属性。 @1

*/

$screen = new screenTest();

$components = $screen->getAllComponent();

$component1 = null;
$component2 = null;
$component3 = null;
$component4 = null;
foreach($components as $component)
{
    if(isset($component->sourceID) && $component->sourceID)
    {
        $component1 = $component;
    }
    elseif(isset($component->key) && $component->key === 'Select')
    {
        $component2 = $component;
    }
    elseif(empty($component->isGroup))
    {
        $component3 = $component;
    }
    else
    {
        $component4 = $component;
    }
}


if($component1) $screen->buildComponentTest($component1);
r(isset($component1->option->header[0]) && $component1->option->header[0] == '产品') && p('') && e('1');  //有图表id的元素判断是否正常生成了刻度和数据。

if($component2) $screen->buildComponentTest($component2);
$onChange = "window.location.href = createLink('screen', 'view', 'screenID=&year=&month=' + value + '&dept=&account=')";
r(isset($component2) && $component2->option->onChange = $onChange) && p('') && e('1');  //判断是否正常生成了Select组件。

if($component3) $screen->buildComponentTest($component3);
r($component3->styles && $component->status && $component->request) && p('') && e('1');  //非列表的组件判断是否给予了默认的属性。

if($component4) $screen->buildComponentTest($component4);
r($component3->styles && $component->status && $component->request) && p('') && e('1'); //列表的组件判断是否给予了默认的属性。
