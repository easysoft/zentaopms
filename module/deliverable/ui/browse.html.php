<?php
namespace zin;

featureBar();

toolbar
(
    hasPriv('deliverable', 'create') ? item(set(array
    (
        'icon'     => 'plus',
        'class'    => 'primary',
        'text'     => $lang->deliverable->create,
        'data-app' => $app->tab,
        'url'      => createLink('deliverable', 'create')
    ))) : null,
);

$cols = $config->deliverable->dtable->fieldList;
$modelList = $this->deliverable->buildModelList('all');

$summary   = sprintf($lang->deliverable->summary, count($deliverables));
$tableData = initTableData($deliverables, $cols, $this->deliverable);
$data      = array();
foreach($tableData as $key => $row)
{
    if($row->model)
    {
        $models = explode(',', $row->model);
        foreach($models as $model)
        {
            $row->model   = $modelList[$model];
            $row->rowspan = count($models);
            $data[] = clone $row;
        }
    }
    else
    {
        $data[] = $row;
    }
}

dtable
(
    set::cols($cols),
    set::data($data),
    set::userMap($users),
    set::orderBy($orderBy),
    set::plugins(array('cellspan')),
    set::checkable(false),
    set::sortLink(createLink('deliverable', 'browse', "browseType=$browseType&param={$param}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::getCellSpan(jsRaw('window.getCellSpan')),
    set::footer(array($summary, 'flex', 'pager')),
    set::footPager(usePager(array
    (
        'recTotal'    => $pager->recTotal,
        'recPerPage'  => $pager->recPerPage,
        'linkCreator' => helper::createLink('deliverable', 'browse', "browseType=$browseType&param={$param}&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={recPerPage}&page={page}")
    )))
);
