<?php
declare(strict_types=1);
/**
 * The bug view file of product plan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     productplan
 * @link        https://www.zentao.net
 */
namespace zin;

$bugCols = array();
foreach($config->productplan->defaultFields['bug'] as $field) $bugCols[$field] = zget($config->bug->dtable->fieldList, $field, array());

$bugCols['assignedTo']['type'] = 'user';

foreach($bugCols as $bugColKey => $bugCol)
{
    $bugCols[$bugColKey]['sortType'] = false;
    if(isset($bugCol['link'])) unset($bugCols[$bugColKey]['link']);
    if($bugColKey == 'pri')      $bugCols[$bugColKey]['priList']      = $lang->bug->priList;
    if($bugColKey == 'severity') $bugCols[$bugColKey]['severityList'] = $lang->bug->severityList;
    if($bugColKey == 'title')    $bugCols[$bugColKey]['link']         = array('url' => createLink('bug', 'view', "bugID={id}"), 'data-toggle' => 'modal', 'data-size' => 'lg');
}
unset($bugCols['actions']);

$productsWithShadow = $this->loadModel('product')->getPairs('', 0, '', 'all');
$productChangeLink  = createLink('productplan', 'bug', "productID={productID}&planID=0&blockID=$blockID&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from");
$planChangeLink     = createLink('productplan', 'bug', "productID=$productID&planID={planID}&blockID=$blockID&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from");
$insertListLink     = createLink('productplan', 'bug', "productID=$productID&planID=$planID&blockID={blockID}&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from");

$footToolbar = array(array('text' => $lang->doc->insertText, 'data-on' => 'click', 'data-call' => "insertListToDoc('#planBugs', 'planBug', $blockID, '$insertListLink')"));
if($from == 'ai') $footToolbar = array(array('text' => $lang->doc->insertText, 'data-on' => 'click', 'data-call' => "insertListToAI('#planBugs', 'planBug')"));

formPanel
(
    setID('zentaolist'),
    setClass('mb-4-important'),
    set::title(sprintf($this->lang->doc->insertTitle, $this->lang->doc->zentaoList['planBug'])),
    set::actions(array()),
    set::showExtra(false),
    to::titleSuffix
    (
        span
        (
            setClass('text-muted text-sm text-gray-600 font-light'),
            span
            (
                setClass('text-warning mr-1'),
                icon('help'),
            ),
            $lang->doc->previewTip
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('product'),
            set::label($lang->doc->product),
            set::control(array('required' => false)),
            set::items($productsWithShadow),
            set::value($productID),
            set::required(),
            span
            (
                setClass('error-tip text-danger hidden'),
                $lang->doc->emptyError
            ),
            on::change('[name="product"]')->do("loadModal('$productChangeLink'.replace('{productID}', $(this).val()))")
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('plan'),
            set::label($lang->doc->plan),
            set::control(array('required' => false)),
            set::items($plans),
            set::value($planID),
            set::required(),
            span
            (
                setClass('error-tip text-danger hidden'),
                $lang->doc->emptyError
            ),
            on::change('[name="plan"]')->do("loadModal('$planChangeLink'.replace('{planID}', $(this).val()))")
        )
    )
);

dtable
(
    setID('planBugs'),
    set::userMap($users),
    set::checkable(),
    set::cols($bugCols),
    set::data(array_values($bugs)),
    set::noNestedCheck(),
    set::orderBy($orderBy),
    set::footPager(usePager()),
    set::emptyTip($lang->bug->notice->noBug),
    set::footToolbar($footToolbar),
    set::height(400),
    set::afterRender(jsCallback()->call('toggleCheckRows', $idList)),
    set::onCheckChange(jsRaw('window.checkedChange'))
);

render();
