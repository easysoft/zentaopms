<?php
declare(strict_types=1);
/**
 * The notify view file of release module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     release
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::id('notifyForm'),
    set::title($release->name . ' - ' . $lang->release->notify),
    set::shadow(false),
    set::actions(array('submit')),
    set::submitBtnText($lang->release->notify),
    formRow
    (
        formGroup
        (
            setclass('user-title'),
            $lang->release->notifyUsers
        )
    ),
    formRow
    (
        formGroup
        (
            checkList
            (
                setClass('flex-wrap'),
                set::name('notify[]'),
                set::value('FB'),
                set::items($lang->release->notifyList),
                set::inline(true)
            )
        )
    )
);
hr();
history();

/* ====== Render page ====== */
render();
