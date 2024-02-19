<?php
declare(strict_types=1);

namespace zin;

featureBar();
toolbar(
    item(
        set(array(
            'text' => $lang->ai->models->create,
            'url' => createLink('ai', 'modelcreate'),
            'data-app' => $app->tab,
            'icon'  => 'plus',
            'class' => 'btn primary',
        ))
    )
);

$models = initTableData($models, $config->ai->dtable->models, $this->ai);

dtable
(
    set::cols($config->ai->dtable->models),
    set::data($models),
    set::orderBy($orderBy),
    set::sortLink(inlink('models', "orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::footPager(usePager()),
);
