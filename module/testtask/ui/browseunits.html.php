<?php
declare(strict_types=1);
/**
 * The browse units view file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;

$canSwitch = $this->app->tab == 'qa';
if($canSwitch)
{
    $caseTypeItems = array();
    foreach($lang->testcase->typeList as $type => $typeName)
    {
        if($type == 'unit')
        {
            $url = createLink('testtask', 'browseUnits', "productID={$product->id}&browseType=newest&orderBy=id_desc&recTotal=0&recPerPage=20&pageID=1");
        }
        else
        {
            $url = createLink('testcase', 'browse', "productID={$product->id}&branch=&browseType=all&param=0&caseType={$type}");
        }

        $caseTypeItems[] = array('text' => $typeName ?: $lang->testcase->allType, 'url' => $url, 'active' => $type == 'unit');
    }
}

$lang->testcase->featureBar['browseunits'] = $lang->testtask->featureBar['browseunits'];
featureBar
(
    $canSwitch ? to::before
    (
        dropdown
        (
            to('trigger', btn($lang->testcase->typeList['unit'], setClass('ghost'))),
            set::items($caseTypeItems)
        )
    ) : null,
    set::link(createLink('testtask', 'browseUnits', "productID={$product->id}&browseType={key}"))
);

$canModify = common::canModify('product', $product);
$canImport = hasPriv('testtask', 'importUnitResult');
if($canImport && (empty($product) || $canModify))
{
    toolbar
    (
        btn
        (
            set::className('btn primary'),
            set::icon('import'),
            set::url(createLink('testtask', 'importUnitResult', "product={$product->id}")),
            $lang->testtask->importUnitResult
        )
    );
}

$cols    = $this->config->testtask->browseUnits->dtable->fieldList;
$tasks   = initTableData($tasks, $cols, $this->testtask);
$summary = sprintf($lang->testtask->unitSummary, $pager->recTotal);

dtable
(
    set::cols($cols),
    set::data(array_values($tasks)),
    set::emptyTip($lang->testtask->emptyUnitTip),
    set::userMap($users),
    set::footer(array(array('html' => $summary), 'flex', 'pager')),
    set::footPager($browseType !== 'newest' ? usePager() : null)
);

render();
