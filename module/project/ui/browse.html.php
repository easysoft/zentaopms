<?php
namespace zin;

$cols        = array_values($config->project->browseTable->cols);
$programTree = $this->project->getProgramTree(0, array('projectmodel', 'createManageLink'), 0, 'list');
$usersAvatar = $this->user->getAvatarPairs('');

$data           = [];
$setting        = $this->datatable->getSetting('project');
$waitCount      = 0;
$doingCount     = 0;
$suspendedCount = 0;
$closedCount    = 0;
foreach($projectStats as $project)
{
    if($project->status == 'wait')      $waitCount++;
    if($project->status == 'doing')     $doingCount++;
    if($project->status == 'suspended') $suspendedCount++;
    if($project->status == 'closed')    $closedCount++;

    $item = new stdClass();
    foreach($setting as $value) $this->project->printCellZin($value, $project, $users, $item, $programID);

    $item->PMAvatar = $usersAvatar[$item->PM];
    $item->PM       = zget($users, $item->PM);

    $data[] = $item;
}

$programMenuLink = createLink
(
    $this->app->rawModule,
    $this->app->rawMethod,
    [
        'programID'  => '%d',
        'browseType' => $browseType,
        'param'      => $param,
        'orderBy'    => $orderBy,
        'recTotal'   => $recTotal,
        'recPerPage' => $recPerPage,
        'pageID'     => $pageID
    ]
);

$featureBarItemLink = createLink($this->app->rawModule, $this->app->rawMethod, array
(
    'programID'  => $programID,
    'browseType' => '{key}',
    'param'      => $param,
    'orderBy'    => $orderBy,
    'recTotal'   => $pager->recTotal,
    'recPerPage' => $recPerPage,
    'pageID'     => $pageID
));

$summary = $browseType == 'all'
    ? sprintf($lang->project->allSummary, count($projectStats), $waitCount, $doingCount, $suspendedCount, $closedCount)
    : sprintf($lang->project->summary, count($projectStats));
$summary = str_replace('<strong>', '', str_replace('</strong>', '', $summary));

jsVar('langPostponed', $this->lang->project->statusList['delay']);
jsVar('langManDay', $this->lang->project->manDay);

featureBar
(
    to::before
    (
        programMenu
        (
            setStyle(array('margin-right' => '20px')),
            set
            (
                [
                    'title'       => $lang->program->all,
                    'programs'    => $programTree,
                    'activeKey'   => !empty($programs) ? $programID : null,
                    'closeLink'   => sprintf($programMenuLink, 0),
                    'onClickItem' => jsRaw("function(data){window.programMenuOnClick(data, '$programMenuLink');}")
                ]
            )
        )
    ),
    set::link($featureBarItemLink),
    set::moreMenuLinkCallback(fn($key) => str_replace('{key}', $key, $featureBarItemLink)),
    hasPriv('project', 'batchEdit')
        ? item
        (
            set::type('checkbox'),
            set::text($lang->project->edit),
            set::checked($this->cookie->showProjectBatchEdit)
        )
        : NULL,
    li(searchToggle(set::open($browseType == 'bysearch')))
);

toolbar
(
    item(set(
    [
        'text'  => $lang->export,
        'icon'  => 'export',
        'class' => 'ghost text-darker',
        'url'   => createLink('project', 'export', $browseType, "status=$browseType&orderBy=$orderBy", 'html'),
    ])),
    item(set(
    [
        'text'  => $lang->project->create,
        'icon'  => 'plus',
        'class' => 'btn primary',
        'url'   => createLink('project', 'create', '')
    ])),
);

jsVar('langSummary', $summary);

dtable
(
    set::cols($cols),
    set::data($data),
    set::footPager(usePager()),
    set::onRenderCell(jsRaw('function(result, data){ return window.renderReleaseCountCell(result, data); }')),
    set::footer(jsRaw('window.footerGenerator'))
);

render();
