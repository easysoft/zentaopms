<?php
namespace zin;
global $lang, $config, $app;

$fields    = defineFieldList('testcase.create');
$productID = data('productID');
$moduleID  = data('currentModuleID');
$branch    = data('branch');
$storyID   = data('case.story') ? data('case.story') : '';

unset($lang->testcase->typeList['unit']);

$fields->field('product')
    ->hidden(data('product.shadow'))
    ->control('inputGroup')
    ->items(false)
    ->itemBegin('product')->control('picker')->items(data('products'))->required(true)->value(data('productID'))->itemEnd()
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
    ->itemBegin('story')->control(array('control' => 'remotepicker', 'params' => "productID=$productID&moduleID=$moduleID&branch=$branch&storyID=$storyID", 'type' => 'casestories'))->id('story')->value($storyID)->itemEnd()
    ->itemBegin()->control('btn', array('url' => helper::createLink('story', 'view', 'storyID=' . $storyID), 'data-toggle' => 'modal', 'data-size' => 'lg'))->className('hidden', empty($storyID))->icon('eye text-primary')->hint($lang->preview)->id('preview')->itemEnd();

$fields->field('scene')
    ->control(array('control' => 'picker', 'required' => true))
    ->items(data('sceneOptionMenu'))
    ->value(data('currentSceneID'));

$fields->field('type')
    ->checkbox(array('id' => 'auto', 'name' => 'auto', 'text' => $lang->testcase->automated, 'checked' => data('case.auto') == 'auto' || data('onlyAutoCase')))
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
    ->hidden(data('case.auto') != 'auto' && !data('onlyAutoCase'))
    ->control('fileSelector', array('accept' => $config->testcase->scriptAcceptFileTypes, 'multiple' => false, 'maxFileCount' => 1, 'onAdd' => jsRaw('window.readScriptContent'), 'onRemove' => jsRaw('window.showUploadScriptBtn')));

$fields->field('script')
    ->control('hidden')
    ->value(data('case.script'));

$fields->field('title')
    ->width('5/6')
    ->value(data('case.title'))
    ->control('colorInput', array('colorValue' => data('case.color')))
    ->checkbox(data('needReview') ? array('name' => 'needReview', 'text' => $lang->testcase->forceReview, 'checked' => true) : null);

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

$files = data('case.files');
$fields->field('files')
    ->width('full')
    ->control('fileSelector', array('defaultFiles' => ($files ? array_values($files) : array())));

$fields->field('fileList')->control('hidden')->value(data('case.files'));
