<?php
declare(strict_types=1);
/**
* The UI file of productplan module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      zhouxin <zhouxin@easycorp.ltd>
* @package     productplan
* @link        https://www.zentao.net
*/

namespace zin;

modalHeader();

formPanel
(
    set::submitBtnText($lang->productplan->closeAB),
    formGroup
    (
        set::width('1/3'),
        set::label($lang->productplan->closedReason),
        set::required(true),
        select
        (
            setID('closedReasonBox'),
            set::name('closedReason'),
            set::strong(false),
            set::items($lang->productplan->closedReasonList),
            set::required(true)
        )
    ),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control('editor'),
        set::rows(6)
    )
);
hr();
history();

render();
