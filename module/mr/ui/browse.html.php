<?php
declare(strict_types=1);
/**
 * The browse view file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang <zenggang@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('repoID', $repo->id);

dropmenu(set::objectID($repo->id), set::text($repo->name), set::tab('repo'));

$MRs = initTableData($MRList, $config->mr->dtable->fieldList, $this->mr);

featureBar
(
    set::current($mode != 'status' ? $mode : $param),
    set::linkParams("repoID={$repo->id}&mode=status&param={key}")
);

$linkParams = $app->tab == 'devops' ? "repoID={$repo->id}" : '';
toolBar
(
    hasPriv($app->rawModule, 'create') ? item(set(array
    (
        'text'  => $lang->mr->create,
        'icon'  => 'plus',
        'class' => 'btn primary',
        'url'   => createLink($app->rawModule, 'create', $linkParams)
    ))) : null
);

dtable
(
    set::userMap($users),
    set::cols($config->mr->dtable->fieldList),
    set::data($MRs),
    set::sortLink(createLink($app->rawModule, 'browse', "repoID={$repo->id}&mode={$mode}&param={$param}&objectID={$objectID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::orderBy($orderBy),
    set::footPager(usePager())
);

render();

