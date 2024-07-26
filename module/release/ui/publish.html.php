<?php
declare(strict_types=1);
/**
 * The publish view file of release module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     release
 * @link        https://www.zentao.net
 */
namespace zin;
modalHeader();

formPanel
(
    set::actions(array('submit')),
    formRow
    (
        formGroup
        (
            set::label($lang->release->common . $lang->release->status),
            set::control('radioListInline'),
            set::name('status'),
            set::value('normal'),
            set::items($lang->release->resultList)
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->comment),
        )
    )
);
