<?php
namespace zin;
global $lang, $config;

$fields       = defineFieldList('story.create');
$createFields = data('fields');
$type         = data('type');
$isSR         = $type == 'story';
$isBranchUR   = isset($createFields['branch']) && $type != 'story';
$isKanban     = data('executionType') == 'kanban';

$fields->field('product')
    ->hidden(data('product.shadow'))
    ->required()
    ->control('inputGroup')
    ->items(false)
    ->itemBegin('product')->control('picker')->items($createFields['product']['options'])->value($createFields['product']['default'])->itemEnd()
    ->item($isBranchUR ? field('branch')->control('picker')->boxClass('flex-none')->width('100px')->name('branch')->items($createFields['branch']['options'])->value($createFields['branch']['default']) : null);

$fields->field('module')
    ->wrapAfter(true)
    ->required($createFields['module']['required'])
    ->controlBegin('module')
    ->type('modulePicker')
    ->items($createFields['module']['options'])
    ->value($createFields['module']['default'])
    ->manageLink(createLink('tree', 'browse', 'rootID=' . data('productID') . '&view=strory&currentModuleID=0&branch=' . data('branch')))
    ->controlEnd();

if(isset($createFields['branch']) && $type == 'story')
{
    $fields->remove('module');
    $fields->field('twinsStory')
        ->width('full')
        ->control(array
        (
            'type'          => 'twinsstory',
            'productType'   => data('product.type'),
            'branchItems'   => $createFields['branch']['options'],
            'defaultBranch' => $createFields['branch']['default'],
            'moduleItems'   => $createFields['module']['options'],
            'defaultModule' => $createFields['module']['default'],
            'planItems'     => $createFields['plan']['options'],
            'defaultPlan'   => $createFields['plan']['default'],
        ));
}

if(isset($createFields['URS']))
{
    $fields->field('URS')
        ->label($lang->story->requirement)
        ->control('inputGroup')
        ->items(false)
        ->itemBegin('URS[]')->control('picker')->id('URS')->items($createFields['URS']['options'])->value($createFields['URS']['default'])->multiple()->itemEnd()
        ->itemBegin()->control('btn')->icon('refresh text-primary')->hint($lang->story->loadAllStories)->id('loadURS')->itemEnd();
    $fields->field('parent')
        ->hidden(data('hiddenParent'))
        ->items($createFields['parent']['options'])
        ->value($createFields['parent']['default']);
}

$fields->field('reviewer')
    ->width('full')
    ->control('inputGroup')
    ->id('reviewerBox')
    ->items(false)
    ->itemBegin('reviewer[]')->control('picker')->id('reviewer')->items($createFields['reviewer']['options'])->value($createFields['reviewer']['default'])->multiple()->itemEnd();
$fields->field('needNotReview')->control('hidden')->value(0);

if($isSR)
{
    $fields->field('assignedTo')
        ->required($createFields['assignedTo']['required'])
        ->id('assignedToBox')
        ->items($createFields['assignedTo']['options']);
}

$fields->field('category')
    ->className('full:w-1/6')
    ->required($createFields['category']['required'])
    ->items($createFields['category']['options'])
    ->value($createFields['category']['default']);

if($isKanban)
{
    $fields->field('region')
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
    ->className('full:w-1/6')
    ->required($createFields['pri']['required'])
    ->control('priPicker')
    ->items($createFields['pri']['options'])
    ->value($createFields['pri']['default']);

$fields->field('estimate')
    ->width('1/4')
    ->className('full:w-1/6')
    ->required($createFields['estimate']['required'])
    ->label($lang->story->estimateAB . $lang->story->estimateUnit)
    ->value($createFields['estimate']['default']);

$fields->field('spec')
    ->width('full')
    ->required($createFields['spec']['required'])
    ->control('editor')
    ->placeholder($lang->story->specTemplate . "\n" . $lang->noticePasteImg)
    ->value($createFields['spec']['default']);

$fields->field('verify')
    ->width('full')
    ->required($createFields['verify']['required'])
    ->control('editor')
    ->value($createFields['verify']['default']);

$fields->field('files')->width('full')->control('files');

if(!isset($createFields['branch']) && $type == 'story')
{
    $fields->field('plan')
        ->foldable()
        ->required($createFields['plan']['required'])
        ->control('inputGroup')
        ->items(false)
        ->itemBegin('plan')->control('picker')->id('planIdBox')->items($createFields['plan']['options'])->value($createFields['plan']['default'])->itemEnd()
        ->item(empty($createFields['plan']['options']) ? field()->control('btn')->icon('plus')->url(createLink('productplan', 'create', 'productID=' . data('productID') . '&branch=' . data('branch')))->set('data-toggle', 'modal')->set('title', $lang->productplan->create) : null)
        ->item(empty($createFields['plan']['options']) ? field()->control('btn')->icon('refresh')->id("loadProductPlans")->set('title', $lang->refresh) : null);
}

$fields->field('source')
    ->foldable()
    ->label($lang->story->source . '&' . $lang->story->sourceNote)
    ->control('inputGroup')
    ->items(false)
    ->itemBegin('source')->control('picker')->items($createFields['source']['options'])->value($createFields['source']['default'])->itemEnd()
    ->itemBegin('sourceNote')->value($createFields['sourceNote']['default'])->itemEnd();

$fields->field('feedbackBy')
    ->foldable()
    ->className('feedbackBox')
    ->value($createFields['feedbackBy']['default']);

$fields->field('notifyEmail')
    ->foldable()
    ->className('feedbackBox')
    ->value($createFields['notifyEmail']['default']);

$fields->field('mailto')
    ->foldable()
    ->control('mailto');

$fields->field('keywords')->foldable();

$fields->field('type')->control('hidden')->value($type);
$fields->field('status')->control('hidden')->value('active');
