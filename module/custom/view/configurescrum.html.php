<?php
/**
 * The html template file of configureScrum method of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Guangming Sun<sunguangming@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: configurescrum.html.php 4129 2020-09-01 01:58:14Z sgm $
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a(inlink('configurescrum'), "<span class='text'>" . $lang->custom->concept . '</span>', '', "class='btn btn-link btn-active-text concept'");?>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <form id='ajaxForm' class='form-ajax' method='post'>
    <div class='modal-body'>
      <table class='table table-form'>
        <tr>
          <th class='w-160px'><?php echo $lang->custom->scrum->setConcept;?> </th>
          <td colspan='3'><?php echo html::radio('productProject', $lang->custom->productProject->relation, zget($this->config->custom, 'productProject', '0_0'))?></td>
          <td></td><td></td>
        </tr>
        <tr>
          <th> <?php echo $lang->custom->waterfall->URAndSR;?> </th>
          <td> <?php echo html::radio('URAndSR', $lang->custom->waterfallOptions->URAndSR, zget($this->config->custom, 'URAndSR', '0'));?> </td>
        </tr>
        <?php $hidden = zget($this->config->custom, 'URAndSR', 0) == 0 ? 'hidden' : '';?>
        <tr class="<?php echo $hidden;?>" id='URSRName'>
          <th><?php echo $lang->custom->waterfall->URSRName;?></th>
          <td><?php echo html::select('URSRCommon', $lang->custom->URSRList, zget($config->custom, 'URSRName', 1), "class='form-control chosen'");?></td>
          <td><?php echo html::checkbox('URSRCustom', $lang->custom->common, "class='form-control'");?></td>
        </tr>
        <tr class='hidden' id='customURSR'>
          <th></th>
          <td><?php echo html::input('URName', '', "class='form-control' placeholder={$lang->custom->URTips}");?></td>
          <td><?php echo html::input('SRName', '', "class='form-control' placeholder={$lang->custom->SRTips}");?></td>
        </tr>
        <tr>
          <td class='text-right'><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
