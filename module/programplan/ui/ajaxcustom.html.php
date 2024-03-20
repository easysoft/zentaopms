<?php
declare(strict_types=1);
/**
 * The ajaxCustom view file of programplan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     programplan
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    setID('ajaxCustomForm'),
    set::title($lang->programplan->settingGantt),
    formGroup
    (
        set::label($lang->execution->gantt->format),
        radioList
        (
            set::name('zooming'),
            set::items($lang->execution->gantt->zooming),
            set::value($zooming ? $zooming : 'day'),
            set::inline(true)
        )
    ),
    formGroup
    (
        set::label($lang->programplan->viewSetting),
        checkList
        (
            set::name('stageCustom'),
            set::items($lang->programplan->stageCustom),
            set::value($stageCustom),
            set::inline(true)
        )
    ),
    formGroup
    (
        setClass('customField'),
        set::label($lang->customField),
        checkList
        (
            set::name('ganttFields'),
            set::items($customFields),
            set::value($showFields),
            set::inline(true)
        )
    )
);
