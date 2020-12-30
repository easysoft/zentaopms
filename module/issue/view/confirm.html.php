<?php
/**
 * The cancel of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     issue
 * @version     $Id: complete.html.php 935 2010-07-06 07:49:24Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $issue->id;?></span>
        <?php echo "<span title='$issue->title'>" . $issue->title . '</span>';?>
      </div>
    </div>
    <div class="modal-body" style="min-height: 282px; overflow: auto;">
    <form method='post' enctype='multipart/form-data' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->issue->title;?></th>
          <td colspan='2'><?php echo $issue->title;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->issue->desc;?></th>
          <td colspan='2'><?php echo html::textarea('desc', $issue->desc, 'row="6"');?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'>
            <?php echo html::submitButton();?>
          </td>
        </tr>
      </table>
    </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
