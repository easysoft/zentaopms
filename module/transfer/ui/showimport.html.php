<?php
declare(strict_types=1);
/**
 * The showimport view file of transfer module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     transfer
 * @link        https://www.zentao.net
 */
namespace zin;

$requiredFields = $datas->requiredFields;
$allCount       = $datas->allCount;
$allPager       = $datas->allPager;
$pagerID        = $datas->pagerID;
$isEndPage      = $datas->isEndPage;
$maxImport      = $datas->maxImport;
$dataInsert     = $datas->dataInsert;
$fields         = $datas->fields;
$suhosinInfo    = $datas->suhosinInfo;
$model          = $datas->model;
$datas          = $datas->datas;
$appendFields   = $this->session->appendFields;
$notEmptyRule   = $this->session->notEmptyRule;

jsVar('requiredFields', $requiredFields);
jsVar('allCount', $allCount);

if(!empty($suhosinInfo))
{
    div(setClass('alert secondary'), $suhosinInfo);
}
elseif(empty($maxImport) and $allCount > $this->config->file->maxImport)
{
    panel
    (
        set::title($lang->transfer->import),
        html(sprintf($lang->file->importSummary, $allCount, html::input('maxImport', $config->file->maxImport, "style='width:50px' onkeyup='recomputeTimes'"), ceil($allCount / $config->file->maxImport))),
        btn(setID('import'), setClass('primary'), on::click('setMaxImport'), $lang->import)
    );
}
else
{
    $fnBuildThead = function() use ($fields)
    {
        global $lang;

        $thItems[] = h::th(setClass('w-70px'), $lang->transfer->id);
        foreach($fields as $key => $value)
        {
            if($value['control'] == 'hidden') continue;
            $thItems[] = h::th(setClass("c-{$key}"), setID($key), $value['title']);
        }

        return h::thead(h::tr($thItems));
    };

    $submitText  = $isEndPage ? $lang->save : $lang->file->saveAndNext;
    $isStartPage = $pagerID == 1;

    panel
    (
        set::title($lang->transfer->import),
        set::actions(false),
        form
        (
            h::table
            (
                setID('showData'),
                setClass('table borderless'),
                $fnBuildThead,
                h::tbody(),
                h::tfoot
                (
                    setClass('hidden'),
                    h::tr
                    (
                        h::td
                        (
                            set::colspan(10),
                            setClass('text-center form-actions'),
                            $this->session->insert ? btn(set::btntype('submit'), setClass('primary btn-wide'), $submitText) : btn(set('data-toggle', 'modal'), set('data-target', '#importNoticeModal'), setClass('primary btn-wide'), $submitText),
                            btn(set::url($backLink), setClass('btn-back btn-wide'), $lang->goback),
                            $this->session->insert && $dataInsert != '' ? formHidden('insert', $dataInsert) : null,
                            formHidden('isEndPage', $isEndPage ? 1 : 0),
                            formHidden('pagerID', $pagerID),
                            html(sprintf($lang->file->importPager, $allCount, $pagerID, $allPager))
                        )
                    )
                )
            ),
            $this->session->insert ? null : modal
            (
                set::size('sm'),
                setID('importNoticeModal'),
                set::title($lang->importConfirm),
                formHidden('insert', 0),
                div
                (
                    setClass('alert flex items-center'),
                    icon(setClass('icon-2x alert-icon'), 'exclamation-sign'),
                    div($lang->noticeImport)
                ),
                to::footer
                (
                    btn(setClass('danger btn-wide'), set('onclick', 'submitForm("cover")'), $lang->importAndCover),
                    btn(setClass('primary btn-wide'), set('onclick', 'submitForm("insert")'), $lang->importAndInsert)
                )
            )
        )
    );
}

