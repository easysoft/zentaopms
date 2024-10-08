<?php
declare(strict_types=1);
/**
 * The createButton view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

$createButton = $emptyCreateBtn = null;

$docModel = $this->doc;
$buildCreateBtn = function($typeID, $libID, $moduleID) use($lib, $docModel, $type)
{
    global $app, $config, $lang;
    if($lib->type == 'custom')
    {
        $typeID = $lib->parent > 0 ? $lib->parent : $lib->id;
        if($lib->parent == 0) $libID = $docModel->dao->select('*')->from(TABLE_DOCLIB)->where('parent')->eq($typeID)->andWhere('deleted')->eq('0')->orderBy('id_asc')->limit(1)->fetch('id');
    }

    $buttonItems = array();
    foreach($lang->doc->createList as $typeKey => $typeName)
    {
        $method  = 'create';
        $docType = zget($config->doc->iconList, $typeKey);
        $params  = "objectType={$type}&objectID={$typeID}&libID={$libID}&moduleID={$moduleID}&type={$typeKey}";
        if($typeKey == 'template' && $config->edition == 'max') $params = "objectType={$type}&objectID={$typeID}&libID={$libID}&moduleID={$moduleID}&type=html";
        if($typeKey == 'attachment') $method = 'uploadDocs';

        $buttonItems[] = array
        (
            'content'     => array('html' => "<img class='mr-2' src='static/svg/{$docType}.svg'/>{$typeName}", 'class' => 'flex w-full'),
            'url'         => createLink('doc', $method, $params),
            'data-app'    => $app->tab,
            'data-toggle' => strpos($this->config->doc->officeTypes, $typeKey) !== false ? 'modal' : ''
        );

        if($typeKey == 'template') $buttonItems[] = array('type' => 'divider');
    }

    return btngroup
    (
        btn
        (
            setClass('btn primary ml-2 doc-create-btn'),
            set::icon('plus'),
            set::url(createLink('doc', 'create', "objectType={$type}&objectID={$typeID}&libID={$libID}&moduleID={$moduleID}&type=html")),
            set('data-app', $app->tab),
            $lang->doc->create
        ),
        dropdown
        (
            btn(setClass('btn primary dropdown-toggle'),
            setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::placement('bottom-end'),
            set::items($buttonItems)
        )
    );
};

$createButton = $buildCreateBtn($objectID, $libID, $moduleID);
