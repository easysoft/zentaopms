<?php
declare(strict_types=1);
/**
 * The importissue view file of gitlab module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     gitlab
 * @link        https://www.zentao.net
 */
namespace zin;

$items = array();
$items[] = array('name' => 'title', 'label' => $lang->gitlab->gitlabIssue, 'control' => 'static');
$items[] = array('name' => 'objectTypeList', 'label' => $lang->gitlab->objectType, 'control' => 'picker', 'items' => $objectTypes);
$items[] = array('name' => 'productList', 'label' => $lang->productCommon, 'control' => 'picker', 'items' => $products, '' => 'loadProductExecutions'  );
$items[] = array('name' => 'executionList', 'label' => $lang->execution->common, 'control' => 'picker', 'items' => array());

foreach($gitlabIssues as $issue)
{
    $issue->id       = $issue->iid;
}

if(empty($gitlabIssues))
{
    panel
    (
        setStyle('--zt-page-form-max-width', 'auto'),
        setClass('panel-form'),
        set::size('lg'),
        set::title($lang->gitlab->importIssue),
        $lang->gitlab->noImportableIssues
    );
}
else
{
formBatchPanel
(
    h::input
    (
        set::type('hidden'),
        set::name('gitlabID'),
        set::value($gitlabID)
    ),
    h::input
    (
        set::type('hidden'),
        set::name('gitlabProjectID'),
        set::value($gitlabProjectID)
    ),
    set::title($lang->gitlab->importIssue),
    set::mode('edit'),
    set::items($items),
    set::data($gitlabIssues),
    set::onRenderRowCol(jsRaw('window.renderRowCol')),
    on::change('[data-name="productList"]', 'loadProductExecutions')
);
}

render();

