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
jsVar('orderBy', $orderBy);
jsVar('sortLink', $sortLink);

foreach($imageList as $image)
{
    $image->hostID = $hostID;
}

$imageList = initTableData($imageList, $config->zahost->imageDtable->fieldList, $this->zahost);

modalHeader(set::title($lang->zahost->image->browseImage));

dtable
(
    set::cols($config->zahost->imageDtable->fieldList),
    set::data($imageList),
    set::sortLink(jsRaw('createSortLink')),
    set::footPager(usePager()),
    set::orderBy($orderBy)
);

render();

