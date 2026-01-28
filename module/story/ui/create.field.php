<?php
namespace zin;
global $lang, $config;

$uid          = uniqid();
$fields       = defineFieldList('story.create');
$createFields = data('fields');
$type         = data('type');
$gradeRule    = data('gradeRule');
$isSR         = $type == 'story';
$isBranchUR   = isset($createFields['branch']) && $type != 'story';
$isKanban     = data('executionType') == 'kanban';

$fields->field('product')
    ->hidden(data('product.shadow'))
    ->required()
    ->control('inputGroup')
    ->items(false)
    ->itemBegin('product')->control('picker')->items($createFields['product']['options'])->value($createFields['product']['default'])->required(true)->itemEnd()
    ->item($isBranchUR ? field('branch')->control('picker')->boxClass('flex-none')->width('100px')->name('branch')->items($createFields['branch']['options'])->value($createFields['branch']['default']) : null);

$fields->field('module')
    ->wrapAfter(true)
    ->required($createFields['module']['required'])
    ->control(array('control' => 'modulePicker', 'required' => true, 'manageLink' => createLink('tree', 'browse', 'rootID=' . data('productID') . '&view=story&currentModuleID=0&branch=' . data('branch'))))
    ->items($createFields['module']['options'])
    ->value($createFields['module']['default']);

if(isset($createFields['branch']) && $type == 'story') $fields->remove('module');
$fields->field('twinsStory')
    ->width('full')
    ->hidden(!isset($createFields['branch']) || $type != 'story')
    ->control(array
    (
        'type'          => 'twinsstory',
        'productType'   => data('product.type'),
        'branchItems'   => $createFields['branch']['options'] ?? array(),
        'defaultBranch' => $createFields['branch']['default'] ?? 0,
        'moduleItems'   => $createFields['module']['options'],
        'defaultModule' => $createFields['module']['default'],
        'planItems'     => isset($createFields['plan']['options']) ? $createFields['plan']['options'] : array(),
        'defaultPlan'   => isset($createFields['plan']['default']) ? $createFields['plan']['default'] : 0,
    ));

$fields->field('parent')
    ->id('parentBox')
    ->items($createFields['parent']['options'])
    ->value($createFields['parent']['default']);

$fields->field('grade')
    ->disabled($gradeRule == 'stepwise')
    ->required()
    ->items($createFields['grade']['options'])
    ->value($createFields['grade']['default']);

$fields->field('reviewer')
    ->hidden(!data('forceReview') && !empty(data('needNotReview')))
    ->width('full')
    ->required()
    ->control('inputGroup')
    ->id('reviewerBox')
    ->items(false)
    ->itemBegin('reviewer[]')->control('picker')->id('reviewer')->items($createFields['reviewer']['options'])->value($createFields['reviewer']['default'])->multiple()->itemEnd();
$fields->field('needNotReview')->control('hidden')->value(data('forceReview') ? 0 : 1);

$fields->field('assignedTo')
    ->required($createFields['assignedTo']['required'])
    ->id('assignedToBox')
    ->items($createFields['assignedTo']['options']);

$fields->field('category')
    ->required($createFields['category']['required'])
    ->items($lang->{$type}->categoryList)
    ->value($createFields['category']['default']);

if($isKanban)
{
    $fields->field('region')
        ->wrapBefore(true)
        ->label($createFields['region']['title'])
        ->items($createFields['region']['options'])
        ->value($createFields['region']['default']);

    $fields->field('lane')
        ->required($createFields['lane']['required'])
        ->label($createFields['lane']['title'])
        ->items($createFields['lane']['options'])
        ->value($createFields['lane']['default']);
}

$fields->field('title')
    ->wrapBefore(true)
    ->control('colorInput')
    ->required($createFields['title']['required'])
    ->value($createFields['title']['default']);

$fields->field('pri')
    ->width('1/4')
    ->required($createFields['pri']['required'])
    ->control('priPicker')
    ->items($lang->{$type}->priList)
    ->value($createFields['pri']['default']);

$fields->field('estimate')
    ->width('1/4')
    ->required($createFields['estimate']['required'])
    ->label($lang->story->estimateAB . $lang->story->estimateUnit)
    ->value($createFields['estimate']['default']);

$fields->field('spec')
    ->width('full')
    ->required($createFields['spec']['required'])
    ->control(array('control' => 'editor', 'templateType' => 'story', 'uid' => $uid))
    ->placeholder($lang->story->specTemplate . "\n" . $lang->noticePasteImg)
    ->value($createFields['spec']['default']);

$fields->field('verify')
    ->width('full')
    ->required($createFields['verify']['required'])
    ->control(array('control' => 'editor', 'uid' => $uid))
    ->value($createFields['verify']['default']);

$files = data('initStory.files') ? data('initStory.files') : array();
$fields->field('files')->width('full')->control('fileSelector', array('defaultFiles' => array_values($files)));
$fields->field('fileList')->control('hidden')->value($files);

if(!(isset($createFields['branch']) && $type == 'story') && isset($createFields['plan']))
{
    $fields->field('plan')
        ->foldable()
        ->required($createFields['plan']['required'])
        ->control('inputGroup')
        ->items(false)
        ->itemBegin('plan')->control('picker')->id('planIdBox')->items($createFields['plan']['options'])->value($createFields['plan']['default'])->multiple($type != 'story')->itemEnd()
        ->item(empty($createFields['plan']['options']) && hasPriv('productplan', 'create') ? field()->control('btn')->icon('plus')->url(createLink('productplan', 'create', 'productID=' . data('productID') . '&branch=' . data('branch')))->set(array('data-toggle' => 'modal', 'data-size' => 'lg'))->set('title', $lang->productplan->create) : null)
        ->item(empty($createFields['plan']['options']) ? field()->control(array('control' => 'btn', 'data-on' => 'click', 'data-call' => 'loadProductPlans', 'data-params' => data('productID')))->icon('refresh')->id("loadProductPlans")->set('title', $lang->refresh) : null);
}

$fields->field('source')
    ->foldable()
    ->width('1/4')
    ->required($createFields['source']['required'])
    ->items($lang->{$type}->sourceList)
    ->value($createFields['source']['default']);

$fields->field('sourceNote')
    ->foldable()
    ->width('1/4')
    ->value($createFields['sourceNote']['default']);

$fields->field('feedbackBy')
    ->foldable()
    ->className('feedbackBox')
    ->className(!in_array($createFields['source']['default'], $config->story->feedbackSource) ? 'hidden' : '')
    ->id('feedbackBy')
    ->value($createFields['feedbackBy']['default']);

$fields->field('notifyEmail')
    ->foldable()
    ->className('feedbackBox')
    ->className(!in_array($createFields['source']['default'], $config->story->feedbackSource) ? 'hidden' : '')
    ->id('notifyEmail')
    ->value($createFields['notifyEmail']['default']);

$fields->field('mailto')
    ->foldable()
    ->control('mailto');

$fields->field('keywords')->foldable()->required($createFields['keywords']['required']);

$fields->field('type')->control('hidden')->value($type);
$fields->field('status')->control('hidden')->value('active');
