<?php
declare(strict_types=1);
/**
 * The edit view file of caselib module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     caselib
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    formGroup
    (
        set::label($lang->caselib->name),
        set::name('name'),
        set::value($lib->name),
    ),
    formGroup
    (
        set::label($lang->caselib->desc),
        editor
        (
            set::name('desc'),
            set::value(htmlSpecialString($lib->desc)),
            set::rows(10)
        )
    ),
);

render();

