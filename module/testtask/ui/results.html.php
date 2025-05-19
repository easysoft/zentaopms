<?php
declare(strict_types=1);
/**
 * The results view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('tab', $app->tab);

modalHeader
(
    set::title($lang->testtask->lblResults),
    set::entityText($case->title),
    set::entityID($case->id),
);

div
(
    setClass('border-b'),
    div
    (
        h::strong($lang->testcase->precondition)
    ),
    div
    (
        setClass('leading-6 my-2', empty($case->precondition) ? 'h-6' : ''),
        nl2br($case->precondition)
    )
);

$count      = count($results);
$trs        = array();
$fileModals = array();
$trCount    = 1;
$failCount  = 0;
foreach($results as $i => $result)
{
    $class     = ($result->caseResult == 'pass' ? 'success' : ($result->caseResult == 'fail' ? 'danger' : ($result->caseResult == 'blocked' ? 'warning' : '')));
    $fileCount = count($result->files);
    $isChinese = strpos($app->getClientLang(), 'zh-') !== false;
    if($result->node == 0 && $result->task > 0)
    {
        $taskResult = $isChinese ? sprintf($lang->testtask->runInTask, zget($testtasks, $result->task, ''), zget($builds, $result->build, '')) : sprintf($lang->testtask->runInTask, zget($testtasks, $result->task, ''), zget($users, $result->lastRunner), zget($builds, $result->build, ''));
    }
    else
    {
        $taskResult = !$isChinese ? ' by <strong>' . zget($users, $result->lastRunner) . '</strong>' : '';
    }
    if($class != 'success') $failCount ++;
    $trs[] = h::tr
    (
        setClass("result-item result{$class} h-12 is-collapsed"),
        on::click('toggleShowResults'),
        set('data-id', $result->id),
        set('data-status', $result->node > 0 && empty($result->ZTFResult) ? 'running': 'ready'),
        h::td
        (
            span(setClass('toggle-icon inline-block align-middle mr-2')),
            width('120px'),
            label(setClass('mx-2 gray-pale'), "#{$result->id}"),
            $result->date,
            $result->node > 0 ? sprintf($lang->testtask->runNode, zget($users, $result->lastRunner), $result->nodeName, $lang->testtask->runCase) : '',
            $result->node == 0 || !empty($result->ZTFResult) ? html
            (
                $isChinese ? sprintf($lang->testtask->runCaseResult, zget($users, $result->lastRunner), $taskResult, $class, $lang->testcase->resultList[$result->caseResult]) : sprintf($lang->testtask->runCaseResult, $taskResult, $class, $lang->testcase->resultList[$result->caseResult])
            ) : h::strong
            (
                setClass('text-waring'),
                $lang->testtask->running
            ),
            $result->node > 0 ? span
            (
                setClass('label'),
                $lang->testtask->auto
            ) : null
        )
    );

    $stepResultTrs = array();
    foreach($result->stepResults as $key => $stepResult)
    {
        if(empty($stepResult['id']))     $stepResult['id']     = 0;
        if(empty($stepResult['type']))   $stepResult['type']   = 'step';
        if(empty($stepResult['parent'])) $stepResult['parent'] = 0;
        if(empty($stepResult['result'])) $stepResult['result'] = 0;
        if(empty($stepResult['grade']))  $stepResult['grade']  = 0;

        $itemTds   = array();
        $modalID   = $result->id . '-' . $key;
        $fileCount = count($stepResult['files']);
        $itemTds[] = div
        (
            setClass('text-left flex border-r break-all'),
            width('calc(25% + 2px)'),
            isset($stepResult['expect']) ? html(nl2br($stepResult['expect'])) : ''
        );
        $itemTds[] = div
        (
            setClass('text-left flex border-r text-gray'),
            width('80px'),
            isset($stepResult['version']) ? "#{$stepResult['version']}" : ''
        );
        $itemTds[] = !empty($stepResult['result']) ? div
        (
            setClass("status-{$stepResult['result']} text-center flex border-r"),
            width('80px'),
            $lang->testcase->resultList[$stepResult['result']]
        ) : div
        (
            setClass('border-r'),
            width('80px')
        );
        $itemTds[] = !empty($stepResult['result']) ? div
        (
            setClass('text-left flex border-r break-all'),
            width('240px'),
            html(nl2br($stepResult['real'] ?? ''))
        ) : div
        (
            setClass('border-r'),
            width('240px')
        );
        $itemTds[] = !empty($stepResult['result']) ? div
        (
            setClass('text-center flex'),
            width('56px'),
            !empty($stepResult['files']) ? a
            (
                on::click('setFileModalHeight'),
                set::href("#stepResult{$modalID}"),
                set('data-toggle', 'modal'),
                icon('paper-clip'),
                $fileCount
            ) : ''
        ) : div(width('56px'));
        $fileModals[] = modal
        (
            set::id("stepResult{$modalID}"),
            !empty($stepResult['files']) ? fileList(set::extra($stepResult['id']), set::files($stepResult['files'])) : ''
        );

        $stepResultTrs[] = div
        (
            setClass("step flex border-b step-{$stepResult['type']} "),
            set('data-parent', $stepResult['parent']),
            set('data-grade', $stepResult['grade']),
            set('data-id', $key),
            div
            (
                setClass('step-id flex border-r check-item'),
                width('calc(75% - 292px)'),
                $result->caseResult == 'fail' ? checkbox
                (
                    on::click('toggleCheckChildItem'),
                    set::id("stepIdList[{$result->id}][{$stepResult['id']}]"),
                    set('name', "stepIdList[{$result->id}][{$stepResult['id']}]"),
                    set('value', $key)
                ) : '',
                div
                (
                    setClass('inputGroup row', 'pl-' . ((zget($stepResult, 'grade', 1) - 1) * 2)),
                    span
                    (
                        setClass('step-item-id mr-2'),
                        zget($stepResult, 'name', '')
                    ),
                    div(setClass('wrap break-all'), html(nl2br(zget($stepResult, 'desc', ''))))
                )
            ),
            $itemTds
        );
    }
    $stepResultTrs[] = !empty($result->ZTFResult) && $result->node > 0 ? div
    (
        h::p($lang->testtask->runningLog),
        h::p(html($result->ZTFResult))
    ) : '';

    $projectParam   = $this->app->tab == 'project' ? "projectID={$this->session->project}," : '';
    $executionParam = $this->app->tab == 'execution' ? "executionID={$this->session->execution}" : "";
    $executionParam = isset($testtask) ? "executionID={$testtask->execution}" : $executionParam;
    $params         = isset($testtask) ? ",testtask={$testtask->id}" : "";
    $params         = $params . ',buildID=' . (isset($testtask->build) ? $testtask->build : $result->build);
    $linkParams     = "product={$case->product}&branch={$case->branch}&extras={$projectParam}caseID={$case->id},version={$case->version},resultID={$result->id},runID={$result->run}{$params}";
    if($executionParam) $params .= ',' . $executionParam;
    $trs[] = h::tr
    (
        set::id('tr-detail_' . $trCount ++),
        setClass('result-detail hidden'),
        h::td
        (
            setClass('pd-0'),
            form
            (
                set('data-params', $linkParams),
                set::actions(array()),
                div
                (
                    setClass('resultSteps ' . $result->caseResult),
                    div
                    (
                        setClass('steps-header flex border-b'),
                        div
                        (
                            width('calc(75% - 296px)'),
                            setClass('text-left desc border-r'),
                            $lang->testcase->stepDesc
                        ),
                        div
                        (
                            width('calc(25%)'),
                            setClass('text-left border-r'),
                            $lang->testcase->stepExpect
                        ),
                        div
                        (
                            width('80px'),
                            setClass('text-center border-r'),
                            $lang->testcase->version
                        ),
                        div
                        (
                            width('80px'),
                            setClass('text-center border-r'),
                            $lang->testcase->resultAB
                        ),
                        div
                        (
                            width('240px'),
                            setClass('text-left border-r'),
                            $lang->testcase->real
                        ),
                        div
                        (
                            width('56px'),
                            setClass('text-left'),
                            $lang->attach
                        )
                    ),
                    div
                    (
                        setClass('steps-body ml-2'),
                        $stepResultTrs
                    ),
                    $result->caseResult == 'fail' && common::hasPriv('testcase', 'createBug') ? div
                    (
                        setClass('check-all flex items-center h-12 pl-4 border-t'),
                        checkbox
                        (
                            on::click('toggleCheckAll'),
                            set::id("checkAll[{$i}]"),
                            set('type', 'checkbox'),
                            set('name', 'checkAll'),
                            set::text($lang->selectAll)
                        ),
                        div
                        (
                            btn
                            (
                                setClass('btn h-7 px-6 ml-8 to-bug-button'),
                                set::type('primary'),
                                on::click('createBug'),
                                $lang->testcase->createBug
                            )
                        )
                    ) : ''
                )
            )
        )
    );
}
$resultItem = array();
$resultItem[] = span
(
    setClass('px-3 my-1 border-r'),
    html(sprintf($lang->testtask->showResult, $count))
);
$resultItem[] = span
(
    setClass('pl-3 my-1'),
    html(sprintf($lang->testtask->showFail, $failCount))
);

div
(
    div
    (
        setClass('main' . ($type != 'fail' ? ' mt-6' : '')),
        set::id('casesResults'),
        $case->auto != 'unit' && $type != 'fail' ? formRowGroup
        (
            set::title($lang->testcase->resultAB),
            set::items
            (
                $resultItem
            )
        ) : null,
        h::table
        (
            setClass('table border'),
            $trs
        ),
        $fileModals
    )
);

render();

