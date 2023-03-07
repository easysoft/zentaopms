<?php
/**
 * The browse view file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: browse.html.php 4769 2013-05-05 07:24:21Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class='flex-space-center'>
  <div class="btn-toobar">
    <?php echo html::backButton('<i class="icon icon-angle-left"></i>' . $lang->goback, '', '');?>
    <a href="#" class="btn btn-link btn-active-text">所有权限</a>
  </div>
  <div class="btn-toolbar">
    <div class="btn-group">
    <?php echo html::a($this->createLink('', '', '', '', false), '<i class="icon icon-list"></i>', '', 'class="btn"');?>
    <?php echo html::a($this->createLink('', '', '', '', false), '<i class="icon icon-cards-view"></i>', '', 'class="btn"');?>
    </div>
    <a href="#" class="btn btn-primary"><?php echo $lang->group->addPriv?></a>
    <?php if(common::hasPriv('group', 'managePrivPackage')) echo html::a($this->createLink('group', 'managePrivPackage', ''), $lang->group->managePrivPackage, '', 'class="btn btn-primary"');?>
    <a href="#" class="btn btn-primary"><?php echo $lang->group->batchSetDependency?></a>
  </div>
</div>
<div id='mainContent' class='main-table'>
</div>
<?php include '../../common/view/footer.html.php';?>

