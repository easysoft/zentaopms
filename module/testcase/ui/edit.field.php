<?php
declare(strict_types=1);
namespace zin;

global $lang, $config, $app;

$fields = defineFieldList('testcase.edit');

if(data('case.type') != 'unit') unset($lang->testcase->typeList['unit']);

$files = array();
if(!empty(data('case.files')))
{
    foreach(data('case.files') as $caseFile)
    {
        if($caseFile->extra != '') continue;
        $files[] = $caseFile;
    }
}

$linkCaseItems = array();
if(data('case.linkCaseTitles') !== null)
{
    foreach(data('case.linkCaseTitles') as $linkCaseID => $linkCaseTitle)
    {
        $linkCaseItems[] = array('text' => "#{$linkCaseID} {$linkCaseTitle}", 'value' => $linkCaseID, 'checked' => true);
    }
}

$linkBugItems = array();
if(data('case.toBugs') !== null)
{
    foreach(data('case.toBugs') as $bugID => $bug)
    {
        $linkBugItems[] = array('text' => "#{$bugID} {$bug->title}", 'value' => $bugID, 'checked' => true);
    }
}

$rootID   = data('isLibCase') ? data('case.lib') : data('case.product');
$viewType = data('isLibCase') ? 'caselib' : 'case';
$createModuleLink = createLink('tree', 'browse', 'rootID=' . $rootID . '&view=' . $viewType . '&currentModuleID=0&branch=' . data('case.branch'));

$buildProduct = function()
{
    return inputGroup
    (
        picker
        (
            setID('product'),
            set::name('product'),
            set::items(data('products')),
            set::required(true),
            set::value(data('case.product'))
        ),
        picker
        (
            setClass(data('product.type') === null || data('product.type') == 'normal' ? 'hidden' : ''),
            setID('branch'),
            set::name('branch'),
            set::required(true),
            set::items(data('branchTagOption')),
            set::value(data('case.branch'))
        )
    );
};

$buildModule = function() use ($createModuleLink)
{
    return modulePicker
    (
        setID('module'),
        set::items(data('moduleOptionMenu')),
        set::value(data('case.module')),
        set::manageLink($createModuleLink)
    );
};

$buildStory = function()
{
    return remotePicker
    (
        setID('story'),
        set::name('story'),
        set::type('casestories'),
        set::params('productID=' . data('case.product') . '&moduleID=' . data('case.module') . '&branch=' . data('case.branch') . '&storyID=' . data('case.story')),
        data('case.story') ? set::value(data('case.story')) : null
    );
};

