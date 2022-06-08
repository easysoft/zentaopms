<?php
/**
 * The enableArchived file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL
 * @author      Runyu Zheng <zhengrunyu@easycorp.ltd>
 * @package     kanban
 * @version     $Id: enableArchived.html.php 935 2022-01-21 11:02:24Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->kanban->enableArchived;?></h2>
    </div>
    <form class='main-form form-ajax' method='post' enctype='multipart/form-data' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='w-90px'><?php echo $lang->kanban->archive;?></th>
          <td><?php echo nl2br(html::radio('archived', $lang->kanban->archiveList, $kanban->archived));?></td>
        </tr>
        <tr>
          <td colspan='2' class='text-center form-actions'>
            <?php echo html::submitButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>

