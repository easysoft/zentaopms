<?php
declare(strict_types=1);
/**
* The release block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

jsVar('markerTitle', $lang->release->marker);

if(!$longBlock)
{
    unset($config->block->release->dtable->fieldList['id']);
    unset($config->block->release->dtable->fieldList['productName']);
    unset($config->block->release->dtable->fieldList['buildName']);
}

$this->config->block->release->dtable->fieldList['build']['map'] = $builds;

panel
(
    setClass('p-0'),
    set::title($block->title),
    set::bodyClass('p-0 no-shadow border-t'),
    dtable
    (
        set::height(318),
        set::fixedLeftWidth($longBlock ? '0.33' : '0.5'),
        set::onRenderCell(jsRaw('window.onRenderReleaseNameCell')),
        set::cols(array_values($config->block->release->dtable->fieldList)),
        set::data(array_values($releases))
    )
);

render();
