<?php
namespace zin;
global $app, $lang, $config;

$app->loadLang('productplan');

$uid          = uniqid();
$fields       = defineFieldList('story.create');
$createFields = data('fields');
$type         = data('type') ?: 'story';
$gradeRule    = data('gradeRule');
$isSR         = $type == 'story';
$isBranchUR   = isset($createFields['branch']) && $type != 'story';
$isKanban     = data('executionType') == 'kanban';

$fields->field('product')
    ->hidden(data('product.shadow'))
    ->required()
    ->control('inputGroup')
    ->items(false)
    ->itemBegin('product')->control('picker')
    ->items(isset($createFields['product']['options']) ? $createFields['product']['options'] : array())
    ->value(isset($createFields['product']['default']) ? $createFields['product']['default'] : 0)->required(true)->itemEnd()
    ->item($isBranchUR ? field('branch')->control('picker')->boxClass('flex-none')->width('100px')->name('branch')->items($createFields['branch']['options'])->value($createFields['branch']['default']) : null);

$fields->field('module')
    ->wrapAfter(true)
    ->required(!empty($createFields['module']['required']))
    ->control(array('control' => 'modulePicker', 'required' => true, 'manageLink' => createLink('tree', 'browse', 'rootID=' . data('productID') . '&view=story&currentModuleID=0&branch=' . data('branch'))))
    ->items(isset($createFields['module']['options']) ? $createFields['module']['options'] : array())
    ->value(isset($createFields['module']['default']) ? $createFields['module']['default'] : 0);

if(isset($createFields['branch']) && $type == 'story') $fields->remove('module');
$fields->field('twinsStory')
    ->width('full')
    ->hidden(!isset($createFields['branch']) || $type != 'story')
    ->control(array
    (
        'type'          => 'twinsstory',
        'productType'   => data('product.type'),
        'branchItems'   => isset($createFields['branch']['options']) ? $createFields['branch']['options'] : array(),
        'defaultBranch' => isset($createFields['branch']['default']) ? $createFields['branch']['default'] : 0,
        'moduleItems'   => isset($createFields['module']['options']) ? $createFields['module']['options'] : array(),
        'defaultModule' => isset($createFields['module']['default']) ? $createFields['module']['default'] : 0,
        'planItems'     => isset($createFields['plan']['options']) ? $createFields['plan']['options'] : array(),
        'defaultPlan'   => isset($createFields['plan']['default']) ? $createFields['plan']['default'] : 0,
    ));

$fields->field('parent')
    ->id('parentBox')
    ->items(isset($createFields['parent']['options']) ? $createFields['parent']['options'] : array())
    ->value(isset($createFields['parent']['default']) ? $createFields['parent']['default'] : 0)
    ->checkbox(array('text' => $lang->story->syncToChild, 'id' => 'syncToChild'));

$fields->field('grade')
    ->disabled($gradeRule == 'stepwise')
    ->required()
    ->items(isset($createFields['grade']['options']) ? $createFields['grade']['options'] : array())
    ->value(isset($createFields['grade']['default']) ? $createFields['grade']['default'] : 0);

$fields->field('reviewer')
    ->hidden(!data('forceReview') && !empty(data('needNotReview')))
    ->width('full')
    ->required()
    ->control('inputGroup')
    ->id('reviewerBox')
    ->items(false)
    ->itemBegin('reviewer[]')->control('picker')->id('reviewer')->items(isset($createFields['reviewer']['options']) ? $createFields['reviewer']['options'] : array())
    ->value(isset($createFields['reviewer']['default']) ? $createFields['reviewer']['default'] : '')->multiple()
    ->itemEnd();
$fields->field('needNotReview')->control('hidden')->value(data('forceReview') ? 0 : 1);

$fields->field('assignedTo')
    ->required(!empty($createFields['assignedTo']['required']))
    ->id('assignedToBox')
    ->items(isset($createFields['assignedTo']['options']) ? $createFields['assignedTo']['options'] : array());

$fields->field('category')
    ->required(!empty($createFields['category']['required']))
    ->items($lang->{$type}->categoryList)
    ->value(isset($createFields['category']['default']) ? $createFields['category']['default'] : '');

if($isKanban)
{
    $fields->field('region')
        ->wrapBefore(true)
        ->label(isset($createFields['region']['title']) ? $createFields['region']['title'] : '')
        ->items(isset($createFields['region']['options']) ? $createFields['region']['options'] : array())
        ->value(isset($createFields['region']['default']) ? $createFields['region']['default'] : '');

    $fields->field('lane')
        ->required(!empty($createFields['lane']['required']))
        ->label(isset($createFields['lane']['title']) ? $createFields['lane']['title'] : '')
        ->items(isset($createFields['lane']['options']) ? $createFields['lane']['options'] : array())
        ->value(isset($createFields['lane']['default']) ? $createFields['lane']['default'] : '');
}

