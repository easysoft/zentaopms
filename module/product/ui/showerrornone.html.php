<?php
declare(strict_types=1);
/**
 * The none view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      dingguodong <dingguodong@easycorp.ltd>
 * @package     product
 * @link        https://www.zentao.net
 */
namespace zin;

if($this->app->tab == 'project')
{
    $link = createLink('project', 'manageProducts', "projectID=$objectID");
    $tab  = 'project';
    $text = $lang->project->manageProducts;
}
elseif($this->app->tab == 'execution')
{
    $link = createLink('execution', 'manageProducts', "executionID=$objectID");
    $tab  = 'execution';
    $text = $lang->execution->manageProducts;
}
else
{
    $link = createLink('product', 'create', "programID=0&extra=&from=$moduleName");
    $tab  =  'product';
    $text = $lang->product->create;
}

div
(
    setClass('table-empty-tip bg-canvas h-40 flex items-center justify-center'),
    span
    (
        setClass('text-muted'),
        $lang->product->noProduct
    ),
    a
    (
        setClass('btn primary-pale'),
        set::href($link),
        setData('app', $tab),
        icon('plus'),
        $text
    )
);

render();
