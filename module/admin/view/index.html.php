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
<?php if(!$login):?>
<?php if(!$ignore):?>
<div id="notice">
  <div class="f-left">
  <?php echo sprintf($lang->admin->notice->join, html::a(inlink('register'), $lang->admin->register->join), html::a(inlink('login'), $lang->admin->login->join));?>
  </div>
  <div class="f-right">
    <?php echo html::a(inlink('ignoreNotice'), $lang->admin->notice->ignore);?>
  </div>
</div>
<?php endif;?>
<?php endif;?>
<table align='center' class='table-1'>
<caption><?php echo $lang->admin->info->caption;?></caption>
  <tr>
    <th class='rowhead'><?php echo $lang->admin->info->currentVersion;?></th>
	<td><?php echo $config->version;?></td>
  </tr>
  <tr>
    <th class='rowhead'><?php echo $lang->admin->info->urls;?></th>
	<td class="content">
	  <p><?php echo html::a($config->url->community, $lang->admin->info->community, '_blank');?></p>
	  <p><?php echo html::a($config->url->ask, $lang->admin->info->ask, '_blank');?></p>
	  <p><?php echo html::a($config->url->document, $lang->admin->info->document, '_blank');?></p>
	  <p><?php echo html::a($config->url->feedback, $lang->admin->info->feedback, '_blank');?></p>
	  <p><?php echo html::a($config->url->faq, $lang->admin->info->faq, '_blank');?></p>
	  <p><?php echo html::a($config->url->extension, $lang->admin->info->extension, '_blank');?></p>
	  <p><?php echo html::a($config->url->donation, $lang->admin->info->donation, '_blank');?></p>
	  <p><?php echo html::a($config->url->service, $lang->admin->info->service, '_blank');?></p>
	</td>
  </tr>
  <?php if($login):?>
  <tr>
    <th class='rowhead'><?php echo $lang->admin->info->account;?></th>
    <td><?php echo $account;?></td>
  </tr>
  <?php endif;?>
</table>
<?php include '../../common/view/footer.html.php';?>
