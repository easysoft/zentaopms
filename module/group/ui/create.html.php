<?php
declare(strict_types=1);
/**
 * The create view file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     group
 * @link        https://www.zentao.net
 */

namespace zin;

modalHeader(set::title($lang->group->create));
formPanel
(
    formGroup
    (
        set::label($lang->group->name),
        set::required(true),
        input(set::name('name'))
    ),
    formGroup
    (
        set::label($lang->group->desc),
        textarea
        (
            set::name('desc'),
            set::rows('5')
        )
    ),
    $app->tab != 'project' ? formGroup
    (
        set::label($lang->group->limited),
        setClass('items-center'),
        checkbox
        (
            set::name('limited'),
            set::value(1)
        )
    ) : null
);
