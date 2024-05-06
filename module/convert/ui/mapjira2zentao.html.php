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

$items = array();
foreach($lang->convert->jira->steps as $key => $label)
{
    $items[] = (object)array('text' => $label, 'active' => $key == $step);
}

div
(
    setClass('main-header relative center size-lg mx-auto my-4'),
    setStyle(array('max-width' => 'var(--zt-panel-form-max-width)')),
    div
    (
        setClass('absolute left-0'),
        $lang->convert->jira->mapJira2Zentao
    ),
    navigator
    (
        set::items($items)
    ),
);

$buildHeader = function(string $label, int $count = 2): h
{
    return div
    (
        setClass("flex justify-center text-md font-bold w-1/{$count}"),
        $label
    );
};

$buildJira = function(string $name, int $count, string $label, int $value): h
{
    return div
    (
        setClass("flex justify-center w-1/{$count}"),
        $label,
        input
        (
            set::type('hidden'),
            set::name("{$name}[]"),
            set::value($value)
        )
    );
};

$buildZenTao = function(string $name, int $count, array $items): h
{
    return div
    (
        setClass("ml-4 w-1/{$count}"),
        picker
        (
            set::name("{$name}[]"),
            set::items($items)
        )
    );
};

$rows = array();
if($step == 1)
{
    $rows[] = formRow
    (
        $buildHeader($lang->convert->jira->jiraObject),
        $buildHeader($lang->convert->jira->zentaoObject)
    );
    foreach($issueTypeList as $id => $issueType)
    {
        $value = $method == 'db' ? $issueType->pname : $issueType['name'];

        $rows[] = formRow
        (
            $buildJira('jiraObject', 2, $value, $id),
            $buildZenTao('zentaoObject', 2, $lang->convert->jira->zentaoObjectList)
        );
    }
}

if($step == 2)
{
    $rows[] = formRow
    (
        $buildHeader($lang->convert->jira->jiraLinkType),
        $buildHeader($lang->convert->jira->zentaoLinkType)
    );
    foreach($linkTypeList as $id => $linkType)
    {
        $value = $method == 'db' ? $linkType->linkname : $linkType['linkname'];

        $rows[] = formRow
        (
            $buildJira('jiraLinkType', 2, $value, $id),
            $buildZenTao('zentaoLinkType', 2, $lang->convert->jira->zentaoLinkTypeList)
        );
    }
}

if($step == 3)
{
    $rows[] = formRow
    (
        $buildHeader($lang->convert->jira->jiraResolution, 3),
        $buildHeader($lang->convert->jira->zentaoResolution, 3),
        $buildHeader($lang->convert->jira->zentaoReason, 3)
    );
    foreach($resolutionList as $id => $resolution)
    {
        $value = $method == 'db' ? $resolution->pname : $resolution['name'];

        $rows[] = formRow
        (
            $buildJira('jiraResolution', 3, $value, $id),
            $buildZenTao('zentaoResolution', 3, $lang->bug->resolutionList),
            $buildZenTao('zentaoReason', 3, $lang->story->reasonList)
        );
    }
}

if($step == 4)
{
    $rows[] = formRow
    (
        $buildHeader($lang->convert->jira->jiraStatus, 5),
        $buildHeader($lang->convert->jira->storyStatus, 5),
        $buildHeader($lang->convert->jira->storyStage, 5),
        $buildHeader($lang->convert->jira->taskStatus, 5),
        $buildHeader($lang->convert->jira->bugStatus, 5)
    );
    foreach($statusList as $id => $status)
    {
        $value = $method == 'db' ? $status->pname : $status['name'];

        $rows[] = formRow
        (
            $buildJira('jiraStatus', 5, $value, $id),
            $buildZenTao('storyStatus', 5, $lang->story->statusList),
            $buildZenTao('storyStage', 5, $lang->story->stageList),
            $buildZenTao('taskStatus', 5, $lang->task->statusList),
            $buildZenTao('bugStatus', 5, $lang->bug->statusList)
        );
    }
}

$backUrl = $step == 1 ? inlink('importNotice', "method={$method}") : inlink('mapJira2Zentao', "method={$method}&dbName={$dbName}&step=" . --$step);

formPanel
(
    set::submitBtnText($lang->convert->jira->next),
    set::backUrl($backUrl),
    $rows
);

render();
