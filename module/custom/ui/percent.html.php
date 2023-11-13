<?php
declare(strict_types=1);
/**
 * The percent view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     custom
 * @link        https://www.zentao.net
 */
namespace zin;
formPanel
(
    set::id('percentForm'),
    set::actions(array('submit')),
    formRow
    (
        setClass('percent-box'),
        formGroup
        (
            set::label($lang->custom->setPercent),
            radioList
            (
                set::name('percent'),
                set::items($lang->custom->conceptOptions->URAndSR),
                set::value(isset($config->setPercent) ? $config->setPercent : 0),
                set::inline(true)
            )
        )
    ),
    formRow
    (
        setClass('percent-tip'),
        formGroup
        (
            set::label(''),
            span
            (
                icon('info text-warning mr-2'),
                $lang->custom->notice->readOnlyOfPercent
            )
        )
    )
);

/* ====== Render page ====== */
render();
