<?php
/**
 * The preference view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     user
 * @version     $Id: editprofile.html.php 2605 2012-02-21 07:22:58Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php if($preferenceSetted):?>
<style>
#submit{margin-top: 45px}
.chosen-container-single .chosen-single div b {top: 7px !important;}
</style>
<?php include '../../common/view/header.html.php';?>
<?php else:?>
<?php include '../../common/view/header.lite.html.php';?>
<style>
html,body {height: 100%;}
.table {width: 80%;}
.container {height: 100%; display: flex; align-items: center;}
</style>
<?php endif;?>
<?php if($preferenceSetted):?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><i class='icon-key'></i> <?php echo $lang->my->preference;?></h2>
  </div>
<?php else:?>
<div class='container'>
  <div class='modal-dialog'>
    <div class='modal-header'>
      <strong><?php echo $lang->my->preference;?></strong>
    </div>
<?php endif;?>
  <form method='post' target='hiddenwin'>
    <table align='center' class='table table-form w-320px'>
      <tr>
        <th class='w-120px'><?php echo $lang->my->storyConcept;?></th>
        <td><?php echo html::select('URSR', $URSRList, $URSR, "class='form-control chosen'");?></td>
      </tr>
      <?php if($this->config->systemMode == 'new'):?>
      <tr>
        <th><?php echo $lang->my->programLink;?></th>
        <td><?php echo html::select('programLink', $lang->my->programLinkList, $programLink, "class='form-control chosen'");?></td>
      </tr>
      <?php endif;?>
      <tr>
        <th><?php echo $lang->my->productLink;?></th>
        <td><?php echo html::select('productLink', $lang->my->productLinkList, $productLink, "class='form-control chosen'");?></td>
      </tr>
      <?php if($this->config->systemMode == 'new'):?>
      <tr>
        <th><?php echo $lang->my->projectLink;?></th>
        <td><?php echo html::select('projectLink', $lang->my->projectLinkList, $projectLink, "class='form-control chosen'");?></td>
      </tr>
      <?php else:?>
      <tr>
        <th><?php echo $lang->my->executionLink;?></th>
        <td><?php echo html::select('executionLink', $lang->my->executionLinkList, 'execution-task', "class='form-control chosen'");?></td>
      </tr>
      <?php endif;?>
<!--
      <tr>
        <th><?php echo $lang->my->executionLink;?></th>
        <td><?php echo html::select('executionLink', $lang->my->executionLinkList, $executionLink, "class='form-control chosen'");?></td>
      </tr>
-->
      <tr>
        <td colspan='2' class='text-center form-actions'><?php echo html::submitButton();?></td>
      </tr>
    </table>
  </form>
</div>
<?php if($preferenceSetted):?>
<?php include '../../common/view/footer.html.php';?>
<?php else:?>
<?php include '../../common/view/footer.lite.html.php';?>
<?php endif;?>
