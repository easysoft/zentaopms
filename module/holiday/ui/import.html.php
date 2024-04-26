<?php
declare(strict_types=1);
/**
 * The import view file of holiday module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     holiday
 * @link        https://www.zentao.net
 */
namespace zin;

set::title($lang->holiday->importAction);
set::condensed();

form
(
    dtable
    (
        set::cols($this->config->holiday->dtable->import->fieldList),
        set::data($holidays),
        set::height('auto')
    ),
    h::formHidden('submit', '')
);

render();
