<?php
declare(strict_types=1);
/**
 * The product view file of program module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      wangyidong<wangyidong@cnezsoft.com>
 * @package     program
 * @link        https://www.zentao.net
 */

namespace zin;

dropmenu();

featureBar
(
    set::current($browseType),
    set::linkParams("programID={$programID}&browseType={key}&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")
);

toolbar
(
    item(set
    (array(
        'text'     => $lang->product->create,
        'icon'     => 'plus',
        'class'    => 'btn primary',
        'data-app' => 'product',
        'url'      => $this->createLink('product', 'create', "programID={$programID}")
    )))
);

$cols = $this->config->product->dtable->fieldList;

$data = array();
$products = initTableData($products, $cols, $this->product);
foreach($products as $product) $data[] = $this->product->formatDataForList($product, $users, $usersAvatar);

$summary = sprintf($lang->product->pageSummary, count($data));
$footToolbar = hasPriv('product', 'batchEdit') ? array(
    'type'  => 'btn-group',
    'items' => array(
        array(
            'text'      => $lang->edit,
            'className' => 'secondary size-sm batch-btn',
            'data-page' => 'batch',
            'data-app'  => $this->app->tab,
            'data-formaction' => $this->createLink('product', 'batchEdit', "programID={$programID}")
        )
    )
) : null;

dtable
(
    setID('products'),
    set::userMap($users),
    set::cols($cols),
    set::data($data),
    set::nested(false),
    set::orderBy($orderBy),
    set::sortLink(createLink('program', 'product', "programID={$programID}&browseType={$browseType}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
    set::checkInfo(jsRaw("function(checkedIDList){ return window.footerSummary(checkedIDList, '{$summary}');}"))
);

render();
