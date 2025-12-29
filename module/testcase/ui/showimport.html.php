<?php
declare(strict_types=1);
/**
 * The showimport view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('productID', $productID);
jsVar('branch', $branch);
jsVar('modules', $modules);

unset($lang->testcase->typeList['unit']);
if(!empty($suhosinInfo))
{
    div
    (
        setClass('alert secondary-pale'),
        $suhosinInfo
    );
}
elseif(empty($maxImport) && $allCount > $this->config->file->maxImport)
{
    $importActions[] = array('id' => 'import', 'type' => 'primary', 'text' => $lang->import);
    $maxImportInput  = html::input('maxImport', $config->file->maxImport, "style='width:50px' class='border'");

    panel
    (
        on::change('#maxImport', 'computeImportTimes'),
        on::click('#import', 'importNextPage'),
        set::title($lang->testcase->import),
        html(sprintf($lang->file->importSummary, $allCount, $maxImportInput, ceil($allCount / $config->file->maxImport))),
        set::footerActions($importActions)
    );
}
else
{
    $priList = array_filter($lang->testcase->priList);
    $requiredFields = $config->testcase->create->requiredFields;
    $items[] = array
    (
        'name'  => 'index',
        'label' => $lang->idAB,
        'control' => 'index',
        'width' => '32px'
    );

    $items[] = array
    (
        'name'  => 'rawID',
        'label' => '',
        'hidden' => true,
        'control' => 'hidden',
        'width' => '32px'
    );

    $items[] = array
    (
        'name'  => 'product',
        'label' => '',
        'hidden' => true,
        'control' => 'hidden',
        'width' => '32px'
    );

    $items[] = $product->type != 'normal' ? array
    (
        'name'  => 'branch',
        'label' => $lang->product->branch,
        'control' => array('control' => 'picker', 'required' => true),
        'items'   => $branches,
        'width' => '120px'
    ) : array();

    $items[] = array
    (
        'name'  => 'title',
        'label' => $lang->testcase->title,
        'width' => '240px',
        'required' => strpos(",$requiredFields,", ',title,') !== false
    );

    $items[] = array
    (
        'name'    => 'module',
        'label'   => $lang->testcase->module,
        'control' => array('control' => 'picker', 'required' => true),
        'items'   => isset($modules[$branch]) ? $modules[$branch] : array(),
        'width'   => '200px',
        'required' => strpos(",$requiredFields,", ',module,') !== false
    );

    if($this->config->edition != 'open')
    {
        $items[] = array
        (
            'name'     => 'scene',
            'label'    => $lang->testcase->scene,
            'control'  => array('control' => 'picker', 'required' => true),
            'items'    => array(),
            'width'    => '200px',
            'required' => strpos(",$requiredFields,", ',scene,') !== false
        );
    }

    $items[] = array
    (
        'name'    => 'story',
        'label'   => $lang->testcase->story,
        'control' => 'picker',
        'items'   => array(),
        'width'   => '240px',
        'required' => strpos(",$requiredFields,", ',story,') !== false
    );

    $items[] = array
    (
        'name'    => 'type',
        'label'   => $lang->testcase->type,
        'control' => 'picker',
        'items'   => $lang->testcase->typeList,
        'width'   => '160px',
        'required' => strpos(",$requiredFields,", ',type,') !== false
    );

    $items[] = array
    (
        'name'    => 'pri',
        'label'   => $lang->testcase->pri,
        'control' => 'pripicker',
        'items'   => $priList,
        'width'   => '80px',
        'required' => strpos(",$requiredFields,", ',pri,') !== false
    );

    $items[] = array
    (
        'name'    => 'precondition',
        'label'   => $lang->testcase->precondition,
        'control' => 'textarea',
        'width'   => '240px',
        'required' => strpos(",$requiredFields,", ',precondition,') !== false
    );

    $items[] = array
    (
        'name'  => 'keywords',
        'label' => $lang->testcase->keywords,
        'width' => '240px',
        'required' => strpos(",$requiredFields,", ',keywords,') !== false
    );

    $items[] = array
    (
        'name'    => 'stage',
        'label'   => $lang->testcase->stage,
        'control' => 'picker',
        'multiple' => true,
        'items'   => $lang->testcase->stageList,
        'width'   => '240px',
        'required' => strpos(",$requiredFields,", ',stage,') !== false
    );

    /* Field of steps. */
    $items[] = array
    (
        'name'     => 'steps',
        'control'  => array('control' => 'textarea', 'class' => 'form-control form-batch-input text-3-row', 'placeholder' => $lang->testcase->stepsPlaceholder),
        'label'    => $lang->testcase->steps,
        'width'    => '256px',
        'required' => isset($requiredFields['steps'])
    );

    /* Field of expects. */
    $items[] = array
    (
        'name'     => 'expects',
        'control'  => array('control' => 'textarea', 'class' => 'form-control form-batch-input text-3-row'),
        'label'    => $lang->testcase->expect,
        'width'    => '256px',
        'required' => isset($requiredFields['expects'])
    );

    $insert = true;
    $caseData = array_values($caseData);
    foreach($caseData as $key => $case)
    {
        $case->type = $case->type ? $case->type : 'feature';
        if(empty($case->pri)) $case->pri = 3;
        if(empty($case->id) || !isset($cases[$case->id]))
        {
            $case->new   = true;
            $case->id    = $key + 1;
            $case->rawID = '';
        }
        else
        {
            $insert = false;

            $case->rawID = $case->id;
            if(!isset($case->module))  $case->module  = $cases[$case->id]->module;
            if(!isset($case->pri))     $case->pri     = $cases[$case->id]->pri;
            if(!isset($case->type))    $case->type    = $cases[$case->id]->type;
            if(empty($case->stage))    $case->stage   = $cases[$case->id]->stage;
            $case->product = $cases[$case->id]->product;
        }
    }

    $submitText = $isEndPage ? $this->lang->save : $this->lang->file->saveAndNext;
    formBatchPanel
    (
        set::mode('edit'),
        set::title($lang->testcase->import),
        set::items($items),
        set::data($caseData),
        set::actionsText(false),
        set::onRenderRowCol(jsRaw('renderRowCol')),
        on::change('[data-name="branch"]', 'changeBranch'),
        on::change('[data-name="module"]', 'changeModule'),
        input(set::className('hidden'), set::name('isEndPage'), set::value($isEndPage ? '1' : '0')),
        input(set::className('hidden'), set::name('pagerID'), set::value($pagerID)),
        input(set::className('hidden'), set::name('insert'), set::value($dataInsert)),
        (!$insert && $dataInsert === '') ? set::actions(array(array('text' => $submitText, 'data-toggle' => 'modal', 'data-target' => '#importNoticeModal', 'class' => 'primary showNotice'), 'cancel')) : set::submitBtnText($submitText)
    );

    if(!$insert && $dataInsert === '') include '../../common/ui/noticeimport.html.php';
}

render();
