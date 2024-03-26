<?php
declare(strict_types=1);

namespace zin;

jsVar('confirmPublishTip', $lang->ai->assistants->confirmPublishTip);
jsVar('confirmWithdrawTip', $lang->ai->assistants->confirmWithdrawTip);

detailHeader
(
    set::backUrl($this->createLink('ai', 'assistants')),
    to::title
    (
        entityLabel
        (
            set(array('entityID' => $assistant->id, 'text' => $assistant->name)),
        )
    )
);

$actions = $this->loadModel('common')->buildOperateMenu($assistant);

detailBody
(
    sectionList
    (
        section
        (
            set::title($lang->ai->assistants->details),
            tableData
            (
                item
                (
                    set::name($lang->ai->assistants->name),
                    $assistant->name
                ),
                item
                (
                    set::name($lang->ai->assistants->refModel),
                    $model->name
                ),
                item
                (
                    set::name($lang->ai->assistants->desc),
                    $assistant->desc
                ),
                item
                (
                    set::name($lang->ai->assistants->systemMessage),
                    $assistant->systemMessage
                ),
                item
                (
                    set::name($lang->ai->assistants->greetings),
                    $assistant->greetings
                ),
                item
                (
                    set::name($lang->statusAB),
                    $lang->ai->assistants->statusList[$assistant->enabled]
                ),
            )
        )
    ),
    floatToolbar
    (
        set::object($assistant),
        isAjaxRequest('assistant') ? null : to::prefix(backBtn(set::back('ai-assistants'), set::icon('back'), setClass('ghost text-white'), $lang->goback)),
        set::main($actions['mainActions']),
        set::suffix($actions['suffixActions'])
    ),
);
