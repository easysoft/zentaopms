<?php
declare(strict_types=1);
namespace zin;

global $lang, $config, $app;

$fields = defineFieldList('testcase.edit');
$case = data('case');
$isLibCase = data('isLibCase');

if($case->type != 'unit') unset($lang->testcase->typeList['unit']);

$priList = array_filter($lang->testcase->priList);

$files = array();
foreach($case->files as $caseFile)
{
    if($caseFile->extra != '') continue;
    $files[] = $caseFile;
}

$linkCaseItems = array();
if(isset($case->linkCaseTitles))
{
    foreach($case->linkCaseTitles as $linkCaseID => $linkCaseTitle)
    {
        $linkCaseItems[] = array('text' => "#{$linkCaseID} {$linkCaseTitle}", 'value' => $linkCaseID, 'checked' => true);
    }
}

$linkBugItems = array();
if(isset($case->toBugs))
{
    foreach($case->toBugs as $bugID => $bug)
    {
        $linkBugItems[] = array('text' => "#{$bugID} {$bug->title}", 'value' => $bugID, 'checked' => true);
    }
}

$rootID   = $isLibCase ? $case->lib : $case->product;
$viewType = $isLibCase ? 'caselib' : 'case';
$createModuleLink = createLink('tree', 'browse', "rootID={$rootID}&view={$viewType}&currentModuleID=0&branch={$case->branch}");

$buildProduct = function() use ($case)
{
    $product = data('product');

    return inputGroup
    (
        picker
        (
            setID('product'),
            set::name('product'),
            set::items(data('products')),
            set::required(true),
            set::value($case->product)
        ),
        picker
        (
            setClass(!isset($product->type) || $product->type == 'normal' ? 'hidden' : ''),
            setID('branch'),
            set::name('branch'),
            set::required(true),
            set::items(data('branchTagOption')),
            set::value($case->branch)
        )
    );
};

$buildModule = function() use ($createModuleLink, $case)
{
    return modulePicker
    (
        setID('module'),
        set::items(data('moduleOptionMenu')),
        set::value($case->module),
        set::manageLink($createModuleLink)
    );
};

$buildStory = function() use ($case)
{
    return remotePicker
    (
        setID('story'),
        set::name('story'),
        set::type('casestories'),
        set::params("productID={$case->product}&moduleID={$case->module}&branch={$case->branch}&storyID={$case->story}"),
        $case->story ? set::value($case->story) : null
    );
};

$buildType = function() use ($case)
{
    global $lang;

    return inputGroup
    (
        picker
        (
            set::name('type'),
            set::items($lang->testcase->typeList),
            set::value($case->type),
            set::required(true),
            $case->type == 'unit' ? set::disabled(true) : null
        ),
        helper::hasFeature('automated') ? span
        (
            setClass('input-group-addon'),
            control
            (
                set::type('checkbox'),
                set::name('auto'),
                set::value('auto'),
                set::text($lang->testcase->automated),
                set::checked($case->auto == 'auto' ? true : false)
            )
        ) : null
    );
};

$buildAutoScript = function()
{
    global $config;

    return div
    (
        setClass('flex'),
        fileSelector
        (
            set::name('scriptFile'),
            set::accept($config->testcase->scriptAcceptFileTypes),
            set::maxFileCount(1),
            set::multiple(false),
            set::onAdd(jsRaw('window.readScriptContent')),
            set::onRemove(jsRaw('window.showUploadScriptBtn'))
        ),
        input
        (
            set::type('hidden'),
            set::name('script')
        )
    );
};

$buildStatus = function() use ($case)
{
    global $lang;
    $forceNotReview = data('forceNotReview');

    if(!$forceNotReview && $case->status == 'wait') return span(setClass('form-control-static'), $lang->testcase->statusList[$case->status]);

    return picker
    (
        set::name('status'),
        set::items($lang->testcase->statusList),
        set::required(true),
        set::value($case->status)
    );
};

$buildLinkCase = function() use ($case)
{
    global $lang;

    return a
    (
        set::href(createLink('testcase', 'linkCases', "caseID={$case->id}")),
        set('data-toggle', 'modal'),
        set('data-size', 'lg'),
        $lang->testcase->linkCases
    );
};

$buildLinkBug = function() use ($case)
{
    global $lang;

    return a
    (
        set::href(createLink('testcase', 'linkBugs', "caseID={$case->id}")),
        set('data-toggle', 'modal'),
        set('data-size', 'lg'),
        $lang->testcase->linkBugs
    );
};

$buildFiles = function() use ($files)
{
    return fileSelector($files ? set::defaultFiles(array_values($files)) : false);
};

