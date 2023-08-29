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
            set('class', 'shadow-sm p-3 ' . ($key > 0 ? 'mt-4' : '')),
            div
            (
                label(set('class', $key > 0 ? 'special' : 'secondary'), $dynamic->title),
                label(set('class', 'text-black font-bold ml-1 ' . ($key > 0 ? 'special-pale' : 'secondary-pale')), $dynamic->label),
                $dynamic->linklabel ? span
                (
                    setStyle(array('float' => 'right')),
                    $dynamic->linklabel,
                    icon('arrow-right')
                ) : null
            ),
            div
            (
                set('class', 'ellipsis text-black mt-4'),
                $dynamic->content
            )
        )
    );
}
panel
(
    set::className('zentaoDynamic-block'),
    set::bodyClass('p-0 px-4'),
    set::title($block->title),
    to::headingActions
    (
        a
        (
            set('class', 'text-gray'),
            set('href', $config->admin->dynamicURL),
            set('target', '__blank'),
            $lang->more,
            icon('caret-right')
        )
    ),
    div($dynamicCards)
);

render();
