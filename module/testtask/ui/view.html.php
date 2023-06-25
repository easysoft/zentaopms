<?php
declare(strict_types=1);
/**
 * The testtask view file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;

$isInModal = isAjaxRequest('modal');

detailHeader
(
    to::title
    (
        entityLabel
        (
            set(array('entityID' => $task->id, 'level' => 1, 'text' => $task->name))
        )
    ),
);

$taskBuild = array
(
    'text' => $task->buildName,
    'url' => createLink('build', 'view', "buildID=$task->build"),
);
if($isInModal)              $taskBuild = $task->buildName;
if($task->build == 'trunk') $taskBuild = $lang->trunk;

$taskType = '';
foreach(explode(',', $task->type) as $type) $testType .= zget($lang->testtask->typeList, $type);

$mailto = '';
if($task->mailto)
{
    foreach(explode(',', str_replace(' ', '', $task->mailto)) as $account) $mailto .= zget($users, $account, $account);
}

$actionList  = array();
$actionCodes = explode(',', $config->testtask->actions->view);
foreach($actionCodes as $actionCode)
{
    $actionConfig = $config->testtask->actionList[$actionCode];
    if(!empty($actionConfig['url']['module']) && !empty($actionConfig['url']['method']))
    {
        $moduleName = $actionConfig['url']['module'];
        $methodName = $actionConfig['url']['method'];

        if(!$this->testtask->isClickable($task, $actionCode)) continue;

        $params = !empty($actionConfig['url']['params']) ? $actionConfig['url']['params'] : array();

        preg_match_all("/\{(.+?)\}/i", $params, $vars);
        foreach($vars[0] as $key => $var)
        {
            $realVar = $vars[1][$key];
            $params = str_replace($var, (string)$task->$realVar, $params);
        }

        $actionConfig['url'] = createLink($moduleName, $methodName, $params);
    }

    $actionList[$actionCode] = $actionConfig;
}

detailBody
(
    sectionList
    (
        section
        (
            set::title($lang->testtask->desc),
            set::content($task->desc ? $task->desc : $lang->noData),
            set::useHtml(true)
        ),
        history(),
        center
        (
            floatToolbar
            (
                set::prefix
                (
                    array(array('icon' => 'back', 'text' => $lang->goback))
                ),
                set::main($actionList),
                set::suffix
                (
                    array
                    (
                        array('icon' => 'edit',  'url' => $this->createLink('testtask', 'edit',   "taskID={$testtask->id}")),
                        array('icon' => 'trash', 'url' => $this->createLink('testtask', 'delete', "taskID={$testtask->id}")),
                    )
                )
            )
        )
    ),
    detailSide
    (
        panel
        (
            set::title($lang->testtask->legendBasicInfo),
            tableData
            (
                !empty($execution->multiple) ? item
                (
                    set::name($lang->testtask->execution),
                    $isInModal ? $task->executionName : a
                    (
                        set('href', createLink('execution', 'story', "executionID=$task->execution")),
                        set('title', $task->executionName),
                        $task->executionName,
                    ),
                ) : null,
                item
                (
                    set::name($lang->testtask->build),
                    item(set($taskBuild)),
                ),
                item
                (
                    set::name($lang->testtask->type),
                    $taskType
                ),
                item
                (
                    set::name($lang->testtask->owner),
                    zget($users, $task->owner)
                ),
                item
                (
                    set::name($lang->testtask->mailto),
                    $mailto 
                ),
                item
                (
                    set::name($lang->testtask->pri),
                    priLabel(zget($lang->testtask->priList, $task->pri))
                ),
                item
                (
                    set::name($lang->testtask->begin),
                    $task->begin
                ),
                item
                (
                    set::name($lang->testtask->end),
                    $task->end
                ),
                item
                (
                    set::name($lang->testtask->realFinishedDate),
                    !helper::isZeroDate($task->realFinishedDate) ? $task->realFinishedDate : ''
                ),
                item
                (
                    set::name($lang->testtask->status),
                    $this->processStatus('testtask', $task)
                ),
                item
                (
                    set::name($lang->testtask->testreport),
                    !empty($task->testreport) ? a
                    (
                        set('href', createLink('testreport', 'view', "reportID=$task->testreport")),
                        $testreportTitle
                    ) : null,
                )
            )
        )
    )
);

render($isInModal ? 'modalDialog' : 'page');
