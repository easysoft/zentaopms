<?php
/**
 * The html template file of setstoryconcept method of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Guangming Sun<sunguangming@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: setstoryconcept.html.php 4129 2020-09-01 01:58:14Z sgm $
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <form class="load-indicator main-form form-ajax" method='post'>
      <div class='modal-body'>
        <table class='table table-form'>
          <tr id='URSRName'>
            <th class='w-120px'><?php echo $lang->custom->waterfall->URSRName;?></th>
            <td><?php echo html::select('URSRCommon', $lang->custom->URSRList, zget($config->custom, 'URSRName', 1), "class='form-control chosen'");?></td>
            <td><?php echo html::checkbox('URSRCustom', $lang->custom->common, "class='form-control'");?></td>
            <td></td><td></td><td></td>
          </tr>
          <tr class='hidden' id='customURSR'>
            <th></th>
            <td><?php echo html::input('URName', '', "class='form-control' placeholder={$lang->custom->URTips}");?></td>
            <td><?php echo html::input('SRName', '', "class='form-control' placeholder={$lang->custom->SRTips}");?></td>
          </tr>
          <tr>
            <th></th>
            <td class='text-left'><?php echo html::submitButton();?></td>
          </tr>
        </table>
      </div>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
