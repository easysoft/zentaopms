<?php
declare(strict_types=1);
/**
 * The editor view file of dir module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     editor
 * @version     $Id$
 * @link        https://www.zentao.net
 */
namespace zin;
set::zui(true);

panel
(
    setClass('bg-white'),
    set::title(zget($lang->editor->modules, $module, isset($lang->{$module}->common) ? $lang->{$module}->common : $module)),
    div
    (
        setClass('extend-content'),
        treeEditor(set(array('items' => $tree, 'canEdit' => false, 'canDelete' => false, 'canSplit' => false)))
    )
);
render('pagebase');
