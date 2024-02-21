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

if(empty($models))
{
    panel
    (
        center
        (
            set::style(array('height' => 'calc(100vh - 145px)')), // 145px is sum of header and footer height.
            div
            (
                span
                (
                    set::className('p-8 text-gray'),
                    $lang->ai->models->noModels
                ),
                btn
                (
                    set::text($lang->ai->models->create),
                    set::icon('plus'),
                    set::className('btn secondary'),
                    set::url(createLink('ai', 'modelcreate'))
                )
            )
        )
    );
}
else
{
    $models = initTableData($models, $config->ai->dtable->models, $this->ai);

    dtable
    (
        set::cols($config->ai->dtable->models),
        set::data($models),
        set::orderBy($orderBy),
        set::sortLink(inlink('models', "orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
        set::footPager(usePager()),
    );
}
