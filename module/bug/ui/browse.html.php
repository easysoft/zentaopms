<?php
declare(strict_types=1);
/**
 * The browse view file of bug module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     bug
 * @link        https://www.zentao.net
 */
namespace zin;

$this->bug->setOperateActions($view = 'browse');

foreach($bugs as $bug)
{
    $bug->productName  = zget($products, $bug->product);
    $bug->storyName    = zget($stories, $bug->story);
    $bug->taskName     = zget($tasks, $bug->task);
    $bug->toTaskName   = zget($tasks, $bug->toTask);
    $bug->module       = zget($modulePairs, $bug->module);
    $bug->branch       = zget($branchTagOption, $bug->branch);
    $bug->project      = zget($projectPairs, $bug->project);
    $bug->execution    = zget($executions, $bug->execution);
    $bug->openedBy     = zget($users, $bug->openedBy); 
    $bug->assignedTo   = zget($users, $bug->assignedTo); 
    $bug->resolvedBy   = zget($users, $bug->resolvedBy); 
    $bug->mailto       = zget($users, $bug->mailto); 
    $bug->closedBy     = zget($users, $bug->closedBy); 
    $bug->lastEditedBy = zget($users, $bug->lastEditedBy); 
    $bug->type         = zget($lang->bug->typeList, $bug->type); 
    $bug->confirmed    = zget($lang->bug->confirmedList, $bug->confirmed); 
    $bug->resolution   = zget($lang->bug->resolutionList, $bug->resolution); 
    $bug->os           = zget($lang->bug->osList, $bug->os); 
    $bug->browser      = zget($lang->bug->browserList, $bug->browser); 

    $actions = array();
    foreach($this->config->bug->dtable->fieldList['actions']['actionsMap'] as $actionCode => $actionMap)
    {
        $isClickable = $this->bug->isClickable($bug, $actionCode);

        $actions[] = $isClickable ? $actionCode : array('name' => $actionCode, 'disabled' => true);
    }
    $bug->actions = $actions;
}

$cols = array_values($config->bug->dtable->fieldList);
$data = array_values($bugs);

featureBar
(
    li(searchToggle())
);

toolbar();

sidebar();

dtable
(
    set::checkable(true),
    set::cols($cols),
    set::data($data)
);

render();
