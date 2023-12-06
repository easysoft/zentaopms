<?php
declare(strict_types=1);
/**
 * The browseImage view file of host module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     host
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('hostID', $hostID);

$tableData = initTableData($imageList, $config->host->imageDtable->fieldList);

dtable
(
    set::cols(array_values($config->host->imageDtable->fieldList)),
    set::data($tableData),
    set::sortLink(createLink('host', 'browseImage', "hostID={$hostID}&browseType=$browseType&param=$param&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::orderBy($orderBy),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager())
);

render();
