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

$fields = array();
$fields['id']      = array('name' => 'id', 'label' => $lang->transfer->id, 'control' => 'input', 'required' => false, 'width' => '64px', 'hidden' => true);
$fields['idIndex'] = array('name' => 'id', 'label' => $lang->transfer->id, 'control' => 'index', 'required' => false, 'width' => '64px');
$fields           += $datas->fields;

$requiredFields = $datas->requiredFields;
$allCount       = $datas->allCount;
$allPager       = $datas->allPager;
$pagerID        = $datas->pagerID;
$isEndPage      = $datas->isEndPage;
$maxImport      = $datas->maxImport;
$dataInsert     = $datas->dataInsert;
$suhosinInfo    = $datas->suhosinInfo;
$module         = $datas->module;
$datas          = $datas->datas;
$appendFields   = $this->session->appendFields;
$notEmptyRule   = $this->session->notEmptyRule;

if(!empty($suhosinInfo))
{
    div(setClass('alert secondary'), html($suhosinInfo));
}
elseif(empty($maxImport) and $allCount > $this->config->file->maxImport)
{
    panel
    (
        on::keyup('[name=maxImport]', 'recomputeTimes'),
        set::title($lang->transfer->import),
        html(sprintf($lang->file->importSummary, $allCount, html::input('maxImport', $config->file->maxImport, "style='width:50px'"), ceil($allCount / $config->file->maxImport))),
        btn(setID('import'), setClass('primary'), on::click('setMaxImport'), $lang->import)
    );
    pageJS(<<<JAVASCRIPT
    window.recomputeTimes = function()
    {
        if(parseInt($('#maxImport').val())) $('#times').html(Math.ceil(parseInt($allCount) / parseInt($('#maxImport').val())));
    };

    window.setMaxImport = function()
    {
        $.cookie.set('maxImport', $('#maxImport').val(), {expires:config.cookieLife, path:config.webRoot});
        loadCurrentPage();
    };
    JAVASCRIPT);
}
else
{
    $submitText  = $isEndPage ? $lang->save : $lang->file->saveAndNext;
    $isStartPage = $pagerID == 1;

    $index = 1;
    foreach($datas as $data)
    {
        if(empty($data->id)) $data->id = $index ++;
    }

    formBatchPanel
    (
        set::title($lang->transfer->import),
        set::mode('edit'),
        set::items($fields),
        set::data(array_values($datas)),
        set::actions(array()),
        div
        (
            setClass('toolbar form-actions form-group no-label'),
            $this->session->insert ? btn(set::btnType('submit'), setClass('primary btn-wide'), $submitText) : btn(set('data-toggle', 'modal'), set('data-target', '#importNoticeModal'), setClass('primary btn-wide'), $submitText),
            btn(set::url($backLink), setClass('btn-back btn-wide'), $lang->goback),
            $this->session->insert && $dataInsert != '' ? formHidden('insert', $dataInsert) : null,
            formHidden('isEndPage', $isEndPage ? 1 : 0),
            formHidden('pagerID', $pagerID),
            html(sprintf($lang->file->importPager, $allCount, $pagerID, $allPager))
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
                btn(setClass('danger btn-wide'), set('onclick', 'submitForm("cover")'), $lang->importAndCover, set::btnType('submit')),
                btn(setClass('primary btn-wide'), set('onclick', 'submitForm("insert")'), $lang->importAndInsert, set::btnType('submit'))
            )
        )
    );
    pageJS(<<<JAVASCRIPT
    window.submitForm = function(type)
    {
        $('#importNoticeModal [name=insert]').val(type == 'insert' ? 1 : 0);
    };
    JAVASCRIPT);
}

