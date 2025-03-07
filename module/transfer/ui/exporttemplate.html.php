<?php
declare(strict_types=1);
/**
 * The export view file of transfer module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     transfer
 * @link        https://www.zentao.net
 */
namespace zin;

$this->app->loadLang('file');

set::title($lang->transfer->exportTemplate);

form
(
    set::target('_self'),
    on::submit('setDownloading'),
    formGroup
    (
        on::click('removeNumTip'),
        set::label($lang->transfer->num),
        set::name('num'),
        set::type('number'),
        set::value(10)
    ),
    !empty($isProjectStory) ? formGroup
    (
        set::label($lang->story->type),
        set::name('storyType'),
        set::required(true),
        set::control('picker'),
        set::value('story'),
        set::items($typeList)
    ) : null,
    formGroup
    (
        set::label($lang->file->extension),
        set::name('fileType'),
        set::required(true),
        set::value('xlsx'),
        set::control('picker'),
        set::items(array('xlsx' => 'xlsx', 'xls' => 'xls'))
    ),
    set::actions(array('submit'))
);

$numError = sprintf($this->lang->error->int[0], $this->lang->transfer->numAB);
h::js
(
    <<<JAVASCRIPT
    window.setDownloading = function(e)
    {
        const \$num = $(this).find('[name=num]');
        removeNumTip();
        if(isNaN(\$num.val()) || \$num.val().trim() == '')
        {
            \$num.after('<div class="form-tip ajax-form-tip text-danger pre-line" id="numTip">{$numError}</div>');
            \$num.addClass('has-error');
            e.preventDefault();
            return false;
        }

        if(navigator.userAgent.toLowerCase().indexOf("opera") > -1) return true; // Opera don't support, omit it.

        $.cookie.set('downloading', 0, {expires:config.cookieLife, path:config.webRoot});

        time = setInterval(function()
        {
            if($.cookie.get('downloading') == 1)
            {
                $('.modal-dialog .modal-actions .close').trigger('click');

                $.cookie.set('downloading', null, {expires:config.cookieLife, path:config.webRoot});

                clearInterval(time);
            }
        }, 300);
        return true;
    }

    window.removeNumTip = function()
    {
        const \$num = $(this).find('[name=num]');
        \$num.parent().find('.form-tip').remove();
        \$num.removeClass('has-error');
    }
    JAVASCRIPT
);
