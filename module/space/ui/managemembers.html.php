<?php
declare(strict_types=1);
/**
* The UI file of space module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yang Li <liyang@chandao.com>
* @package     space
* @link        https://www.zentao.net
*/

namespace zin;
jsVar('hasAccessUsers', array_keys($members));
dropmenu
(
    set::module('space'),
    set::tab('space'),
    set::objectID($spaceID),
    set::url(createLink('space', 'ajaxGetDropMenu', "spaceID=$spaceID&module={$app->rawModule}&method={$app->rawMethod}"))
);
/* Generate fields for the batch create form. */
$fnGenerateFields = function() use ($lang, $fields, $space)
{
    /* Generate fields with the appropriate properties. */
    $items   = array();
    $items[] = array('name' => 'id', 'label' => $lang->idAB, 'control' => 'index', 'width' => '32px');

    $cols = array_merge($items, array_map(function($name, $field)
    {
        $field['name'] = $name;
        if(!empty($field['options'])) $field['items'] = $field['options'];
        if(!empty($field['default'])) $field['value'] = $field['default'];
        unset($field['options']);

        return $field;
    }, array_keys($fields), array_values($fields)));

    foreach($cols as $key => &$col)
    {
        $colName = $col['name'];
        if(!empty($space) && $space->auth == 'extend' && $colName == 'group')
        {
            unset($cols[$key]);
            continue;
        }
        if($colName == 'account')
        {
            $col['control']['menu'] = jsRaw('{getItem(item) {return getMenu(item)}}');
        }
    }

    return $cols;
};

if(!empty($members))
{
    foreach($members as &$member)
    {
        $member->group = array_keys($member->group);
        $member->repo  = array_keys($member->repo);
    }
}

formBatchPanel
(
    setID('dataform'),
    set::data(array_values($members)),
    set::model('edit'),
    set::title($title),
    set::items($fnGenerateFields()),
    set::onRenderRow(jsRaw('onRenderRow')),
    set::actions(array
    (
        array('text' => $lang->save,   'data-status' => 'active', 'class' => 'primary', 'btnType' => 'submit'),
        array('text' => $lang->goback, 'data-back'   => 'APP',    'class' => 'open-url')
    )),
);
