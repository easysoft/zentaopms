<?php
declare(strict_types=1);
/**
 * The block view file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;

to::header
(
    $lang->testtask->blockAction,
    entityLabel
    (
        set::entityID($testtask->id),
        set::level(1),
        set::text($testtask->name),
        set::reverse(true)
    )
);

form
(
    formGroup
    (
        set::label($lang->comment),
        editor
        (
            set::name('comment'),
            set::rows(6)
        )
    ),
    set::actions(array('submit'))
);

history();

render();

