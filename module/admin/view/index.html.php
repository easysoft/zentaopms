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
<table align='center' class='table-1'>
<caption><?php echo $lang->admin->info->caption;?></caption>
  <tr>
    <td id="info">
	  <h3>
	  <?php echo sprintf($lang->admin->info->version, $config->version);
      	  if(isset($latestRelease) and (version_compare($latestRelease->version, $config->version) > 0))
		  {
        	  echo sprintf($lang->admin->info->latest, html::a($latestRelease->url, $latestRelease->version, '_blank'));
		  }
      	  else
		  {
        	  echo $lang->admin->info->new;
		  }
		  echo $lang->admin->info->links;
	  ?>
      </h3>
  	  <li><?php echo html::a($config->url->community, $lang->admin->info->community, '_blank') .$lang->admin->desc->community ;?></li>
  	  <li><?php echo html::a($config->url->ask, $lang->admin->info->ask, '_blank') . $lang->admin->desc->ask;?></li>
      <li><?php echo html::a($config->url->document, $lang->admin->info->document, '_blank') . $lang->admin->desc->document;?></li>
      <li><?php echo html::a($config->url->feedback, $lang->admin->info->feedback, '_blank') . $lang->admin->desc->feedback;?></li>
      <li><?php echo html::a($config->url->faq, $lang->admin->info->faq, '_blank') . $lang->admin->desc->faq;?></li>
      <li><?php echo html::a($config->url->extension, $lang->admin->info->extension, '_blank') . $lang->admin->desc->extension;?></li>
      <li><?php echo html::a($config->url->donation, $lang->admin->info->donation, '_blank') . $lang->admin->desc->donation;?></li>
      <li><?php echo html::a($config->url->service, $lang->admin->info->service, '_blank') . $lang->admin->desc->service;?></li>
      <?php if($login):?>
        <p><?php echo $lang->admin->info->account . $account;?></p>
      <?php endif;?>
    </td>
    <!--th class='rowhead w-100px'><?php echo $lang->admin->info->currentVersion;?></th>
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
</tr-->
</tr>
</table>
<?php if(!$login):?>
<?php if(!$ignore):?>
<div id="notice">
  <div class="f-left">
  <?php echo sprintf($lang->admin->notice->join, html::a(inlink('register'), $lang->admin->register->join), html::a(inlink('login'), $lang->admin->login->join));?>
  </div>
  <div class="f-right">
    <?php echo html::a(inlink('ignoreNotice'), $lang->admin->notice->ignore, 'hiddenwin');?>
  </div>
</div>
<?php endif;?>
<?php endif;?>

<?php include '../../common/view/footer.html.php';?>
