<?php
declare(strict_types=1);
/**
 * The struct view file of api module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming <sunguangming@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

$structs = initTableData($structs, $config->api->dtable->struct->fieldList, $this->api);
$cols = array_values($config->api->dtable->struct->fieldList);
$data = array_values($structs);

div
(
    dtable
    (
        set::_className(''),
        set::cols($cols),
        set::data($data),
        set::userMap($users),
        set::footPager(usePager()),
        set::loadPartial(),
        set::loadOptions(array('zui-command' => 'updateLazyContent', 'data-lazy-target' => '#table-api-struct'))
    ),
);
