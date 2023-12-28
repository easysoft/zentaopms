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
jsVar('stepData', $stepData);
jsVar('productID', $productID);
jsVar('branch', $branch);

if(isset($suhosinInfo))
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
        'name'  => 'id',
        'label' => $lang->idAB,
        'control' => 'index',
        'width' => '32px'
    );

    $items[] = array
    (
        'name'  => 'title',
        'label' => $lang->testcase->title,
        'width' => '240px',
        'required' => strpos(",$requiredFields,", ',title,') !== false
    );

    $caseModules = ($branch and isset($modules[$branch])) ? $modules[BRANCH_MAIN] + $modules[$branch] : $modules[BRANCH_MAIN];
    $items[] = array
    (
        'name'    => 'module',
        'label'   => $lang->testcase->module,
        'control' => 'picker',
        'items'   => $caseModules,
        'width'   => '200px',
        'required' => strpos(",$requiredFields,", ',module,') !== false
    );

    $storyID = isset($case->story) ? $case->story : ((!empty($case->id) and isset($cases[$case->id])) ? $cases[$case->id]->story : '');
    $items[] = array
    (
        'name'    => 'story',
        'label'   => $lang->testcase->story,
        'control' => 'picker',
        'items'   => array($storyID => zget($stories, $storyID, '')),
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
            $case->id  = '';
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
        set::title($lang->testcase->import),
        set::items($items),
        set::data(array_values($caseData)),
        set::mode('edit'),
        set::actionsText(false),
        set::onRenderRow(jsRaw('handleRenderRow')),
        input(set::className('hidden'), set::name('isEndPage'), set::value($isEndPage ? '1' : '0')),
        input(set::className('hidden'), set::name('pagerID'), set::value($pagerID)),
        input(set::className('hidden'), set::name('insert'), set::value($dataInsert)),
        (!$insert && $dataInsert === '') ? set::actions(array(array('text' => $submitText, 'data-toggle' => 'modal', 'data-target' => '#importNoticeModal', 'class' => 'primary showNotice'), 'cancel')) : set::submitBtnText($submitText)
    );

    if(!$insert && $dataInsert === '') include '../../common/ui/noticeimport.html.php';
}

render();
