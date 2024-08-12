<?php
declare(strict_types=1);
/**
 * The instruction view file of zanode module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     zanode
 * @link        https://www.zentao.net
 */
namespace zin;
div
(
    set::className('space-y-4'),
    div
    (
        h3($lang->zanode->instructionPage->title),
        div
        (
            set::className('leading-normal'),
            $lang->zanode->instructionPage->desc
        )
    ),
    div
    (
        h5($lang->zanode->instructionPage->imageInstruction),
        img(set::className('w-1/2'), set::src($lang->zanode->instructionPage->image))
    ),
    div
    (
        h5($lang->zanode->instructionPage->h1),
        div(set::className('leading-normal whitespace-pre-line'), $lang->zanode->instructionPage->h1Desc),
    ),
    div
    (
    )
);
