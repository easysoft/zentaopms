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
  <form class="load-indicator main-form form-ajax" method='post'>
    <div class='modal-body'>
      <table class='table table-form'>
        <tr class='text-center'>
          <td class='w-200px'><strong><?php echo $lang->custom->URConcept;?></strong></th>
          <td class='w-200px'><strong><?php echo $lang->custom->SRConcept;?></strong></th>
          <td></td><td></td>
        </tr>
        <tr>
          <td><?php echo html::input('URName', $URSR->URName, "class='form-control'");?></td>
          <td><?php echo html::input('SRName', $URSR->SRName, "class='form-control'");?></td>
        </tr>
        <tr>
          <th></th>
          <td class='text-center' colspan='2'><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
