<?php
declare(strict_types=1);
/**
 * The edit view file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     group
 * @link        https://www.zentao.net
 */

namespace zin;

modalHeader(set::title($lang->group->edit));
formPanel
(
    formGroup
    (
        set::label($lang->group->name),
        set::required(true),
        input
        (
            set::name('name'),
            set::value($group->name)
        )
    ),
    formGroup
    (
        set::label($lang->group->desc),
        textarea
        (
            set::name('desc'),
            set::value($group->desc),
            set::rows('5')
        )
    )
);
