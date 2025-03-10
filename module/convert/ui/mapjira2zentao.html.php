<?php
declare(strict_types=1);
/**
 * The map jira to zentao view file of convert module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     convert
 * @link        https://www.zentao.net
 */
namespace zin;

include('jiraside.html.php');

$buildHeader = function(string $label): h
{
    return div
    (
        setClass("flex-1 text-center text-md font-bold"),
        $label
    );
};

$buildJira = function(string $name, string $label, int $value): h
{
    return div
    (
        setClass("flex-1 text-center"),
        $label,
        input
        (
            set::type('hidden'),
            set::name("{$name}[]"),
            set::value($value)
        )
    );
};

$buildZenTao = function(string $name, array $items, string|int $index = '', string $key = '', string $default = '') use($jiraRelation, $defaultValue): h
{
    $default = !empty($defaultValue[$name][$key]) ? $defaultValue[$name][$key] : $default;
    return div
    (
        setClass("flex-1 mx-2"),
        picker
        (
            set::name("{$name}[$index]"),
            set::items($items),
            set::value(!empty($jiraRelation[$name][$index]) ? $jiraRelation[$name][$index] : $default)
        )
    );
};

$rows = array();
if($step == 'object')
{
    $rows[] = div
    (
        setClass('panel-title'),
        span(setClass('text-lg'), $lang->convert->jira->steps[$step]),
        span
        (
            icon('help self-center text-warning mr-1 pl-2'),
            setClass('self-center font-medium text-gray'),
            $lang->convert->jira->mapObjectNotice
        )
    );
    $rows[] = formRow
    (
        $buildHeader($lang->convert->jira->jiraObject),
        $buildHeader($lang->convert->jira->zentaoObject)
    );
    foreach($issueTypeList as $id => $issueType)
    {
        $value = $issueType->pname;

        $rows[] = formRow
        (
            $buildJira('jiraObject', $value, $id),
            $buildZenTao('zentaoObject', $zentaoObjects, $id, $value)
        );
    }
}

