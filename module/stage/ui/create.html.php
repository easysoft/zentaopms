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
$percentRow = '';
if(isset($config->setPercent) && $config->setPercent == 1)
{
    $percentRow = formRow(
        formGroup
        (
            set::width('1/2'),
            set::label($lang->stage->percent),
            set::labelClass('required'),
            inputGroup
            (
                inputControl
                (
                    input(set::name('percent')),
                    to::suffix('%'),
                    set::suffixWidth(20),
                ),
            )
        )
    );
}

formPanel
(
    set::title($lang->stage->create),
    set::shadow(false),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->stage->name),
            set::name('name'),
        )
    ),
    $percentRow,
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->stage->type),
            set::name('type'),
            set::items($lang->stage->typeList),
        )
    ),
);
/* ====== Render page ====== */
render('modalDialog');
