<?php
declare(strict_types=1);
/**
 * The browse view file of company module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     company
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('resultHeader',       isset($resultHeader) ? $resultHeader : array());
jsVar('resultData',         isset($resultData)   ? $resultData   : array());
jsVar('objectList',         $lang->metric->objectList);
jsVar('current',            $current);
jsVar('maxSelectNum',       $config->metric->maxSelectNum);
jsVar('maxSelectMsg',       $lang->metric->maxSelect);
jsVar('maxSelectTip',       $lang->metric->maxSelectTip);
jsVar('viewType',           $viewType);
jsVar('selectCount',        $lang->metric->selectCount);
jsVar('filterLang',         $lang->metric->filter);
jsVar('scope',              $scope);
jsVar('metricListLang',     $metricList);
jsVar('chartTypeList',      $lang->metric->chartTypeList);
jsVar('errorDateRange',     $lang->metric->errorDateRange);
jsVar('errorCalcTimeRange', $lang->metric->errorCalcTimeRange);
jsVar('queryScopeLang',     $lang->metric->query->scope);
jsVar('collectStar',        $lang->metric->collectStar);
// jsVar('pager',              usePager());

if($scope == 'collect' and empty($current))
{
    include 'emptycollect.html.php';
}
else
{
    if($viewType == 'single') include 'previewsingle.html.php';
    if($viewType == 'multiple') include 'previewmultiple.html.php';
}
