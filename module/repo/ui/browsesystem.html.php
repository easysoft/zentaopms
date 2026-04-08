<?php
declare(strict_types=1);
/**
 * The browsesystem view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;
featureBar
(
    div(searchToggle(set::module('systemSearch'), set::open($type == 'bySearch')))
);

$config->repo->dtable->system->fieldList['latestRelease']['map'] = $releases;
$config->repo->dtable->system->fieldList['product']['map']       = $products;
if(hasPriv('system', 'view')) $config->repo->dtable->system->fieldList['name']['link'] = createLink('system', 'view', "id={id}&spaceID={$spaceID}") . '#app=devops';
$tableData = initTableData($appList, $config->repo->dtable->system->fieldList);

dtable
(
    set::cols($config->repo->dtable->system->fieldList),
    set::data($tableData),
    set::sortLink(createLink('repo', 'browsesystem', "space={$spaceID}&type={$type}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&param={$param}")),
    set::orderBy($orderBy),
    set::footPager(usePager())
);
