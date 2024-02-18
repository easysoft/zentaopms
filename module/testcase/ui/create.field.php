<?php
namespace zin;
global $lang, $config, $app;

$fields = defineFieldList('testcase.create');

$fields->field('product')
    ->hidden($app->tab != 'qa' && data('product.shadow'))
    ->control('inputGroup')
    ->items(false)
    ->itemBegin('product')->control('picker')->items(data('products'))->required(true)->value(empty(data('case.product')) ? data('productID') : data('case.product'))->itemEnd()
    ->item(data('product.type') == 'normal' ? null : field('branch')->control('picker', array('required' => true))->width('100px')->items(data('branches'))->value((int)data('branch')));

$fields->field('module')
    ->controlBegin('modulePicker')
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
    ->control(array('type' => 'picker', 'required' => true))
    ->items(data('sceneOptionMenu'))
    ->value(data('currentSceneID'));

$fields->field('type')
    ->checkbox(array('id' => 'auto', 'name' => 'auto', 'text' => $lang->testcase->automated, 'checked' => data('case.auto') == 'auto'))
    ->items($lang->testcase->typeList)
    ->value(data('case.type'));

$fields->field('stage')
    ->items($lang->testcase->stageList)
    ->value(data('case.stage'))
    ->multiple();

$fields->field('scriptFile')
    ->multiple(false)
    ->width('full')
    ->label($lang->testcase->autoScript)
    ->className('autoScript')
    ->hidden(data('case.auto') != 'auto')
    ->control('fileSelector', array('accept' => $config->testcase->scriptAcceptFileTypes, 'multiple' => false, 'onAdd' => jsRaw('window.readScriptContent'), 'onRemove' => jsRaw('window.showUploadScriptBtn')));

$fields->field('script')
    ->control('hidden')
    ->value(data('case.script'));

$fields->field('title')
    ->width('5/6')
    ->control('colorInput', array('colorValue' => data('case.color')))
    ->checkbox(data('needReview') ? array('name' => 'needReview', 'text' => $lang->testcase->forceReview) : null);

$fields->field('pri')
    ->width('1/6')
    ->control(array('control' => 'priPicker', 'required' => true))
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
    ->control('fileSelector');
