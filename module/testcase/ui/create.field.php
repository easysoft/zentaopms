<?php
namespace zin;
global $lang, $config;

$fields = defineFieldList('testcase.create');

$fields->field('product')
    ->hidden(data('product.shadow'))
    ->control('inputGroup')
    ->items(false)
    ->itemBegin('product')->control('picker')->items(data('products'))->required(true)->value(empty(data('case.product')) ? data('productID') : data('case.product'))->itemEnd()
    ->item(data('product.type') == 'normal' ? null : field('branch')->control('picker')->width('100px')->items(data('branches'))->value(data('branch')));

$fields->field('module')
    ->controlBegin('module')
    ->type('modulePicker')
    ->items(data('moduleOptionMenu'))
    ->value(data('currentModuleID'))
    ->manageLink(createLink('tree', 'browse', 'rootID=' . data('productID') . '&view=case&currentModuleID=0&branch=' . data('branch')))
    ->required(true)
    ->controlEnd()
    ->wrapAfter(true);

$fields->field('story')
    ->label($lang->testcase->lblStory)
    ->control('inputGroup')
    ->items(false)
    ->itemBegin('story')->control('picker')->id('story')->items(data('stories'))->value(data('case.story'))->itemEnd()
    ->itemBegin()->control('btn', array('url' => helper::createLink('story', 'view', 'storyID=' . data('case.story')), 'data-toggle' => 'modal', 'data-size' => 'lg'))->className('hidden', empty(data('case.story')))->icon('eye text-primary')->hint($lang->preview)->id('preview')->itemEnd();

$fields->field('scene')
    ->items(data('sceneOptionMenu'))
    ->value(data('currentSceneID'))
    ->required(true);

$fields->field('type')
    ->checkbox(array('id' => 'auto', 'name' => 'auto', 'text' => $lang->testcase->automated, 'checked' => data('case.auto') == 'auto'))
    ->items($lang->testcase->typeList)
    ->value(data('case.type'));

$fields->field('stage')
    ->items($lang->testcase->stageList)
    ->value(data('case.stage'))
    ->multiple();

$fields->field('scriptFile')
    ->width('full')
    ->label($lang->testcase->autoScript)
    ->className('autoScript')
    ->hidden(data('case.auto') != 'auto')
    ->control('upload', array('accept' => $config->testcase->scriptAcceptFileTypes, 'limitCount' => 1));

$fields->field('script')
    ->control('hidden')
    ->value(data('case.script'));

$fields->field('title')
    ->width('5/6')
    ->control('colorInput', array('colorValue' => data('case.color')))
    ->checkbox(data('needReview') ? array('name' => 'needReview', 'text' => $lang->testcase->forceReview, 'checked' => true) : null);

$fields->field('pri')
    ->width('1/6')
    ->control('priPicker')
    ->items(array_filter($lang->testcase->priList))
    ->value(data('case.pri'));

$fields->field('precondition')
    ->control('textarea', array('rows' => 2))
    ->width('full')
    ->value(data('case.precondition'));

$fields->field('steps')
    ->width('full')
    ->control('stepsEditor', array('data' => data('case.steps')));

$fields->field('keywords')
    ->width('full')
    ->value(data('case.keywords'));

$fields->field('files')
    ->width('full')
    ->control('upload');
