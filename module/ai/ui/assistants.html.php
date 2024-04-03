<?php
declare(strict_types=1);

namespace zin;

jsVar('confirmPublishTip', $lang->ai->assistants->confirmPublishTip);
jsVar('confirmWithdrawTip', $lang->ai->assistants->confirmWithdrawTip);

featureBar();
if(!$hasModalAvailable)
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
                    $lang->ai->assistants->noLlm
                )
            )
        )
    );
}
else
{
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

}
