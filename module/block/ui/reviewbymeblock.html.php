<?php
declare(strict_types=1);
/**
 * The reviewbyme block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang LI <liyang@easycorp.ltd>
 * @package     block
 * @link        https://www.zentao.net
 */

namespace zin;

$cols = $longBlock ? $config->block->reviewbyme->dtable->fieldList : $config->block->reviewbyme->dtable->short->fieldList;

$blockID = (string)$block->id;

$getBlockUrl = function(string $type) use($block): string
{
    return createLink('block', 'printBlock', "blockID={$block->id}&params=" . helper::safe64Encode("browseType={$type}"));
};

$getLinkProps = function(string $url) use($blockID): array
{
    return array
    (
        'href'         => $url,
        'data-url'     => $url,
        'data-on'      => 'click',
        'data-prevent' => 'true',
        'data-do'      => "loadBlock('{$blockID}', options.url)"
    );
};

$featureMenus = array();
foreach($lang->block->reviewbyme->featureBar as $type => $name)
{
    $url = $getBlockUrl($type);
    $featureMenus[] = li
    (
        setClass('nav-item'),
        a(setClass($type == $browseType ? 'active' : ''), set($getLinkProps($url)), $name)
    );
}

blockPanel
(
    setClass('reviewbyme-block list-block'),
    set::bodyClass('p-0'),
    to::heading(ul(setClass('nav'), $featureMenus)),
    dtable
    (
        setID('reviewbyme'),
        set::height(318),
        set::bordered(false),
        set::horzScrollbarPos('inside'),
        set::emptyTip($lang->block->emptyTip),
        set::cols(array_values($cols)),
        set::data(array_values($reviews))
    )
);
