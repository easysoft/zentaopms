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
dtable
(
    set::cols($cols),
    set::data($deliverables)
);
