<?php
declare(strict_types=1);

namespace zin;

$defaultModelType   = array_key_first($lang->ai->models->typeList);
$defaultModelVendor = array_key_first($lang->ai->models->vendorList->{$defaultModelType});
$allCredFields      = array_unique(array_reduce((array)$config->ai->vendorList, function($carry, $vendor) {return array_merge($carry, array_values((array)$vendor['credentials']));}, array()));

jsVar('window.vendorList',     $config->ai->vendorList);
jsVar('window.vendorListLang', $lang->ai->models->vendorList);
jsVar('window.vendorTipsLang', $lang->ai->models->vendorTips);

formPanel
(
    set::title($lang->ai->models->edit . (empty($model->name) ? '' : " - {$model->name}")),
    set::id('model-form'),
    set::actions(array('submit', array('text' => $lang->ai->models->testConnection, 'id' => 'test-conn-btn', 'class' => 'btn secondary', 'url' => 'javascript:testConnection()'), 'cancel')),
    formGroup
    (
        set::label($lang->ai->models->name),
        set::width('1/2'),
        input
        (
            set::name('name'),
            set::value($model->name)
        )
    ),
    formGroup
    (
        set::label($lang->ai->models->type),
        set::width('1/2'),
        select
        (
            set::name('type'),
            set::items($lang->ai->models->typeList),
            set::value($model->type),
            set::required(true)
        )
    ),
    formRow
    (
        formGroup
        (
            /* Update vendor group on model type change. */
            set::label($lang->ai->models->vendor),
            set::width('1/2'),
            select
            (
                set::name('vendor'),
                set::items($lang->ai->models->vendorList->{$defaultModelType}),
                set::value($model->vendor),
                set::required(true)
            ),
            set::tip(' '),
            set::tipClass('vendor-tips text-gray')
        )
    ),
    array_map( // Vendor credentials rows.
        function($field) use ($lang, $model)
        {
            return formRow
            (
                set::className('vendor-row'),
                formGroup
                (
                    set::label($lang->ai->models->{$field}),
                    input
                    (
                        set::name($field),
                        set::value($model->{$field}),
                        set::required(true)
                    )
                )
            );
        },
        $allCredFields
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->ai->models->proxyType),
            set::width('1/2'),
            select
            (
                set::name('proxyType'),
                set::items($lang->ai->models->proxyTypes),
                set::value($model->proxyType),
                set::required(true)
            )
        ),
        formGroup
        (
            set::label($lang->ai->models->proxyAddr),
            set::width('1/2'),
            set::style(array('display' => 'none')), // Hide proxy address input by default.
            set::id('proxy-addr-container'),
            input
            (
                set::name('proxyAddr'),
                set::value($model->proxyAddr)
            )
        )
    ),
    formGroup
    (
        set::label($lang->ai->models->description),
        textarea
        (
            set::name('description'),
            set::rows(3),
            set::value($model->desc)
        )
    )
);
