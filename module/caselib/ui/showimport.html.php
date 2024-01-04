<?php
declare(strict_types=1);
/**
 * The showimport view file of caselib module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     caselib
 * @link        https://www.zentao.net
 */
namespace zin;
if(isset($suhosinInfo))
{
    div
    (
        setClass('alert secondary-pale'),
        html($suhosinInfo)
    );
}
elseif(empty($maxImport) && $totalAmount > $this->config->file->maxImport)
{
    $importActions[] = array('id' => 'import', 'type' => 'primary', 'text' => $lang->import);
    $maxImportInput  = html::input('maxImport', $config->file->maxImport, "style='width:50px' class='border'");

    panel
    (
        on::change('#maxImport', 'computeImportTimes'),
        on::click('#import', 'importNextPage'),
        set::title($lang->caselib->import),
        html(sprintf($lang->file->importSummary, $totalAmount, $maxImportInput, ceil($totalAmount / $config->file->maxImport))),
        set::footerActions($importActions)
    );
}
else
{
    jsVar('stepData', $stepData);
    jsVar('libID', $libID);
    jsVar('newTestcase', $lang->testcase->new);

    $items[] = array
    (
        'name'  => 'id',
        'label' => $lang->idAB,
        'control' => 'index',
        'width' => '50px'
    );

    $items[] = array
    (
        'name'  => 'title',
        'label' => $lang->testcase->title,
        'width' => '240px'
    );

    $items[] = array
    (
        'name'    => 'module',
        'label'   => $lang->testcase->module,
        'control' => 'picker',
        'items'   => $modules,
        'required' => true,
        'width'   => '200px'
    );

    $items[] = array
    (
        'name'    => 'type',
        'label'   => $lang->testcase->type,
        'control' => 'picker',
        'items'   => $lang->testcase->typeList,
        'required' => true,
        'width'   => '160px'
    );

    $items[] = array
    (
        'name'    => 'pri',
        'label'   => $lang->testcase->pri,
        'control' => 'pripicker',
        'items'   => $lang->testcase->priList,
        'width'   => '80px'
    );

    $items[] = array
    (
        'name'    => 'precondition',
        'label'   => $lang->testcase->precondition,
        'control' => 'textarea',
        'width'   => '240px'
    );

    $items[] = array
    (
        'name'  => 'keywords',
        'label' => $lang->testcase->keywords,
        'width' => '240px'
    );

    $items[] = array
    (
        'name'    => 'stage',
        'label'   => $lang->testcase->stage,
        'control' => 'picker',
        'multiple' => true,
        'items'   => $lang->testcase->stageList,
        'width'   => '240px'
    );

    $items[] = array
    (
        'name'  => 'stepDesc',
        'label' => $lang->testcase->stepDesc,
        'width' => '320px'
    );

    $items[] = array
    (
        'name'  => 'stepExpect',
        'label' => $lang->testcase->stepExpect,
        'width' => '320px'
    );

    $insert = true;
    foreach($caseData as $key => $case)
    {
        if(empty($case->id) || !isset($cases[$case->id]))
        {
            $case->new = true;
            $case->id  = $key;
        }
        else
        {
            $insert = false;

            if(!isset($case->module)) $case->module = $cases[$case->id]->module;
            if(!isset($case->pri))    $case->pri    = $cases[$case->id]->pri;
            if(!isset($case->type))   $case->type   = $cases[$case->id]->type;
            if(empty($case->stage))   $case->stage  = $cases[$case->id]->stage;
        }
    }

    $submitText = $isEndPage ? $this->lang->save : $this->lang->file->saveAndNext;
    formBatchPanel
    (
        set::title($lang->caselib->import),
        set::items($items),
        set::data(array_values($caseData)),
        set::mode('edit'),
        set::actionsText(false),
        set::onRenderRow(jsRaw('handleRenderRow')),
        input(set::className('hidden'), set::name('isEndPage'), set::value($isEndPage ? '1' : '0')),
        input(set::className('hidden'), set::name('pageID'), set::value($pageID)),
        input(set::className('hidden'), set::name('insert'), set::value($dataInsert)),
        (!$insert && $dataInsert === '') ? set::actions(array(array('text' => $submitText, 'data-toggle' => 'modal', 'data-target' => '#importNoticeModal', 'class' => 'primary showNotice'), 'cancel')) : set::submitBtnText($submitText)
    );

    if(!$insert && $dataInsert === '') include '../../common/ui/noticeimport.html.php';
}

render();
