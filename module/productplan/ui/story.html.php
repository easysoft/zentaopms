<?php
declare(strict_types=1);
/**
 * The story view file of product plan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     productplan
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('gradeGroup', $gradeGroup);
$isFromAI = $from === 'ai';

$storyCols = array();
foreach($config->productplan->defaultFields['story'] as $field)
{
    if($field == 'branch' && $product->type == 'normal') continue;
    $storyCols[$field] = zget($config->story->dtable->fieldList, $field, array());
}
if(isset($storyCols['branch'])) $storyCols['branch']['map'] = $branchOption;

$storyCols['title']['title']     = $lang->productplan->storyTitle;
$storyCols['assignedTo']['type'] = 'user';
$storyCols['module']['type']     = 'text';
$storyCols['module']['map']      = $modulePairs;

foreach($storyCols as $storyColKey => $storyCol)
{
    $storyCols[$storyColKey]['sortType'] = false;
    if(isset($storyCol['link'])) unset($storyCols[$storyColKey]['link']);
    if($storyColKey == 'pri') $storyCols[$storyColKey]['priList'] = $lang->story->priList;
    if($storyColKey == 'title') $storyCols[$storyColKey]['link'] = array('url' => createLink('{type}', 'view', "storyID={id}&version={version}"), 'data-toggle' => 'modal', 'data-size' => 'lg');
}
unset($storyCols['actions']);


$productsWithShadow = $this->loadModel('product')->getPairs('', 0, '', 'all');
$productChangeLink  = createLink('productplan', 'story', "productID={productID}&planID=0&blockID=$blockID&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from");
$planChangeLink     = createLink('productplan', 'story', "productID=$productID&planID={planID}&blockID=$blockID&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from");
$insertListLink     = createLink('productplan', 'story', "productID=$productID&planID=$planID&blockID={blockID}&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&from=$from");

$footToolbar = array(array('text' => $lang->doc->insertText, 'data-on' => 'click', 'data-call' => "insertListToDoc('#planStories', 'planStory', $blockID, '$insertListLink')"));
if($isFromAI) $footToolbar = array(array('text' => $lang->doc->insertText, 'data-on' => 'click', 'data-call' => "insertListToAI('#planStories', 'story')"));

formPanel
(
    setID('zentaolist'),
    setClass('mb-4-important'),
    set::title(sprintf($this->lang->doc->insertTitle, $this->lang->doc->zentaoList['planStory'])),
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

foreach($planStories as $story) $story->estimate = $story->estimate . $config->hourUnit;
dtable
(
    setID('planStories'),
    set::userMap($users),
    set::checkable(),
    set::cols($storyCols),
    set::data(array_values($planStories)),
    set::noNestedCheck(),
    set::orderBy($orderBy),
    set::onRenderCell(jsRaw('window.renderStoryCell')),
    set::footPager(usePager()),
    set::emptyTip($lang->story->noStory),
    set::footToolbar($footToolbar),
    set::height(400),
    set::afterRender(jsCallback()->call('toggleCheckRows', $idList)),
    set::onCheckChange(jsRaw('window.checkedChange'))
);

render();
