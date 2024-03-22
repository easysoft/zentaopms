<?php
declare(strict_types=1);

namespace zin;

featureBar();
toolbar(
    item(
        set(array(
            'text' => $lang->ai->assistants->create,
            'url' => createLink('ai', 'assistantcreate'),
            'data-app' => $app->tab,
            'icon'  => 'plus',
            'class' => 'btn primary',
        ))
    )
);

$assistants = initTableData($assistants, $config->ai->dtable->assistants, $this->ai);

dtable
(
    set::cols($config->ai->dtable->assistants),
    set::data($assistants),
    set::orderBy($orderBy),
    set::sortLink(inlink('assistants', "orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::footPager(usePager()),
);

