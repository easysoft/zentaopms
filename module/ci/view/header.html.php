<?php
/**
 * The header view file of credential module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     credential
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'><?php common::printAdminSubMenu('sso');?></div>
  <div class='btn-toolbar pull-right'>
    <div class='btn-group'>
      <div class='btn-group' id='createActionMenu'>
        <?php
            if(strpos($this->methodName,'browse') > -1  && (true || common::hasPriv($module, 'create'))) {
                echo html::a(inlink('create' . $module),
                    "<i class='icon-plus'></i> {$lang->ci->create}{$lang->ci->subModules[$module]}",
                    '', "class='btn btn-primary'");
            }?>
      </div>
    </div>
  </div>
</div>