$buildType = function()
{
    global $lang;

    return inputGroup
    (
        picker
        (
            set::name('type'),
            set::items($lang->testcase->typeList),
            set::value(data('case.type')),
            set::required(true),
            data('case.type') == 'unit' ? set::disabled(true) : null
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
                set::checked(data('case.auto') == 'auto' ? true : false)
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

$buildStatus = function()
{
    global $lang;

    if(!data('forceNotReview') && data('case.status') == 'wait') return span(setClass('form-control-static'), $lang->testcase->statusList[data('case.status')]);

    return picker
    (
        set::name('status'),
        set::items($lang->testcase->statusList),
        set::required(true),
        set::value(data('case.status'))
    );
};

$buildLinkCase = function()
{
    global $lang;

    return a
    (
        set::href(createLink('testcase', 'linkCases', 'caseID=' . data('case.id'))),
        set('data-toggle', 'modal'),
        set('data-size', 'lg'),
        $lang->testcase->linkCases
    );
};

$buildLinkBug = function()
{
    global $lang;

    return a
    (
        set::href(createLink('testcase', 'linkBugs', 'caseID=' . data('case.id'))),
        set('data-toggle', 'modal'),
        set('data-size', 'lg'),
        $lang->testcase->linkBugs
    );
};

$buildFiles = function() use ($files)
{
    return fileSelector($files ? set::defaultFiles(array_values($files)) : false);
};

$buildOpenedBy = function()
{
    global $lang;
    if(!data('case.openedBy')) return null;
    return span(setClass('form-control-static'), zget(data('users'), data('case.openedBy')) . $lang->at . data('case.openedDate'));
};

$buildLastEdited = function()
{
    global $lang;
    if(!data('case.lastEditedBy')) return null;
    return span(setClass('form-control-static'), zget(data('users'), data('case.lastEditedBy')) . $lang->at . data('case.lastEditedDate'));
};

/* ====== Main column fields (position = info) ====== */

$fields->field('title')
    ->position('info')
    ->width('full')
    ->required(true)
    ->control('colorInput', array('colorValue' => data('case.color')))
    ->placeholder($lang->case->title)
    ->value(data('case.title'));

$fields->field('scene')
    ->position('info')
    ->width('full')
    ->hidden(data('isLibCase'))
    ->control('picker')
    ->items(data('sceneOptionMenu'))
    ->value(data('case.scene'));

$fields->field('precondition')
    ->position('info')
    ->width('full')
    ->required(strpos(",{$config->testcase->edit->requiredFields},", ",precondition,") !== false)
    ->control('textarea', array('rows' => 2))
    ->value(data('case.precondition'));

$fields->field('steps')
    ->position('info')
    ->width('full')
    ->control('stepsEditor', array('data' => data('case.steps')));

$fields->field('files')
    ->position('info')
    ->width('full')
    ->control($buildFiles);

/* ====== Sidebar: legendBasicInfo ====== */

$fields->field('lib')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->hidden(!data('isLibCase'))
    ->control('picker')
    ->required(true)
    ->items(data('libraries'))
    ->value(data('case.lib'));

$fields->field('product')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->hidden(data('isLibCase') || !empty(data('product.shadow')))
    ->control($buildProduct);

$fields->field('module')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->required(strpos(",{$config->testcase->edit->requiredFields},", ",module,") !== false)
    ->control($buildModule);

$fields->field('story')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->hidden(data('isLibCase'))
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
    ->hidden(data('case.auto') != 'auto')
    ->control($buildAutoScript);

$fields->field('stage')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->required(strpos(",{$config->testcase->edit->requiredFields},", ",stage,") !== false)
    ->control('picker')
    ->items($lang->testcase->stageList)
    ->value(data('case.stage'))
    ->multiple(true);

$fields->field('pri')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->required(strpos(",{$config->testcase->edit->requiredFields},", ",pri,") !== false)
    ->control('priPicker')
    ->items(array_filter($lang->testcase->priList))
    ->value(data('case.pri'));

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
    ->value(data('case.keywords'));

$fields->field('relatedCase')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->hidden(data('isLibCase') || !hasPriv('testcase', 'linkCases') || $config->edition != 'open')
    ->control($buildLinkCase);

$fields->field('linkCaseList')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->hidden(data('isLibCase') || !hasPriv('testcase', 'linkCases') || $config->edition != 'open')
    ->label('')
    ->className(data('case.linkCaseTitles') === null ? 'hidden' : '')
    ->control(array('control' => 'checkList', 'name' => 'linkCase[]'))
    ->items($linkCaseItems)
    ->value(data('case.linkCaseTitles') !== null ? array_keys(data('case.linkCaseTitles')) : '');

$fields->field('relatedBug')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->hidden(data('isLibCase') || !hasPriv('testcase', 'linkBugs'))
    ->control($buildLinkBug);

$fields->field('linkBugList')
    ->position('basic')
    ->group($lang->testcase->legendBasicInfo)
    ->hidden(data('isLibCase') || !hasPriv('testcase', 'linkBugs'))
    ->label('')
    ->className(data('case.toBugs') === null ? 'hidden' : '')
    ->control(array('control' => 'checkList', 'name' => 'linkBug[]'))
    ->items($linkBugItems)
    ->value(data('case.toBugs') !== null ? array_keys(data('case.toBugs')) : '');

/* ====== Sidebar: legendOpenAndEdit ====== */

$fields->field('openedBy')
    ->position('basic')
    ->group($lang->testcase->legendOpenAndEdit)
    ->control($buildOpenedBy);

$fields->field('lastEdited')
    ->position('basic')
    ->group($lang->testcase->legendOpenAndEdit)
    ->control($buildLastEdited);
