<?php
declare(strict_types=1);

namespace zin;

jsVar('confirmDeleteTip',  $lang->ai->models->confirmDelete);
jsVar('confirmDisableTip', $lang->ai->models->confirmDisable);

detailHeader
(
    set::backUrl($this->createLink('ai', 'models')),
    to::title
    (
        entityLabel
        (
            set(array('entityID' => $model->id, 'text' => $model->name)),
        )
    )
);

$actions = $this->loadModel('common')->buildOperateMenu($model);

detailBody
(
    sectionList
    (
        section
        (
            set::title($lang->ai->models->details),
            tableData
            (
                item
                (
                    set::name($lang->ai->models->type),
                    $lang->ai->models->typeList[$model->type]
                ),
                array_map(function($field) use ($model, $lang)
                {
                    return item
                    (
                        set::name($lang->ai->models->$field),
                        empty($model->$field) ? $lang->ai->models->unconfigured : $model->$field
                    );
                }, $config->ai->vendorList[$model->vendor]['credentials']),
                item
                (
                    set::name($lang->ai->models->proxyType),
                    $lang->ai->models->proxyTypes[empty($model->proxyType) ? '' : $model->proxyType]
                ),
                !empty($model->proxyType) ? item
                (
                    set::name($lang->ai->models->proxyAddr),
                    empty($model->proxyAddr) ? $lang->ai->models->unconfigured : $model->proxyAddr
                ) : null,
                item
                (
                    set::name($lang->ai->models->description),
                    $model->desc
                ),
                item
                (
                    set::name($lang->statusAB),
                    $lang->ai->models->statusList[$model->enabled]
                )
            )
        )
    ),
    floatToolbar
    (
        set::object($model),
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::back('ai-models'), set::icon('back'), setClass('ghost text-white'), $lang->goback)),
        set::main($actions['mainActions']),
        set::suffix($actions['suffixActions'])
    ),
);
