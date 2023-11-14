<?php
declare(strict_types=1);
/**
 * The displaysetting view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->doc->displaySetting));
formPanel
(
    formGroup
    (
        set::label($lang->doc->showDoc),
        checkList
        (
            set::type('radio'),
            set::inline(true),
            set::name('showDoc'),
            set::items($lang->doc->showDocList),
            set::value($showDoc)
        )
    )
);
