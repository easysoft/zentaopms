<?php
declare(strict_types=1);
/**
 * The browse view file of ppm module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang <zenggang@easycorp.ltd>
 * @package     ppm
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('repoID', $repo->id);

dropmenu(set::objectID($repo->id), set::text($repo->name), set::tab('repo'));

$ppms = initTableData($ppmList, $config->ppm->dtable->fieldList, $this->ppm);

featureBar
(
    set::current($mode != 'status' ? $mode : $param),
    set::linkParams("repoID={$repo->id}&mode=status&param={key}")
);

$linkParams = $app->tab == 'devops' ? "repoID={$repo->id}" : '';
/* 镜像代码库（$repo->mirror == 1）不允许提交合并请求，按钮置灰禁点。 */
$isMirrorRepo = !empty($repo->mirror);
toolBar
(
    hasPriv($app->rawModule, 'create') ? item(set(array
    (
        'text'  => $lang->ppm->create,
        'icon'  => 'plus',
        'class' => 'btn primary' . ($isMirrorRepo ? ' disabled' : ''),
        'url'   => $isMirrorRepo ? 'javascript:;' : createLink($app->rawModule, 'create', $linkParams)
    ))) : null
);

dtable
(
    set::userMap($users),
    set::cols($config->ppm->dtable->fieldList),
    set::data($ppms),
    set::sortLink(createLink($app->rawModule, 'browse', "repoID={$repo->id}&mode={$mode}&param={$param}&objectID={$objectID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::orderBy($orderBy),
    set::footPager(usePager())
);
