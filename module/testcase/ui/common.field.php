<?php
namespace zin;
global $lang, $config;

$fields = defineFieldList('testcase');

$fields->field('product')
    ->items(data('products'))
    ->value(!empty(data('case.product')) ? data('case.product') : data('productID'))
    ->required();

$fields->field('branch')
    ->items(data('branches'))
    ->value(data('branch'));

$fields->field('module')
    ->controlBegin('module')
    ->type('modulePicker')
    ->items(data('moduleOptionMenu'))
    ->value(data('currentModuleID'))
    ->manageLink(createLink('tree', 'browse', 'rootID=' . data('productID') . '&view=case&currentModuleID=0&branch=' . data('branch')))
    ->controlEnd();

$fields->field('type')
    ->checkbox(array('id' => 'auto', 'name' => 'auto', 'text' => $lang->testcase->automated, 'checked' => data('case.auto') == 'auto'))
    ->items($lang->testcase->typeList)
    ->value(data('case.type'))
    ->required();

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

$fields->field('story')
    ->label($lang->testcase->lblStory)
    ->items(data('stories'))
    ->value(data('case.story'));

$fields->field('scene')
    ->items(data('sceneOptionMenu'))
    ->value(data('currentSceneID'))
    ->required();

$fields->field('title')
    ->width('full');

$fields->field('pri')
    ->width('1/6')
    ->control('priPicker')
    ->items(array_filter($lang->testcase->priList))
    ->value(data('case.pri'));

$fields->field('color')
    ->control('color');

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
