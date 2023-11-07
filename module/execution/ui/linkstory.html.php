<?php
declare(strict_types=1);
/**
 * The link story view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     execution
 * @link        http://www.zentao.net
 */

namespace zin;

featureBar(li
(
    setClass('nav-item'),
    a
    (
        setClass('active'),
        $lang->execution->linkStory
    )
));

toolbar
(
    btn(setClass('btn primary'), set::icon('back'), set::url($browseLink), $lang->goback)
);

div(setID('searchFormPanel'), set('data-module', 'story'), searchToggle(set::open(true), set::module('story')));

$footToolbar['items'][] = array
(
    'type'  => 'btn-group',
    'items' => array(
        array('text' => $lang->save, 'className' => "btn size-sm batch-btn ajax-btn link-story-btn", 'btnType' => 'secondary', 'data-url' => commonModel::isTutorialMode() ? '' :  createLink('execution', 'linkStory', "objectID=$object->id")),
    )
);

$cols = $config->execution->linkStory->dtable->fieldList;
$cols['module']['map']  = $modules;
$cols['product']['map'] = $productPairs;
if($productType != 'normal')
{
    $cols['branch']['title'] = $lang->product->branchName[$productType];
}
else
{
    unset($cols['branch']);
}

jsVar('branchGroups', $branchGroups);

dtable
(
    set::groupDivider(true),
    set::userMap($users),
    set::cols($cols),
    set::data($allStories),
    set::checkable(true),
    set::footToolbar($footToolbar),
    set::onRenderCell(jsRaw('window.onRenderLinkStoryCell')),
    set::footPager(usePager(array(
        'linkCreator' => helper::createLink($object->type, 'linkStory', "objectID={$object->id}&browseType={$browseType}&param={$param}&recPerPage={recPerPage}&page={page}&extra=$extra")
    ))),
);

render();
