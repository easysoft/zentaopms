<?php
declare(strict_types=1);
/**
 * The close view file of product module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     product
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader();

formPanel
(
    set::submitBtnText($lang->product->activateAction),
    formGroup
    (
        set::label($lang->comment),
        editor
        (
            set::name('comment'),
            set::rows('6'),
        ),
        input(
            set::className('hidden'),
            set::name('status'),
            set::value('normal')
        )
    )
);
hr();
history();

/* ====== Render page ====== */
render();
