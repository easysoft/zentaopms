<?php
declare(strict_types=1);
/**
 * The moveDoc view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@chandao.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->doc->moveDocAction));

$libType    = $space == 'mine' ? 'mine' : 'custom';
$defaultAcl = $doc->acl;
if($libType == 'mine') $defaultAcl = 'private';
if($libType == 'mine') $this->lang->doc->aclList = $this->lang->doclib->mySpaceAclList;
if($libType == 'custom' && $doc->lib != $libID) $defaultAcl = 'open';

jsVar('space', $space);
jsVar('libID', $libID);
jsVar('docID', $docID);
formPanel
(
    formGroup
    (
        set::width('5/6'),
        set::label($lang->doc->space),
        set::required(true),
        set::control(array('control' => "picker", 'name' => 'space', 'items' => $spaces, 'value' => $space))
    ),
    formGroup
    (
        set::width('5/6'),
        set::label($lang->doc->lib),
        set::required(true),
        set::control(array('control' => "picker", 'name' => 'lib', 'items' => $libPairs, 'value' => $libID))
    ),
    formGroup
    (
        set::width('5/6'),
        set::label($lang->doc->module),
        set::control(array('control' => "picker", 'name' => 'module', 'items' => $optionMenu, 'value' => $doc->module, 'required' => true))
    ),
);
