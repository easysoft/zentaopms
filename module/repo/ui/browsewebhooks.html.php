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

featureBar();

$createWebhookItem = array('text' => $lang->repo->createWebhook, 'url' => createLink('repo', 'createWebhook', "repoID={$repo->id}"));
toolBar
(
    hasPriv('repo', 'createWebhook') ? item(set($createWebhookItem + array('icon' => 'plus', 'class' => 'btn primary')), set('data-app', 'devops')) : null
);

$webhookList = initTableData($webhooks, $config->repo->dtable->webhook->fieldList);
$urlParams = array(
    'repoID'     => $repo->id,
    'orderBy'    => '{name}_{sortType}',
    'recPerPage' => $pager->recPerPage,
    'pageID'     => $pager->pageID
);

if(!empty($webhookList))
{
    foreach($webhookList as $webhook)
    {
        if(empty($webhook->actions)) continue;
        foreach($webhook->actions as $key => $action)
        {
            if($webhook->enabled && $action['name'] == 'enable') $webhook->actions[$key]['name'] = 'disable';
        }
    }
}

$confirm = $lang->repo->webhook->confirmWebhookDelete;
dtable
(
    set::actionItemCreator(jsRaw(<<<JS
        (item, info) => {
            if(item.url)
            {
                if(typeof item.url == 'string') item.url = zui.formatString(item.url, info.row.data);
                else item.url.params = zui.formatString(item.url.params, info.row.data);
            }
            if(item.icon == 'trash')
            {
                const confirm = "$confirm";
                item['data-confirm']['message'] = confirm.replace('%s', info.row.data.name);
            }
            return item;
        }
    JS
    )),
    set::cols($config->repo->dtable->webhook->fieldList),
    set::data($webhookList),
    set::sortLink(createLink('repo', 'browsewebhooks', $urlParams)),
    set::orderBy($orderBy),
    set::footPager(usePager())
);
