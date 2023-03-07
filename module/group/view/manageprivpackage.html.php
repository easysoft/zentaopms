<?php
/**
 * The managePrivPackage view file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     group
 * @version     $Id: manageprivpackage.html.php 4769 2023-03-07 10:09:21Z liumengyi $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class='clearfix'>
  <div class="btn-toolbar pull-left">
    <?php common::printBack(inlink('permissionedit', ''), 'btn btn-primary');?>
    <div class="divider"></div>
    <div class="page-title">
      <span class="text" title='<?php echo $lang->group->managePrivPackage;?>'><?php echo $lang->group->managePrivPackage;?></span>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('group', 'createPrivPackage')) echo html::a($this->createLink('group', 'createPrivPackage', '', '', true), $lang->group->createPrivPackage, '', 'class="btn btn-primary iframe" data-width="500"');?>
  </div>
</div>
<div id='mainContent' class='main-table'>
</div>
<?php include '../../common/view/footer.html.php';?>

