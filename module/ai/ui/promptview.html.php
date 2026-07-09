<?php
declare(strict_types=1);
namespace zin;

detailHeader
(
    to::prefix
    (
        backBtn
        (
            set::icon('back'),
            setClass('primary-outline size-md'),
            set::url(inlink('prompts')),
            $lang->goback
        ),
    ),
    to::title
    (
        entityLabel
        (
            set::entityID($prompt->id),
            set::level(1),
            set::text($prompt->name)
        )
    ),
    to::suffix
    (
        $config->edition != 'open' && common::hasPriv('ai', 'createprompt') ? btn
        (
            setClass('primary'),
            set::icon('plus'),
            set::url(createLink('ai', 'promptbasicinfo')),
            $lang->ai->prompts->create
        ) : null
    )
);

$actionObject = '';
if(!empty($prompt->actionPurpose))
{
    $actionPurposePath = explode('.', $prompt->actionPurpose, 2);
    if(count($actionPurposePath) == 2)
    {
        $actionObjectCommon = $lang->ai->targetForm[$actionPurposePath[0]]['common'] ?? '';
        $actionObjectName   = $lang->ai->targetForm[$actionPurposePath[0]][$actionPurposePath[1]] ?? '';
        if(!empty($actionObjectName)) $actionObject = $prompt->actionPurpose == 'empty.empty' || empty($actionObjectCommon) ? $actionObjectName : $actionObjectCommon . ' / ' . $actionObjectName;
    }
}

$fnBuildPublishInfo = function() use ($actions, $prompt, $users, $lang)
{
    $lastPublishAction = null;
    foreach(array_reverse($actions) as $action)
    {
        if(in_array($action->action, array('published', 'unpublished')))
        {
            $lastPublishAction = $action;
            break;
        }
    }

    $items = array();
    if($prompt->status == 'active')
    {
        $items[] = item(set::name($lang->ai->prompts->publishedBy),  zget($users, empty($lastPublishAction) ? $prompt->createdBy : $lastPublishAction->actor));
    }
    else
    {
        $items[] = item
        (
            set::name(empty($lastPublishAction) ? $lang->ai->prompts->publishedBy : $lang->ai->prompts->draftedBy),
            !empty($lastPublishAction) ? zget($users, $lastPublishAction->actor) : ''
        );
    }
    return $items;
};

if($prompt->status != 'draft' || !$this->ai->isExecutable($prompt)) unset($config->ai->actions->promptview['mainActions'][1]);
$actionList = $this->loadModel('common')->buildOperateMenu($prompt);

$promptContent = $prompt->purpose;
if(!empty($prompt->elaboration)) $promptContent .= "\n\n" . $prompt->elaboration;

$skillName = !empty($skill) && !empty($skill->name) ? $skill->name : '';

$knowledgeLibNames = array();
if(!empty($knowledgeLibs))
{
    foreach($knowledgeLibs as $knowledgeLib)
    {
        if(!empty($knowledgeLib->name)) $knowledgeLibNames[] = $knowledgeLib->name;
    }
}
$knowledgeLibText = implode($lang->ai->prompts->fieldSeparator, $knowledgeLibNames);
$displayPosition  = isset($prompt->displayPosition) && isset($lang->ai->prompts->displayPositionList[$prompt->displayPosition]) ? $lang->ai->prompts->displayPositionList[$prompt->displayPosition] : '';

detailBody
(
    sectionList
    (
        section
        (
            set::title($lang->ai->prompts->processObject),
            set::content($prompt->module ? $lang->ai->moduleList[$prompt->module]['common'] : '')
        ),
        section(set::title($lang->ai->prompts->actionObject), set::content($actionObject)),
        section(set::title($lang->ai->prompts->displayPosition), set::content($displayPosition)),
        section(set::title($lang->ai->prompts->role), set::content($prompt->role)),
        section(set::title($lang->ai->prompts->prompt), set::content(wg(p(setClass('pre'), $promptContent)))),
        section(set::title($lang->ai->prompts->skill), set::content($skillName)),
        section(set::title($lang->ai->prompts->knowledgeLib), set::content($knowledgeLibText))
    ),
    history
    (
        setClass('canvas shadow-none mt-2'),
        setStyle(array('--tw-ring-color' => '#fff')),
        set::objectType('prompt'),
        set::objectID($prompt->id)
    ),
    $config->edition != 'open' ? floatToolbar
    (
        set::object($prompt),
        to::prefix(backBtn(set::icon('back'), set::url(inlink('prompts')), $lang->goback)),
        set::main($actionList['mainActions']),
        set::suffix($actionList['suffixActions'])
    ) : null,
    detailSide
    (
        setClass('ml-2'),
        tabs
        (
            set::collapse(true),
            tabPane
            (
                set::key('promptBasicInfo'),
                set::title($lang->ai->prompts->basicInfo),
                set::active(true),
                tableData
                (
                    item(set::name($lang->prompt->module), $prompt->module ? $lang->ai->moduleList[$prompt->module]['common'] : ''),
                    item(set::name($lang->prompt->desc),   div(setClass('w-64 text-clip'), set::title($prompt->desc), $prompt->desc)),
                    item(set::name($lang->prompt->status), $lang->ai->prompts->statuses[$prompt->status]),
                    item(set::name($lang->prompt->model), zui::aiModelName($prompt->model)),
                )
            ),
            tabPane
            (
                set::key('promptEditInfo'),
                set::title($lang->ai->prompts->editInfo),
                tableData
                (
                    item(set::name($lang->prompt->createdBy), zget($users, $prompt->createdBy) . $lang->at . $prompt->createdDate),
                    $fnBuildPublishInfo(),
                    item(set::name($lang->prompt->editedBy),  $prompt->editedBy ? zget($users, $prompt->editedBy) . $lang->at . $prompt->editedDate : ''),
                )
            )
        )
    )
);
