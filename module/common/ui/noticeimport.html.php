<?php
declare(strict_types=1);
/**
 * The notice view file of common module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     common
 * @link        https://www.zentao.net
 */
namespace zin;

modalTrigger
(
    modal
    (
        on::click('.cover', "submitForm('cover')"),
        on::click('.insert', "submitForm('insert')"),
        on::click('.close', "enableSubmitButton()"),

        setID('importNoticeModal'),
        set::title($lang->importConfirm),
        set::footerClass('justify-center'),
        to::footer
        (
            btn
            (
                setClass('danger wide cover'),
                zui::width('86px'),
                $lang->importAndCover
            ),
            btn
            (
                setClass('primary wide insert'),
                $lang->importAndInsert
            )
        ),
        div
        (
            setClass('alert'),
            icon('exclamation-sign'),
            $lang->noticeImport
        )
    )
);

$footerActions = array(
    array('class' => 'danger cover', 'text' => $lang->importAndCover),
    array('class' => 'primary insert', 'text' => $lang->importAndInsert)
);

h::js
(
<<<JAVASCRIPT
window.submitForm = function(type)
{
    $('#insert').val(type == 'insert' ? 1 : 0);
    $('#importNoticeModal .modal-footer .btn').addClass('disabled');

    const formUrl  = $("button[data-target='#importNoticeModal']").closest('form').attr('action');
    const formData = new FormData($("button[data-target='#importNoticeModal']").closest('form')[0]);

    $.ajaxSubmit({url: formUrl, data: formData, onFail: function(error)
    {
        $('#importNoticeModal .modal-footer button').removeClass('disabled');
        $('#importNoticeModal').zui('modal').hide();
        if(error?.message) showValidateMessage(error.message);
    }});
}

window.enableSubmitButton = function()
{
    $('#importNoticeModal .modal-footer .btn').removeClass('disabled');
}
JAVASCRIPT
);
