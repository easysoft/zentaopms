<?php
declare(strict_types=1);
namespace zin;

$module = $app->tab == 'devops' ? 'repo' : $app->tab;
dropmenu
(
    set::module($module),
    set::tab($module),
    set::url(createLink($module, 'ajaxGetDropMenu', "objectID=0&module={$app->rawModule}&method={$app->rawMethod}"))
);

featureBar(backBtn(set::icon('back'), $lang->goback));

$logList = initTableData($logs, $config->repo->dtable->logWebhook->fieldList);
$urlParams = array(
    'repoID'     => $repo->id,
    'webhookID'  => $webhookID,
    'orderBy'    => '{name}_{sortType}',
    'recPerPage' => $pager->recPerPage,
    'pageID'     => $pager->pageID
);

dtable
(
    set::cols($config->repo->dtable->logWebhook->fieldList),
    set::data($logList),
    set::sortLink(createLink('repo', 'logwebhook', $urlParams)),
    set::orderBy($orderBy),
    set::footPager(usePager())
);
