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
/* 镜像代码库（$repo->mirror == 1）不允许提交合并请求，按钮置灰禁点，并在左侧展示橙色提醒。 */
$isMirrorRepo = !empty($repo->mirror);
$mirrorRepoTip = isset($lang->ppm->mirrorRepoTip) ? $lang->ppm->mirrorRepoTip : (isset($lang->mr->mirrorRepoTip) ? $lang->mr->mirrorRepoTip : '');

/* 提取嵌套三元：按钮 class 与 url 在镜像态下的取值。 */
$createBtnClass = 'btn primary';
if($isMirrorRepo) $createBtnClass .= ' disabled';
$createBtnUrl = $isMirrorRepo ? 'javascript:;' : createLink($app->rawModule, 'create', $linkParams);

$mirrorRepoAlert = $isMirrorRepo ? div
(
    setClass('alert with-icon mr-3 text-warning flex items-center mb-0'),
    /* 覆盖 .alert 默认 gap:.75rem，压紧惊叹号与文字的间隔。 */
    setStyle(array('--alert-bg' => 'var(--color-warning-50)', 'gap' => '.25rem')),
    h::span(setClass('icon icon-exclamation-sign')),
    h::span($mirrorRepoTip)
) : null;

toolBar
(
    $mirrorRepoAlert,
    hasPriv($app->rawModule, 'create') ? item(set(array
    (
        'text'  => $lang->ppm->create,
        'icon'  => 'plus',
        'class' => $createBtnClass,
        'url'   => $createBtnUrl
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
