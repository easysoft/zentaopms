<?php
declare(strict_types=1);
/**
 * The linkbug file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao <zhaoke@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('orderBy',  $orderBy);
jsVar('sortLink', createLink('mr', 'linkBug', "MRID=$MRID&productID=$product->id&browseType=$browseType&param=$param&orderBy={orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"));

$footToolbar = array(
    'items' => array
    (
        array(
            'text'      => $lang->productplan->linkBug,
            'className' => 'batch-btn ajax-btn',
            'data-app'  => $app->tab,
            'data-url'  => helper::createLink('mr', 'linkBug', "MRID=$MRID&productID=$product->id&browseType=$browseType&param=$param&orderBy=$orderBy")
        )
    ),
    'btnProps' => array('size' => 'sm', 'btnType' => 'secondary', 'data-type' => 'bugs')
);

searchForm
(
    set::module('bug'),
    set::simple(true),
    set::show(true)
);

div
(
    set('class', 'mr-linkstory-title'),
    icon('unlink'),
    span
    (
        set('class', 'font-semibold ml-2'),
        $lang->productplan->unlinkedBugs . "({$pager->recTotal})"
    )
);
$cols = array();
foreach($config->release->dtable->defaultFields['linkBug'] as $field) $cols[$field] = zget($config->bug->dtable->fieldList, $field, array());
$data = array_values($allBugs);
$cols['title']['data-toggle'] = '';
$cols['title']['link']        = array('module' => 'bug', 'method' => 'view', 'params' => 'bugID={id}', 'target' => '_blank');
dtable
(
    set::userMap($users),
    set::data($data),
    set::cols($cols),
    set::checkable(true),
    set::footToolbar($footToolbar),
    set::sortLink(jsRaw('createSortLink')),
    set::footer(array('checkbox', 'toolbar', array('html' => html::a(inlink('link', "MRID=$MRID&type=bug"), $lang->goback, '', "class='btn size-sm'")), 'flex', 'pager')),
    set::footPager(usePager())
);

render();
