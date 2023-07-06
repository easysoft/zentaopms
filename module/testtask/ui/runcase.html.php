<?php
declare(strict_types=1);
/**
 * The runCase view file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('caseResultSave', $lang->save);
jsVar('tab', $app->tab);
jsVar('confirm', $confirm);
jsVar('resultsLink', createLink('testtask', 'results', "runID={$runID}&caseID={$caseID}&version={$version}&status=all"));

modalHeader
(
    set::title($lang->testtask->lblRunCase),
    set::entityText($run->case->title),
    set::entityID($caseID),
);

form
(
    set::actions(array()),
    h::table
    (
        setClass('table bordered'),
        h::tr
        (
            set('colspan', '5'),
            h::td
            (
                h::strong($lang->testcase->precondition),
                h::br(),
                nl2br($run->case->precondition),
            ),
        ),
        h::tr
        (
            set('colspan', '5'),
            h::td
            (
                setClass('flex justify-center gap-x-4'),
                $preCase ? h::a
                (
                    setClass('btn btn-wide'),
                    set::id('pre'),
                    set::href(createLink('testcase', 'runCase', "runID={$preCase['runID']}&caseID={$preCase['caseID']}&version={$preCase['version']}")),
                    $lang->testtask->pre,
                ) : '',
                $run->case->status != 'wait' && $confirm != 'yes' ? h::a
                (
                    setClass('btn primary btn-wide'),
                    set::id('submit'),
                    set::href(createLink('testcase', 'runCase', "runID={$preCase['runID']}&caseID={$preCase['caseID']}&version={$preCase['version']}")),
                    $lang->save,
                ) : '',
                $preCase ? h::a
                (
                    setClass('btn btn-wide'),
                    set::id('next'),
                    set::href(createLink('testcase', 'runCase', "runID={$preCase['runID']}&caseID={$preCase['caseID']}&version={$preCase['version']}")),
                    $lang->testtask->pre,
                ) : '',
            ),
        ),
    ),
);
div
(
    setClass('main'),
    set::id('resultsContainer'),
    div
    (
        set::id('casesResults'),
    ),
);

render();

