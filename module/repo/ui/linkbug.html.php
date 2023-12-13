<?php
declare(strict_types=1);
/**
 * The linkbug file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao <zhaoke@easycorp.ltd>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('orderBy',  $orderBy);
jsVar('sortLink', createLink('repo', 'linkBug', "repoID=$repoID&revision=$revision&browseType=$browseType&param=$param&orderBy={orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"));

detailHeader
(
    to::prefix(''),
    to::title
    (
        $lang->productplan->linkBug
    )
);

$footToolbar = array('items' => array
(
    array('text' => $lang->productplan->linkBug, 'className' => 'batch-btn-repo ajax-btn', 'data-url' => helper::createLink('repo', 'linkBug', "repoID=$repoID&revision=$revision&browseType=$browseType&param=$param&orderBy=$orderBy"))
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary', 'data-type' => 'bugs'));

searchForm
(
    set::module('bug'),
    set::simple(true),
    set::show(true)
);

div
(
    set('class', 'repo-linkstory-title'),
    icon('unlink'),
    span
    (
        set('class', 'font-semibold ml-2'),
        $lang->productplan->unlinkedBugs . "({$pager->recTotal})"
    )
);
$cols = array();
foreach($config->release->dtable->defaultFields['linkBug'] as $field) $cols[$field] = zget($config->bug->dtable->fieldList, $field, array());
$cols['title']['data-toggle'] = '';
$cols['title']['link']        = array('module' => 'bug', 'method' => 'view', 'params' => 'bugID={id}', 'target' => '_blank');

$allBugs = initTableData($allBugs, $cols);
$data    = array_values($allBugs);
dtable
(
    set::userMap($users),
    set::data($data),
    set::cols($cols),
    set::checkable(true),
    set::footToolbar($footToolbar),
    set::sortLink(jsRaw('createSortLink')),
    set::footPager(usePager())
);

render();
