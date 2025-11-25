<?php
declare(strict_types=1);
/**
 * The create bug view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('tab', $app->tab);
jsVar('resultsLink', createLink('testtask', 'results', "runID={$runID}&caseID={$caseID}&version={$version}&status=all&result=fail") . '#casesResults');

set::title($lang->testcase->createBug);

div
(
    setClass('main'),
    setID('resultsContainer'),
    div(setID('casesResults'))
);
