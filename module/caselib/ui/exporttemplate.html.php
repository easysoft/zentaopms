<?php
declare(strict_types=1);
/**
 * The export template view file of caselib module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     caselib
 * @link        https://www.zentao.net
 */
namespace zin;

set::title($lang->testcase->exportTemplate);

formPanel
(
    set::target('_self'),
    on::submit('setDownloading'),
    formGroup
    (
        set::label($lang->caselib->recordNum),
        set::name('num'),
        set::type('number'),
        set::value(10)
    ),
    formGroup
    (
        set::label($lang->caselib->templateType),
        set::name('encode'),
        set::required(true),
        set::value('utf-8'),
        set::control(array('type' => 'picker', 'items' => $config->charsets[$this->cookie->lang]))
    ),
    set::actions(array('submit')),
    set::submitBtnText($lang->export)
);

js
(
    <<<JAVASCRIPT
    function setDownloading()
    {
        if(navigator.userAgent.toLowerCase().indexOf("opera") > -1) return true; // Opera don't support, omit it.

        $.cookie.set('downloading', 0, {expires:config.cookieLife, path:config.webRoot});

        time = setInterval(function()
        {
            if($.cookie.get('downloading') == 1)
            {
                $('.modal .modal-actions .close')[0].click();

                $.cookie.set('downloading', null, {expires:config.cookieLife, path:config.webRoot});

                clearInterval(time);
            }
        }, 300);

        return true;
    }
    JAVASCRIPT
);

render('modalDialog');
