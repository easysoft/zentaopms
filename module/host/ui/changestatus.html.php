<?php
declare(strict_types=1);
/**
 * The changestatus view file of host module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     host
 * @link        https://www.zentao.net
 */

namespace zin;

formPanel
(
    set::id('hostChangeStatusForm'),
    set::title($this->lang->host->reason),
    formRow
    (
        formGroup
        (
            set::name('reason'),
            set::label(' '),
            set::control('editor'),
            set::required(true)
        )
    )
);
