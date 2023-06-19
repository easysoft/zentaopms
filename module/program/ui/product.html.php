<?php
namespace zin;

featureBar
(
    set::current($browseType),
    set::linkParams("programID={$programID}&browseType={key}&orderBy=$orderBy"),
);

toolbar
(
    item(set
    (array(
        'text' => $lang->product->create,
        'icon' => 'plus',
        'class'=> 'btn primary',
        'url'  => $this->createLink('product', 'create', "programID={$programID}")
    ))),
);

$cols = $this->config->product->dtable->fieldList;

$data = array();
$products = initTableData($products, $cols, $this->product);
foreach($products as $product) $data[] = $this->product->formatDataForList($product, $users, $usersAvatar);

$summary = sprintf($lang->product->pageSummary, count($data));
$footToolbar = common::hasPriv('product', 'batchEdit') ? array('items' => array(array('text' => $lang->edit, 'class' => 'btn batch-btn size-sm primary', 'data-url' => $this->createLink('product', 'batchEdit', "programID={$programID}")))) : null;

dtable
(
    set::userMap($users),
    set::cols($cols),
    set::data($data),
    set::nested(false),
    //set::onRenderCell(jsRaw('window.renderCell')),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
    set::footer(array('checkbox', 'toolbar', array('html' => $summary, 'className' => "text-dark"), 'flex', 'pager')),
);

render();
