<?php
/**
 * The assignto of risk module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     risk
 * @version     $Id: assignto.html.php 4903 2020-09-04 09:32:59Z lyc $
 * @link        http://www.zentao.net
 */
?>
<?php include "../../common/view/header.html.php";?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class="center-block">
    <div class='main-header'>
      <h2>
        <span class='prefix label-id'><strong><?php echo $risk->id;?></strong></span>
        <?php echo "<span title='$risk->name'>" . $risk->name . '</span>';?>
      </h2>
    </div> 
    <form method='post' enctype='multipart/form-data' target='hiddenwin'>
      <table class='table table-form'>
        <tbody>
          <tr>
            <th class='w-100px'><?php echo $lang->risk->assignedTo;?></th>
            <td><?php echo html::select('assignedTo', $users, $risk->assignedTo, "class='form-control chosen'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->comment;?></th>
            <td colspan='2'><?php echo html::textarea('comment', '', "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
          </tr>
          <tr>
            <td class='text-center form-actions' colspan='3'><?php echo html::submitButton(); ?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include "../../common/view/footer.html.php";?>
