<?php
declare(strict_types=1);
/**
 * The select template view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Xin Zhou<zhouxin@chandao.net>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;
formPanel
(
    set::title($lang->doc->template),
    formGroup
    (
        set::label($lang->doc->selectTemplate),
        set::name('template'),
        set::required(true),
        set::width('5/6'),
        set::items($templatePairs)
    )
);
