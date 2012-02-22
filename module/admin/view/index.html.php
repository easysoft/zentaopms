<?php
/**
 * The view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: view.html.php 2568 2012-02-09 06:56:35Z shiyangyangwork@yahoo.cn $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<p class='strong mt-10px'>
  <?php 
  echo sprintf($lang->admin->info->version, $config->version);
  if($bind) echo sprintf($lang->admin->info->account, '<span class="red">' . $account . '</span>');
  echo $lang->admin->info->links;
  ?>
</p>
<?php include '../../misc/view/links.html.php';?>
<?php if(!$bind and !$ignore):?>
<div id="notice">
  <div class="f-left"><?php echo sprintf($lang->admin->notice->register, html::a(inlink('register'), $lang->admin->register->click));?></div>
  <div class="f-right"><?php echo html::a(inlink('ignore'), $lang->admin->notice->ignore, 'hiddenwin');?></div>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