$fields->field('title')
    ->wrapBefore(true)
    ->control('colorInput')
    ->required(!empty($createFields['title']['required']))
    ->value(isset($createFields['title']['default']) ? $createFields['title']['default'] : '');

$fields->field('pri')
    ->width('1/4')
    ->required(!empty($createFields['pri']['required']))
    ->control('priPicker')
    ->items($lang->{$type}->priList)
    ->value(isset($createFields['pri']['default']) ? $createFields['pri']['default'] : '');

$fields->field('estimate')
    ->width('1/4')
    ->required(!empty($createFields['estimate']['required']))
    ->label($lang->story->estimateAB . $lang->story->estimateUnit)
    ->value(isset($createFields['estimate']['default']) ? $createFields['estimate']['default'] : '');

$fields->field('spec')
    ->width('full')
    ->required(!empty($createFields['spec']['required']))
    ->control(array('control' => 'editor', 'templateType' => 'story', 'uid' => $uid))
    ->placeholder($lang->story->specTemplate . "\n" . $lang->noticePasteImg)
    ->value(isset($createFields['spec']['default']) ? $createFields['spec']['default'] : '');

$fields->field('verify')
    ->width('full')
    ->required(!empty($createFields['verify']['required']))
    ->control(array('control' => 'editor', 'uid' => $uid))
    ->value(isset($createFields['verify']['default']) ? $createFields['verify']['default'] : '');

$files = data('story.files') ? data('story.files') : array();
$fields->field('files')->width('full')->control('fileSelector', array('defaultFiles' => array_values($files)));
$fields->field('fileList')->control('hidden')->value(data('storyFiles'));

$fields->field('plan')
       ->hidden((isset($createFields['branch']) && $type == 'story') || !isset($createFields['plan']))
       ->foldable()
       ->required(!empty($createFields['plan']['required']))
       ->control('inputGroup')
       ->items(false)
       ->itemBegin('plan')->control('picker')->id('planIdBox')
       ->items(isset($createFields['plan']['options']) ? $createFields['plan']['options'] : array())
       ->value(isset($createFields['plan']['default']) ? $createFields['plan']['default'] : '')->multiple($type != 'story')->itemEnd()
       ->item(empty($createFields['plan']['options']) && hasPriv('productplan', 'create') ? field()->control('btn')->icon('plus')->url(createLink('productplan', 'create', 'productID=' . data('productID') . '&branch=' . data('branch')))->set(array('data-toggle' => 'modal', 'data-size' => 'lg'))->set('title', $lang->productplan->create) : null)
       ->item(empty($createFields['plan']['options']) ? field()->control(array('control' => 'btn', 'data-on' => 'click', 'data-call' => 'loadProductPlans', 'data-params' => data('productID')))->icon('refresh')->id("loadProductPlans")->set('title', $lang->refresh) : null);

$fields->field('source')
    ->foldable()
    ->width('1/4')
    ->required(!empty($createFields['source']['required']))
    ->items($lang->{$type}->sourceList)
    ->value(isset($createFields['source']['default']) ? $createFields['source']['default'] : '');

$fields->field('sourceNote')
    ->foldable()
    ->width('1/4')
    ->value(isset($createFields['sourceNote']['default']) ? $createFields['sourceNote']['default'] : '');

$fields->field('feedbackBy')
    ->foldable()
    ->className('feedbackBox')
    ->className(!isset($createFields['source']['default']) || !in_array($createFields['source']['default'], $config->story->feedbackSource) ? 'hidden' : '')
    ->id('feedbackBy')
    ->value(isset($createFields['feedbackBy']['default']) ? $createFields['feedbackBy']['default'] : '');

$fields->field('notifyEmail')
    ->foldable()
    ->className('feedbackBox')
    ->className(!isset($createFields['source']['default']) || !in_array($createFields['source']['default'], $config->story->feedbackSource) ? 'hidden' : '')
    ->id('notifyEmail')
    ->value(isset($createFields['notifyEmail']['default']) ? $createFields['notifyEmail']['default'] : '');

$fields->field('mailto')
    ->foldable()
    ->control('mailto');

$fields->field('keywords')->foldable()->required(!empty($createFields['keywords']['required']));

$fields->field('type')->control('hidden')->value($type);
$fields->field('status')->control('hidden')->value('active');

$fields->field('tmpModule')->control('hidden');
$fields->field('tmpCategory')->control('hidden');
$fields->field('tmpSource')->control('hidden');
$fields->field('tmpMailto')->control('hidden');
$fields->field('tmpPri')->control('hidden');
$fields->field('tmpTitle')->control('hidden');
$fields->field('tmpEstimate')->control('hidden');
$fields->field('tmpSourceNote')->control('hidden');
$fields->field('tmpKeywords')->control('hidden');
$fields->field('tmpSpec')->control('hidden');
$fields->field('tmpVerify')->control('hidden');
$fields->field('tmpFiles')->control('hidden');
