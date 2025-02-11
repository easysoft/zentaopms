<?php
declare(strict_types=1);
/**
 * The programTitle view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu<hufangzhou@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */

namespace zin;

global $lang;

set::title($lang->project->moduleSetting);
form
(
    formGroup
    (
        set::label($lang->project->moduleOpen),
        radiolist
        (
            set::name('programTitle'),
            set::inline(true),
            set::items($lang->project->programTitle),
            set::value($status)
        )
    ),
    set::actions(array('submit'))
);
