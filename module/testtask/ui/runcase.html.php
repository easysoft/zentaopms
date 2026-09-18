<?php
declare(strict_types=1);
/**
 * The runCase view file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;
$fields = useFields('testtask.runCase');

jsVar('caseResultSave', $lang->save);
jsVar('tab', $app->tab);
jsVar('confirm', $confirm);
jsVar('resultsLink', createLink('testtask', 'results', "runID={$runID}&caseID={$caseID}&version={$version}&status=all"));

modalHeader
(
    set::title($lang->testtask->lblRunCase),
    set::entityText($run->case->title),
    set::entityID($caseID)
);

$actions = array();
if($preLink)  $actions[] = array('text' => $lang->testtask->pre, 'data-load' => 'modal', 'url' => $preLink, 'id' => 'pre');
if($run->case->status != 'wait' && $confirm != 'yes') $actions[] = 'submit';
if($nextLink) $actions[] = array('text' => $lang->testtask->next, 'data-load' => 'modal', 'url' => $nextLink, 'id' => 'next');

formPanel
(
    set::id('caseStepForm'),
    set::actions($actions),
    set::fields($fields),
    set::grid(false),
);

div
(
    setClass('main border-t'),
    set::id('resultsContainer'),
    on::init()->do('loadResult()'),
    div(set::id('casesResults'))
);

set::id('runCaseModal');
