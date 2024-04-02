<?php
declare(strict_types=1);
/**
* The zenatoDynamic block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

$app->loadModuleConfig('admin');
$dynamicCards = array();
foreach($dynamics as $key => $dynamic)
{
    if($key >= 2) break;
    $dynamicCards[] = a
    (
        set('href', $dynamic->link),
        set('target', '_blank'),
        div
        (
            setClass('px-1 py-3'),
            div
            (
                setClass('zentao-dynamic-head font-semibold'),
                span(set::className('zentao-dynamic-background text-sm rounded py-1 px-2'), $dynamic->title),
                span(set::className('zentao-dynamic-title text-sm rounded py-1 px-2'), $dynamic->title),
                span(set::className('zentao-dynamic-label text-gray-950 ml-1'), $dynamic->label)
            ),
            div
            (
                setClass('clip text-gray-700 font-normal mt-7'),
                $dynamic->content
            )
        )
    );
}

blockPanel
(
    set::className('zentaoDynamic-block'),
    set::moreLink(array('url' => $config->admin->dynamicURL, 'target' => '_blank')),
    div($dynamicCards)
);
