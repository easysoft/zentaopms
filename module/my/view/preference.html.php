<?php
/**
 * The preference view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     user
 * @version     $Id: editprofile.html.php 2605 2012-02-21 07:22:58Z wwccss $
 * @link        https://www.zentao.net
 */
?>
<style>
#submit{margin-top: 45px}
.chosen-container-single .chosen-single div b {top: 7px !important;}
</style>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><i class='icon-key'></i> <?php echo $lang->my->preference;?></h2>
  </div>
  <form method='post' target='hiddenwin' class='no-stash'>
    <table align='center' class='table table-form w-320px'>
      <tr>
        <th class='w-140px'><?php echo $lang->my->storyConcept;?></th>
        <td><?php echo html::select('URSR', $URSRList, $URSR, "class='form-control picker URSR'");?></td>
      </tr>
      <?php if($this->config->systemMode == 'ALM'):?>
      <tr>
        <th><?php echo $lang->my->programLink;?></th>
        <td><?php echo html::select('programLink', $lang->my->programLinkList, $programLink, "class='form-control picker programLink'");?></td>
      </tr>
      <?php endif;?>
      <tr>
        <th><?php echo $lang->my->productLink;?></th>
        <td><?php echo html::select('productLink', $lang->my->productLinkList, $productLink, "class='form-control picker productLink'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->my->projectLink;?></th>
        <td><?php echo html::select('projectLink', $lang->my->projectLinkList, $projectLink, "class='form-control picker projectLink'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->my->executionLink;?></th>
        <td><?php echo html::select('executionLink', $lang->my->executionLinkList, $executionLink, "class='form-control picker executionLink'");?></td>
      </tr>
      <tr>
        <td colspan='2' class='text-center form-actions'>
          <?php echo html::submitButton();?>
          <?php if($showTip == 'true'):?>
          <div>
            <p class='text-muted tip'><?php echo $lang->my->alert;?></p>
          </div>
          <?php endif;?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