$getTbodyLink = helper::createLink('transfer', 'ajaxGetTbody',"model={$model}&lastID=0&pagerID={$pagerID}");
h::script
(
    html
    (
        <<<JAVASCRIPT
        $('#showData > tbody').addClass('load-indicator loading');
        $.get($getTbodyLink, function(data)
        {
            $('#showData > tbody').append(data);
            if($('#showData tbody').find('tr').hasClass('showmore') === false) $('#showData tfoot').removeClass('hidden');
            $('#showData tbody').find('.picker-select').picker({chosenMode: true});
            $('.form-date').datetimepicker({minView: 2, format: "yyyy-mm-dd"});
            $('.form-datetime').datetimepicker('update');
            $('#showData > tbody').removeClass('load-indicator loading');

            if(typeof(getTbodyLoaded) == 'function') getTbodyLoaded();
        });

        $(document).off('mouseenter', '#showData .picker').on('mouseenter', '#showData .picker', function(e)
        {
            var myPicker = $(this);
            var field    = myPicker.prev().attr('data-field');
            var id       = myPicker.prev().attr('id');
            var name     = myPicker.prev().attr('name');
            var index    = Number(name.replace(/[^\d]/g, " "));
            var value    = myPicker.prev().val();

            if($('#' + id).attr('isInit')) return;

            $.get(createLink('transfer', 'ajaxGetOptions', 'model={$model}&field=' + field + '&value=' + value + '&index=' + index), function(data)
            {
                $('#' + id).parent().html(data);
                $('#' + id).picker({chosenMode: true});
                $('#' + id).attr('isInit', true);
                $('#' + id).attr('data-field', field);
            });
        });

        $(function()
        {
            $.fixedTableHead('#showData');
            $("#showData th").each(function()
            {
                if(requiredFields.indexOf(this.id) !== -1) $("#" + this.id).addClass('required');
            });
        });

        window.recomputeTimes = function()
        {
            if(parseInt($('#maxImport').val())) $('#times').html(Math.ceil(parseInt($('#allCount').html()) / parseInt($('#maxImport').val())));
        };

        window.setMaxImport = function()
        {
            $.cookie.set('maxImport', $('#maxImport').val(), {expires:config.cookieLife, path:config.webRoot});
            loadCurrentPage();
        };

        window.submitForm = function(type)
        {
            $('#importNoticeModal #insert').val(type == 'insert' ? 1 : 0);
            $("button[data-target='#importNoticeModal']").closest('form')[0].submit();
        };

        window.handleScroll = function(e)
        {
            var relative = 500; // 相对距离
            $('tr.showmore').each(function()
            {
                var $showmore = $(this);
                var offsetTop = $showmore[0].offsetTop;
                if(offsetTop == 0) return true;

                if(getScrollTop() + getWindowHeight() >= offsetTop - relative) throttle(loadData($showmore), 250)
            })
        };
        window.addEventListener('scroll', handleScroll);

        window.loadData = function($showmore)
        {
            $showmore.removeClass('showmore');
            var lastID = $showmore.attr('data-id');
            var url    = $.createLink('transfer', 'ajaxGetTbody','model={$model}&lastID=' + lastID + '&pagerID={$pagerID}');
            $.get(url, function(data)
            {
                $showmore.after(data);
                if($('#showData tbody').find('tr').hasClass('showmore') === false) $('#showData tfoot').removeClass('hidden');
                $('#showData tbody').find('.picker-select').picker({chosenMode: true}).removeClass('nopicker');
                $('.form-date').datetimepicker({minView: 2, format: "yyyy-mm-dd"});
                $('.form-datetime').datetimepicker('update');
            });
        };

        window.throttle = function(fn, threshhold)
        {
            var last;
            var timer;
            threshhold || (threshhold = 250);

            return function()
            {
                var context = this;
                var args    = arguments;
                var now     = +new Date();

                if(last && now < last + threshhold)
                {
                    clearTimeout(timer);
                    timer = setTimeout(function()
                    {
                        last = now;
                        fn.apply(context, args);
                    }, threshhold);
                }
                else
                {
                    last = now;
                    fn.apply(context, args);
                }
            }
        };

        window.getScrollTop = function()
        {
            return document.body.scrollTop + document.documentElement.scrollTop
        };

        window.getWindowHeight = function()
        {
            return document.compatMode == "CSS1Compat" ? document.documentElement.clientHeight : document.body.clientHeight
        };
        JAVASCRIPT
    )
);

render();
