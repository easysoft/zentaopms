<?php
declare(strict_types=1);
/**
* The product release block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Wangyuting <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

foreach($releases as $release)
{
    $release->status = zget($lang->release->statusList, $release->status);
}

panel
(
    dtable
    (
        set::width('100%'),
        set::height('auto'),
        set::cols(array_values($config->block->dtable->release->fieldList)),
        set::data(array_values($releases))
    )
);

render();
