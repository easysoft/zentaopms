<?php
declare(strict_types=1);
/**
 * The yyy view file of xxx module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     xxx
 * @link        https://www.zentao.net
 */
namespace zin;

panel
(
    setID('tipsModal'),
    set::title($lang->execution->tips),
    set::headingActions(array
    (
        array('url' => createLink('execution', 'task', "executionID={$executionID}"), 'icon' => 'close', 'class' => 'ghost')
    )),
    setClass('m-auto'),
    div
    (
        set::className('flex items-center mt-2'),
        icon('check-circle text-success icon-2x mr-2'),
        span
        (
            set::className('text-md font-bold tip-title'),
            $lang->execution->afterInfo
        )
    ),
    div
    (
        setClass('mt-5 mb-5'),
        btn
        (
            set::className('mr-2 tipBtn ml-1'),
            $lang->execution->setTeam,
            set('data-url', createLink('execution', 'team', "executionID={$executionID}"))
        ),
        $execution->lifetime != 'ops' ? btn
        (
            set::className('mr-2 tipBtn'),
            $lang->execution->linkStory,
            set('data-url', createLink('execution', 'linkstory', "executionID=$executionID"))
        ) : null,
        btn
        (
            set::className('mr-2 tipBtn'),
            $lang->execution->createTask,
            set('data-url', createLink('task', 'create', "execution=$executionID"))
        ),
        btn
        (
            set::className('mr-2 tipBtn'),
            $lang->execution->goback,
            set('data-url', createLink('execution', 'task', "executionID={$executionID}"))
        ),
        btn
        (
            set::className('tipBtn'),
            $lang->execution->gobackExecution,
            set('data-url', createLink('execution', 'all'))
        )
    )
);

/* ====== Render page ====== */
render();
