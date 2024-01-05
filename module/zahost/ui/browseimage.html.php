<?php
declare(strict_types=1);
/**
 * The browseimage view file of zahost module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     zahost
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('hostID', $hostID);

foreach($imageList as $image) $image->hostID = $hostID;

$imageList = initTableData($imageList, $config->zahost->imageDtable->fieldList, $this->zahost);

foreach($imageList as &$image)
{
    foreach($image->actions as &$action) $action['disabled'] = false;
}

modalHeader(set::title($lang->zahost->image->browseImage));

dtable
(
    set::cols($config->zahost->imageDtable->fieldList),
    set::data($imageList),
    set::sortLink(createLink('zahost', 'browseImage', "hostID={$hostID}&browseType={$browseType}&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footPager(usePager()),
    set::orderBy($orderBy)
);