if(!empty($jiraRelation['zentaoObject']) && in_array($step, array_keys($jiraRelation['zentaoObject'])))
{
    if(!empty($fieldList))
    {
        $rows[] = div
        (
            setClass('panel-title'),
            span(setClass('text-lg'), $lang->convert->jira->objectField),
            span
            (
                icon('help self-center text-warning mr-1 pl-2'),
                setClass('self-center font-medium text-gray'),
                $lang->convert->jira->mapFieldNotice
            )
        );
        $rows[] = formRow
        (
            $buildHeader(sprintf($lang->convert->jira->jiraField,   zget($issueTypeList[$step], 'pname', ''))),
            $buildHeader(sprintf($lang->convert->jira->zentaoField, zget($zentaoObjects, $jiraRelation['zentaoObject'][$step])))
        );

        $zentaoFields = $this->convert->getZentaoFields($jiraRelation['zentaoObject'][$step]);
        foreach($fieldList as $id => $field)
        {
            $rows[] = formRow
            (
                $buildJira("jiraField$step", $field, $id),
                $buildZenTao("zentaoField$step", $zentaoFields, $id, $field, 'add_field')
            );
        }
        $rows[] = divider();
    }
    if(!empty($statusList))
    {
        $zentaoStatusList = $this->convert->getZentaoStatus($jiraRelation['zentaoObject'][$step]);
        $defaultStatus    = $this->convert->convertStatus($jiraRelation['zentaoObject'][$step], '', '');
        $rows[] = div
        (
            setClass('panel-title'),
            span(setClass('text-lg'), $lang->convert->jira->objectStatus),
            span
            (
                icon('help self-center text-warning mr-1 pl-2'),
                setClass('self-center font-medium text-gray'),
                sprintf($lang->convert->jira->mapStatusNotice, zget($zentaoStatusList, $defaultStatus))
            )
        );
        $rows[] = formRow
        (
            $buildHeader(sprintf($lang->convert->jira->jiraStatus,   zget($issueTypeList[$step], 'pname', ''))),
            $buildHeader(sprintf($lang->convert->jira->zentaoStatus, zget($zentaoObjects, $jiraRelation['zentaoObject'][$step]))),
            in_array($jiraRelation['zentaoObject'][$step], array('requirement', 'story', 'epic')) ? $buildHeader(sprintf($lang->convert->jira->zentaoStage, zget($zentaoObjects, $jiraRelation['zentaoObject'][$step]))) : null
        );

        foreach($statusList as $id => $status)
        {
            $rows[] = formRow
            (
                $buildJira("jiraStatus$step", $status, $id),
                $buildZenTao("zentaoStatus$step", $zentaoStatusList, $id, $status),
                in_array($jiraRelation['zentaoObject'][$step], array('requirement', 'story', 'epic')) ? $buildZenTao("zentaoStage$step", $lang->story->stageList, $id, $status) : null
            );
        }
    }
    if(!empty($jiraActions))
    {
        $rows[] = formRow
        (
            setClass('hidden'),
            $buildHeader(sprintf($lang->convert->jira->jiraAction,   zget($issueTypeList[$step], 'pname', ''))),
            $buildHeader(sprintf($lang->convert->jira->zentaoAction, zget($zentaoObjects, $jiraRelation['zentaoObject'][$step])))
        );

        $zentaoActions = $this->convert->getZentaoActions($jiraRelation['zentaoObject'][$step]);
        foreach($jiraActions['actions'] as $id => $action)
        {
            $value = $action['name'];
            $rows[] = formRow
            (
                setClass('hidden'),
                $buildJira("jiraAction$step", $value, $id),
                $buildZenTao("zentaoAction$step", $zentaoActions, $id, $value, 'add_action')
            );
        }
    }

    if(in_array($jiraRelation['zentaoObject'][$step], array('bug', 'task', 'story', 'requirement', 'epic')))
    {
        $rows[] = divider();
        $rows[] = div
        (
            setClass('panel-title'),
            span(setClass('text-lg'), $lang->convert->jira->objectResolution),
            span
            (
                icon('help self-center text-warning mr-1 pl-2'),
                setClass('self-center font-medium text-gray'),
                $lang->convert->jira->mapReasonNotice
            )
        );
        $rows[] = formRow
        (
            $buildHeader(sprintf($lang->convert->jira->jiraResolution, zget($issueTypeList[$step], 'pname', ''))),
            $buildHeader(sprintf($jiraRelation['zentaoObject'][$step] == 'bug' ? $lang->convert->jira->zentaoResolution : $lang->convert->jira->zentaoReason, zget($zentaoObjects, $jiraRelation['zentaoObject'][$step])))
        );

        foreach($resolutionList as $id => $resolution)
        {
            $value  = $resolution->pname;
            $module = $jiraRelation['zentaoObject'][$step];
            if($module == 'epic' || $module == 'story' || $module == 'requirement') $module = 'story';
            $reasonList = $module == 'bug' ? $lang->bug->resolutionList + array('add_resolution' => $lang->convert->add) : $lang->{$module}->reasonList + array('add_reason' => $lang->convert->add);
            if($module == 'epic' || $module == 'story' || $module == 'requirement') unset($reasonList['subdivided']);
            $rows[] = formRow
            (
                $buildJira("jiraResolution$step", $value, $id),
                $module == 'bug' ? $buildZenTao("zentaoResolution$step", $reasonList, $id, $value) : $buildZenTao("zentaoReason$step", $reasonList, $id, $value)
            );
        }
    }
}

if($step == 'relation')
{
    $rows[] = div
    (
        setClass('panel-title'),
        span(setClass('text-lg'), $lang->convert->jira->steps[$step]),
        span
        (
            icon('help self-center text-warning mr-1 pl-2'),
            setClass('self-center font-medium text-gray'),
            $lang->convert->jira->mapRelationNotice
        )
    );
    $rows[] = formRow
    (
        $buildHeader($lang->convert->jira->jiraLinkType),
        $buildHeader($lang->convert->jira->zentaoLinkType)
    );
    $relationList = $this->convert->getZentaoRelationList();
    foreach($linkTypeList as $id => $linkType)
    {
        $value = $linkType->LINKNAME;

        $rows[] = formRow
        (
            $buildJira('jiraLinkType', $value, $id),
            $buildZenTao('zentaoLinkType', $relationList, $id)
        );
    }
}

div
(
    setClass('flex'),
    panel
    (
        setClass('w-1/4 mr-4 overflow-y-scroll scrollbar-thin scrollbar-hover'),
        setStyle(array('max-height' => 'calc(100vh - 130px)')),
        $items
    ),
    panel
    (
        setClass('flex-1 m-0 p-0 overflow-y-scroll scrollbar-thin scrollbar-hover'),
        setStyle(array('max-height' => 'calc(100vh - 130px)')),
        formPanel
        (
            on::change('[name^="zentaoField"]', 'changeField'),
            setClass('p-0'),
            set::actionsClass('hidden'),
            $rows
        )
    )
);

render();