$buildOpenedBy = function() use ($case)
{
    global $lang;
    $users = data('users');
    if(!$case->openedBy) return null;
    return span(setClass('form-control-static'), zget($users, $case->openedBy) . $lang->at . $case->openedDate);
};

$buildLastEdited = function() use ($case)
{
    global $lang;
    $users = data('users');
    if(!$case->lastEditedBy) return null;
    return span(setClass('form-control-static'), zget($users, $case->lastEditedBy) . $lang->at . $case->lastEditedDate);
};

/* ====== Main column fields (position = info) ====== */

$fields->field('title')
    ->position('info')
    ->width('full')
    ->required(true)
    ->control('colorInput', array('colorValue' => $case->color))
    ->placeholder($lang->case->title)
    ->value($case->title);

$fields->field('scene')
    ->position('info')
    ->width('full')
    ->hidden($isLibCase)
    ->control('picker')
    ->items(data('sceneOptionMenu'))
    ->value($case->scene);

$fields->field('precondition')
    ->position('info')
    ->width('full')
    ->required(strpos(",{$config->testcase->edit->requiredFields},", ",precondition,") !== false)
    ->control('textarea', array('rows' => 2))
    ->value($case->precondition);

$fields->field('steps')
    ->position('info')
    ->width('full')
    ->control('stepsEditor', array('data' => $case->steps));

$fields->field('files')
    ->position('info')
    ->width('full')
    ->control($buildFiles);

/* ====== Sidebar: legendBasicInfo ====== */

$product = data('product');

$fields->field('lib')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->hidden(!$isLibCase)
    ->control('picker')
    ->required(true)
    ->items(data('libraries'))
    ->value($case->lib);

$fields->field('product')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->hidden($isLibCase || (isset($product->shadow) && $product->shadow))
    ->control($buildProduct);

$fields->field('module')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->required(strpos(",{$config->testcase->edit->requiredFields},", ",module,") !== false)
    ->control($buildModule);

$fields->field('story')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->hidden($isLibCase)
    ->required(strpos(",{$config->testcase->edit->requiredFields},", ",story,") !== false)
    ->control($buildStory);

$fields->field('type')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->required(true)
    ->control($buildType);

$fields->field('autoScript')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->width('full')
    ->className('autoScript')
    ->hidden($case->auto != 'auto')
    ->control($buildAutoScript);

$fields->field('stage')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->required(strpos(",{$config->testcase->edit->requiredFields},", ",stage,") !== false)
    ->control('picker')
    ->items($lang->testcase->stageList)
    ->value($case->stage)
    ->multiple(true);

$fields->field('pri')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->required(strpos(",{$config->testcase->edit->requiredFields},", ",pri,") !== false)
    ->control('priPicker')
    ->items($priList)
    ->value($case->pri);

$fields->field('status')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->required(strpos(",{$config->testcase->edit->requiredFields},", ",status,") !== false)
    ->control($buildStatus);

$fields->field('keywords')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->required(strpos(",{$config->testcase->edit->requiredFields},", ",keywords,") !== false)
    ->control('input')
    ->value($case->keywords);

$fields->field('linkCase')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->hidden($isLibCase || !hasPriv('testcase', 'linkCases') || $config->edition != 'open')
    ->control($buildLinkCase);

$fields->field('linkCaseList')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->hidden($isLibCase || !hasPriv('testcase', 'linkCases') || $config->edition != 'open')
    ->label('')
    ->className(!isset($case->linkCaseTitles) ? 'hidden' : '')
    ->control(array('control' => 'checkList', 'name' => 'linkCase[]'))
    ->items($linkCaseItems)
    ->value(isset($case->linkCaseTitles) ? array_keys($case->linkCaseTitles) : '');

$fields->field('linkBug')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->hidden($isLibCase || !hasPriv('testcase', 'linkBugs'))
    ->control($buildLinkBug);

$fields->field('linkBugList')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->hidden($isLibCase || !hasPriv('testcase', 'linkBugs'))
    ->label('')
    ->className(!isset($case->toBugs) ? 'hidden' : '')
    ->control(array('control' => 'checkList', 'name' => 'linkBug[]'))
    ->items($linkBugItems)
    ->value(isset($case->toBugs) ? array_keys($case->toBugs) : '');

/* ====== Sidebar: legendOpenAndEdit ====== */

$fields->field('openedBy')
    ->position('basic')
    ->group($lang->testcase->legendOpenAndEdit)
    ->control($buildOpenedBy);

$fields->field('lastEdited')
    ->position('basic')
    ->group($lang->testcase->legendOpenAndEdit)
    ->control($buildLastEdited);
