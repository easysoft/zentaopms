<?php
/**
 * The header view file of credentials module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     credentials
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
            if($this->methodName !== 'browsebranch' && $this->methodName !== 'browsebuild' &&
                    strpos($this->methodName,'browse') > -1  && common::hasPriv($module, 'create')) {
                echo html::a(inlink('create'), "<i class='icon-plus'></i> {$lang->ci->create}",
                    '', "class='btn btn-primary'");
            } else if($this->methodName == 'browsebranch' || $this->methodName == 'browsebuild' || $this->methodName == 'viewbuildlogs') {
                echo html::backButton();
            }
            ?>
      </div>
    </div>
  </div>
</div>
