<?php
declare(strict_types=1);
/**
 * The browse view file of pipeline module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     pipeline
 * @link        https://www.zentao.net
 */

namespace zin;
global $app;
$app->loadLang('runner');

jsVar('repoID', $repoID);
jsVar('type', $type);

/* 空间级别或镜像代码库的流水线需显示服务器列。 */
$needServer = !$repoID || !empty($repo->mirror);

if($repoID)
{
    dropmenu(set::objectID($repoID), set::text($repo->name), set::tab('repo'));
    unset($lang->pipeline->featureBar['browse']);
    unset($config->pipeline->dtable->fieldList['repo']);
    if(!empty($repo->mirror))
    {
        unset($config->pipeline->dtable->fieldList['status']);
    }
    featureBar(div(searchToggle(set::module('pipeline'), set::open($type == 'bySearch'))));
}
else
{
    /* zin: Define the set::module('pipeline') feature bar on main menu. */
    featureBar
    (
        set::current($type),
        set::link($this->createLink('pipeline', 'browse', "spaceID={$spaceID}&repoID={$repoID}&type={key}")),
        div(searchToggle(set::module('pipeline'), set::open($type == 'bySearch')))
    );
}
if($type == 'space') unset($config->pipeline->dtable->fieldList['repo']);

if($needServer) $config->pipeline->dtable->fieldList['server']['show'] = true;

/* zin: Define the toolbar on main menu. */
$isMirror      = $repoID && !empty($repo->mirror);
$canCreate     = hasPriv('pipeline', 'create');
$runnerPriv    = hasPriv('runner', 'browse');
$executionPriv = hasPriv('pipeline', 'execution');

$createItem    = array('text' => $lang->pipeline->createBtn, 'url' => inLink('create', "spaceID={$spaceID}&repoID={$repoID}"), 'class' => 'primary', 'icon' => 'plus', 'data-toggle' => 'modal');
$importItem    = array('text' => $lang->pipeline->importBtn, 'url' => inLink('import', "repoID={$repoID}"), 'class' => 'primary', 'icon' => 'import');
//$runnerItem    = array('text' => $lang->runner->manageRunner, 'url' => createLink('runner', 'browse'), 'class' => 'primary');
$executionItem = array('text' => $lang->pipeline->execution,  'url' => inLink('execution', "spaceID={$spaceID}&repoID={$repoID}&type={$type}"), 'class' => 'primary');
$config->pipeline->dtable->fieldList['actions']['list']['arrange'] = array('icon' => 'pencil-alt', 'text' => $lang->pipeline->arrange, 'hint' => $lang->pipeline->arrange, 'url' => helper::createLink('pipeline', 'arrange', "id={id}&spaceID={$spaceID}&repoID={$repoID}&type={$type}"));
$config->pipeline->dtable->fieldList['actions']['list']['edit']    = array('icon' => 'edit', 'text' => $lang->pipeline->edit, 'hint' => $lang->pipeline->edit, 'url' => helper::createLink('pipeline', 'edit', "id={id}"));
$config->pipeline->dtable->fieldList['actions']['list']['exec']['url'] = array('module' => 'pipeline', 'method' => 'exec', 'params' => "pipelineID={id}&space={$spaceID}&repoID={$repoID}&type={$type}");
if($needServer)
{
    $config->pipeline->dtable->fieldList['actions']['menu'] = array('exec', 'execution', 'arrange', 'edit', 'delete');
}

$cols = $this->loadModel('datatable')->getSetting('pipeline');
$tableData = initTableData($pipelineList, $cols, $this->pipeline);
toolbar
(
    $executionPriv ? item(set($executionItem)) : null,
    //$runnerPriv    ? item(set($runnerItem)) : null,
    $isMirror && $canCreate      ? item(set($importItem)) : null,
    !$isMirror && $canCreate     ? item(set($createItem)) : null
);

jsVar('confirmDelete', $lang->pipeline->confirmDelete);

dtable
(
    set::customCols(true),
    set::cols($cols),
    set::data($tableData),
    set::userMap($users),
    set::sortLink(createLink('pipeline', 'browse', "space={$spaceID}&repoID={$repoID}&type={$type}&queryID={$queryID}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::orderBy($orderBy),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager())
);
