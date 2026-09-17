<?php
declare(strict_types=1);
/**
 * The edit view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

data('testcase', $case);

jsVar('tab', $app->tab);
jsVar('isLibCase', $isLibCase);
jsVar('caseBranch', $case->branch);
if($app->tab == 'execution') jsVar('objectID', $case->execution);
if($app->tab == 'project')   jsVar('objectID', $case->project);
if($app->tab == 'qa')        jsVar('objectID', 0);

set::title($lang->testcase->edit);
$config->file->dangers = 'phtml,jsp,asp,aspx,ashx,asa,cer,cdx,aspl,shtm,shtml,html,htm';

$fields = useFields('testcase.edit');
detailForm
(
    set::object($case),
    set::objectType('testcase'),
    set::objectID($case->id),
    set::fields($fields),

    on::change('#lib', 'loadLibModules'),
    on::change('#product', 'loadProductRelated'),
    on::change('#module', 'loadModuleRelated'),
    on::change('#branch', 'loadBranchRelated'),
    on::click('#refresh', $isLibCase ? 'loadLibModules' : 'loadProductModules'),
    on::click('#auto', 'checkScript')
);

render();
