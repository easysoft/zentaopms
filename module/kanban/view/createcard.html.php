<?php
/**
 * The create card view of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Shujie Tian<tianshujie@cnezsoft.com>
 * @package     kanban 
 * @version     $Id: createcard.html.php 5090 2021-12-13 13:49:24Z tainshujie@cnezsoft.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->kanbancard->create;?></h2>
    </div>
    <form class='main-form form-ajax' method='post' enctype='multipart/form-data' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->kanbancard->name;?></th>
          <td><?php echo html::input('name', '', "class='form-control'");?></td><td></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo html::commonButton($lang->cancel, "data-dismiss='modal'", 'btn btn-wide');?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
