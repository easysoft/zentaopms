<?php
declare(strict_types=1);
/**
* The relateObject file of custom module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Qiyu Xie <xieqiyu@chandao.com>
* @package     custom
* @link        https://www.zentao.net
*/

namespace zin;

jsVar('relateObjectList', $this->config->custom->relateObjectList);
jsVar('relationPairs', $relationPairs);
jsVar('objectID', $objectID);
jsVar('objectType', $objectType);

modalHeader(set::title($lang->custom->relateObject), set::titleClass('font-bold text-lg'));

featureBar
(
    inputGroup
    (
        set::className('mb-4 mr-4'),
        $lang->custom->relateObject,
        picker
        (
            set::name('relatedObjectType'),
            set::items($config->custom->relateObjectList),
            set::value($relatedObjectType),
            setClass('w-60'),
            set::onChange(jsRaw("(value) => switchObject(value)"))
        )
    )
);

$module = in_array($relatedObjectType, array('epic', 'requirement')) ? 'story' : $relatedObjectType;
searchForm
(
    set::module($module),
    set::simple(true),
    set::show(true)
);

dtable
(
    setID('relateObject'),
    set::cols($cols),
    set::data($objects),
    set::userMap($users),
    set::noNestedCheck(),
    set::sortLink(inlink('relateObject', "objectID={$objectID}&objectType={$objectType}&relatedObjectType={$relatedObjectType}&browseType={$browseType}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}")),
    set::checkable(true),
    set::footToolbar(array('items' => array(array
        (
            'text'      => $lang->save,
            'className' => 'ajax-btn batch-btn',
            'data-url'  => inlink('relateObject', "objectID=$objectID&objectType=$objectType&relatedObjectType=$relatedObjectType"),
            'btnProps'  => array('size' => 'sm', 'btnType' => 'secondary')
        ))
    )),
    set::footPager(usePager()),
    set::checkboxLabel($lang->selectAll),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::plugins(array('form'))
);
