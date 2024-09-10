<?php
declare(strict_types=1);
/**
 * The app view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Hao<sunhao@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

zui::docApp
(
    set::_class('shadow rounded ring canvas'),
    set::_style(array('height' => 'calc(100vh - 72px)')),
    set::spaceType($type),
    set::spaceID($spaceID),
    set::libID($libID),
    set::moduleID($moduleID),
    set::docID($docID),
    set::docMode($docMode),
    set::fetcher(createLink('doc', 'ajaxGetSpaceData', 'type={spaceType}&spaceID={spaceID}')),
    set::docFetcher(createLink('doc', 'ajaxGetDoc', 'docID={docID}')),
    set::width('100%'),
    set::height('100%')
);

/* Modify navbar. 修改二级导航。 */
query('#navbar nav')->each(function($node) use ($type)
{
    $items     = $node->prop('items');
    $idTypeMap = array('my' => 'mine', 'team' => 'custom', 'project' => 'project', 'product' => 'product');

    foreach($items as &$item)
    {
        if(empty($item['data-id']) || empty($idTypeMap[$item['data-id']])) continue;

        $itemType = $idTypeMap[$item['data-id']];
        $item['active'] = $type === $itemType;
        $item['url']    = createLink('doc', 'app', 'type=' . $itemType);
    }

    $node->setProp('items', $items);
});
