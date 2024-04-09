<?php
declare(strict_types=1);
/**
 * The browse view file of dataview module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     dataview
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('type', $type);
jsVar('groupTree',   json_encode($groupTree));
jsVar('originTable', json_encode($originTable));

featureBar();

toolbar
(
    item
    (
        set
        (
            array
            (
                'icon'  => 'export',
                'text'  => $lang->dataview->export,
                'class' => 'ghost pull-right',
                'url'   => createLink('dataview', 'export', "type=$type&tale=$selectedTable"),
                'data-toggle' => 'modal'
            )
        )
    ),
    item
    (
        set
        (
            array
            (
                'icon'  => 'plus',
                'text'  => $lang->dataview->create,
                'class' => 'primary pull-right',
                'url'   => createLink('dataview', 'create'),
            )
        )
    )
);

$settingLink = hasPriv('tree', 'browsegroup') ? createLink('tree', 'browsegroup', "dimensionID=0&groupID=0&type=dataview") : '';
sidebar
(
    tabs
    (
        tabpane
        (
            set::key('view'),
            set::title($lang->dataview->typeList['view']),
            set::active($type == 'view' ? true : false),
            moduleMenu(set(array
            (
                'titleShow'   => false,
                'showDisplay' => false,
                'modules'     => array(),
                'activeKey'   => $table,
                'settingLink' => $settingLink
            )))
        ),
        tabpane
        (
            set::key('table'),
            set::title($lang->dataview->typeList['table']),
            set::active($type == 'table' ? true : false),
            moduleMenu(set(array
            (
                'titleShow'   => false,
                'showDisplay' => false,
                'modules'     => array(),
                'activeKey'   => $table
            )))
        )
    )
);

$headingActions = array();
if($selectedTable && isset($dataview))
{
    $headingActions[] = hasPriv('dataview', 'query')  && $type == 'view' ? array('icon' => 'design', 'text' => $lang->dataview->design, 'class' => 'query-view ghost') : null;
    $headingActions[] = hasPriv('dataview', 'edit')   && $type == 'view' ? array('icon' => 'edit',   'text' => $lang->dataview->edit,   'class' => 'ghost', 'url' => createLink('dataview', 'edit',"id=$selectedTable"), 'data-toggle' => 'modal') : null;
    $headingActions[] = hasPriv('dataview', 'delete') && $type == 'view' ? array('icon' => 'trash',  'text' => $lang->dataview->delete, 'data-confirm' => $lang->dataview->confirmDelete,  'class' => 'ajax-submit query-delete ghost', 'url' => createLink('dataview', 'delete', "id=$selectedTable")) : null;
}

$viewCols = array();
foreach($fields as $key => $field)
{
    $fieldName = isset($dataview->fieldSettings->$key->name) ? $dataview->fieldSettings->$key->name : $key;
    if(!empty($dataview->langs))
    {
        $langs = json_decode($dataview->langs, true);
        if(!empty($langs)) $fieldName = $langs[$key][$clientLang] ? $langs[$key][$clientLang] : $fieldName;
    }

    if(strpos($field['type'], 'int') !== false) $field['type'] = 'int';
    $fieldType = zget($config->dataview->fieldTypes, $field['type'], 'text');

    $viewCols[$key]['name']  = $key;
    $viewCols[$key]['title'] = $fieldName;
    $viewCols[$key]['type']  = $fieldType;
}

$viewDatas = array();
foreach($data as $value)
{
    $dataRow = new stdclass();
    foreach($fields as $key => $field) $dataRow->$key = zget($value, $key, '');
    $viewDatas[] = $dataRow;
}

if(strpos($selectedTable, $this->config->db->prefix) === false) unset($config->dataview->schema->datable->fieldList['desc']);

$i = 1;
$schemaDatas = array();
foreach($fields as $key => $field)
{
    $schemaData = new stdclass();
    $schemaData->id     = $i;
    $schemaData->name   = $key;
    $schemaData->type   = $field['type'];
    $schemaData->length = isset($field['options']['max']) ? $field['options']['max'] : '';
    $schemaData->null   = $field['null'];
    if(strpos($selectedTable, $this->config->db->prefix) !== false) $schemaData->desc = $field['name'];

    $schemaDatas[] = $schemaData;

    $i++;
}

if(!$selectedTable)
{
    panel
    (
        setClass('dtable-empty-tip text-gray'),
        $lang->dataview->notSelect
    );
}
else
{
    panel
    (
        set::title($dataTitle . ($type == 'table' ? " {$selectedTable}" : '')),
        set::headingActions($headingActions),
        tabs
        (
            tabPane
            (
                set::key('data'),
                set::title($lang->dataview->data),
                dtable
                (
                    set::cols($viewCols),
                    set::data($viewDatas),
                    set::footPager(usePager(array('linkCreator' => createLink('dataview', 'browse', "type={$type}&table={$table}") . '?page={page}&recPerPage={recPerPage}')))
                )
            ),
            tabPane
            (
                set::key('schema'),
                set::title($lang->dataview->schema),
                dtable
                (
                    set::cols($config->dataview->schema->dtable->fieldList),
                    set::data($schemaDatas)
                )
            ),
            !empty($dataview) ? tabPane
            (
                set::key('details'),
                set::title($lang->dataview->details),
                tableData
                (
                    item
                    (
                        set::name($lang->dataview->name),
                        $dataview->name
                    ),
                    item
                    (
                        set::name($lang->dataview->code),
                        $dataview->code
                    ),
                    item
                    (
                        set::name($lang->dataview->view),
                        empty($dataview->sql) ? '' :$dataview->view
                    ),
                    item
                    (
                        set::name($lang->dataview->group),
                        zget($groups, $dataview->group)
                    ),
                    item
                    (
                        set::name($lang->dataview->sql),
                        $dataview->sql
                    )
                )
            ) : null
        )
    );
}

render();
