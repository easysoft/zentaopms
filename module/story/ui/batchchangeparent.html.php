<?php
declare(strict_types=1);
/**
* The UI file of story module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      sunguangming <sunguangming@easycorp.ltd>
* @package     story
* @link        https://www.zentao.net
*/

namespace zin;

modalHeader(set::title($lang->story->batchChangeParent));
unset($parents[0]);
formPanel
(
    formGroup
    (
        set::label($lang->story->parent),
        set::required(true),
        picker
        (
            set::name('parent'),
            set::items($parents),
            set::required(true)
        )
    ),
);
