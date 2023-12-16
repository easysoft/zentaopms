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
    )
);

$taskType = '';
foreach(explode(',', $task->type) as $type) $taskType .= zget($lang->testtask->typeList, $type);

$mailto = '';
if($task->mailto)
{
    foreach(explode(',', str_replace(' ', '', $task->mailto)) as $account) $mailto .= zget($users, $account, $account);
}

$actions = $this->loadModel('common')->buildOperateMenu($task);
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
        fileList
        (
            set::files($task->files),
            set::padding(false)
        ),
        history(set::objectID($task->id)),
        center
        (
            floatToolbar
            (
                set::object($task),
                isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), set::className('ghost text-white'), $lang->goback)),
                set::main($actions['mainActions']),
                set::suffix($actions['suffixActions'])
            )
        )
    ),
    detailSide
    (
        tabs
        (
            set::collapse(true),
            tabPane
            (
                set::key('basicInfo'),
                set::title($lang->testtask->legendBasicInfo),
                set::active(true),
                tableData
                (
                    !empty($execution->multiple) ? item
                    (
                        set::name($lang->testtask->execution),
                        $isInModal ? $task->executionName : a
                        (
                            set('href', createLink('execution', 'story', "executionID=$task->execution")),
                            set('title', $task->executionName),
                            $task->executionName
                        )
                    ) : null,
                    item
                    (
                        set::name($lang->testtask->build),
                        span
                        (
                            $isInModal ? $buildName :a
                            (
                                set::href(createLink('build', 'view', "buildID=$task->build")),
                                $buildName
                            )
                        )
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
                        priLabel($task->pri, set::text($lang->testtask->priList))
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
                            zget($testreport, 'title', '')
                        ) : null
                    )
                )
            )
        )
    )
);

render($isInModal ? 'modalDialog' : 'page');
