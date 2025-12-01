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
            set::url(createLink('ai', 'createprompt')),
            setData('toggle', 'modal'),
            setData('size', 'sm'),
            $lang->ai->prompts->create,
        ) : null
    )
);

$selectTargetForm = '';
if(!empty($prompt->targetForm))
{
    $targetForm       = explode('.', $prompt->targetForm);
    $selectTargetForm = $lang->ai->targetForm[$targetForm[0]][$targetForm[1]];
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

$fnBuildFieldConfig = function() use ($lang, $fieldConfig)
{
    if(empty($fieldConfig)) return array();

    $fields = array();
    foreach($fieldConfig as $field)
    {
        $control  = $lang->ai->miniPrograms->field->typeList[$field->type];
        $required = $lang->ai->requiredList[$field->required];
        $options  = $field->options ?: '-';
        $fields[] = div(setClass('mb-1'), $field->name . ' (' . $control . ', ' . $required . ') : ' . $options);
    }
    return section(set::title($lang->ai->miniPrograms->field->fields), $fields);
};

detailBody
(
    sectionList
    (
        section(set::title($lang->ai->prompts->role), set::content($prompt->role)),
        section(set::title($lang->ai->prompts->characterization), set::content($prompt->characterization)),
        section
        (
            set::title($lang->ai->prompts->object),
            set::content($prompt->module ? $lang->ai->dataSource[$prompt->module]['common'] : '')
        ),
        section(set::title($lang->ai->prompts->field), set::content($dataPreview)),
        $fnBuildFieldConfig(),
        section(set::title($lang->ai->prompts->setPurpose), set::content($prompt->purpose)),
        section(set::title($lang->ai->prompts->elaboration), set::content($prompt->elaboration)),
        section(set::title($lang->ai->prompts->selectTargetForm), set::content($selectTargetForm))
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
                    item(set::name($lang->prompt->module), $prompt->module ? $lang->ai->dataSource[$prompt->module]['common'] : ''),
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
