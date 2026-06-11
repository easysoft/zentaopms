<?php
declare(strict_types=1);
/**
 * Create view of program plan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chen.tao <chentao@easycorp.ltd>
 * @package     programplan
 * @link        https://www.zentao.net
 */

namespace zin;

formPanel
(
    set::title($lang->programplan->editVersion),
    set::actions(array('submit')),
    formGroup
    (
        set::label($lang->programplan->version),
        set::name('version'),
        set::value($version->version),
        set::required(true)
    ),
    formGroup
    (
        set::label($lang->programplan->desc),
        textarea(set::name('items'), set::rows(3), set::value($version->items))
    )
);
