<?php
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: view.html.php 2568 2012-02-09 06:56:35Z shiyangyangwork@yahoo.cn $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php if(!$bind and !$ignore and common::hasPriv('admin', 'register')):?>
<div id="notice" class='alert alert-success'>
  <?php echo html::a(inlink('ignore'), '<i class="icon-remove"></i> ' . $lang->admin->notice->ignore, 'hiddenwin', 'class="close" data-dismiss="alert" style="font-size: 12px"');?>
  <div class="content"><i class='icon-info-sign'></i> <?php echo sprintf($lang->admin->notice->register, html::a(inlink('register'), $lang->admin->register->click, '', 'class="alert-link"'));?></div>
</div>
<?php endif;?>

<div id='titlebar'>
  <div class='heading'>
    <?php 
    echo sprintf($lang->admin->info->version, $config->version);
    if($bind) echo sprintf($lang->admin->info->account, '<span class="red">' . $account . '</span>');
    echo $lang->admin->info->links;
    ?>
  </div>
</div>
<?php include '../../misc/view/links.html.php';?>
<?php include '../../common/view/footer.html.php';?>
