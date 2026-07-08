<?php
declare(strict_types=1);
/**
 * The solution ruleset view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;

$cols = $config->codescan->ruleset->dtable->fieldList;
if(isset($cols['plugin'])) $cols['plugin']['map'] = $pluginList;
if(isset($cols['lang']))   $cols['lang']['map']   = $langList;

unset($cols['actions']);
unset($cols['updatedAt']);
unset($cols['updatedBy']);
unset($cols['createdBy']);
unset($cols['createdAt']);
unset($cols['rulesCount']['link']);
foreach($cols as &$col) $col['sortType'] = false;

$rulesets    = zget($solution, 'ruleSets', array());
$rulesetList = initTableData($rulesets, $cols);

modalHeader(set::title($lang->codescan->ruleset), set::entityText($solution->name), set::entityID($solutionID));

dtable
(
    set::sortable(false),
    set::cols($cols),
    set::data($rulesetList)
);
