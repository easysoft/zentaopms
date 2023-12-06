<?php
declare(strict_types=1);
/**
 * The create view file of branch module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     branch
 * @link        https://www.zentao.net
 */

namespace zin;

formPanel
(
    set::title(sprintf($lang->branch->create, $lang->product->branchName[$product->type])),
    set::shadow(!isonlybody()),
    formGroup
    (
        set::label(sprintf($lang->branch->name, $lang->product->branchName[$product->type])),
        set::required(true),
        input
        (
            set::name('name')
        )
    ),
    formGroup
    (
        set::label(sprintf($lang->branch->desc, $lang->product->branchName[$product->type])),
        textarea
        (
            set::name('desc'),
            set::rows('5')
        )
    )
);

render();
