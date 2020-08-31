<?php
/**
 * The create view of design module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     design
 * @version     $Id: create.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->design->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <?php if($program->category == 'multiple'):?>
          <tr>
            <th class='w-120px'><?php echo $lang->design->product;?></th>
            <td><?php echo html::select('product', $products, $productID, "class='form-control chosen'");?></td>
            <td></td>
          </tr>
          <?php endif;?>
          <?php if($program->category == 'single') echo html::hidden('product', $productID);?>
          <tr>
            <th class='w-120px'><?php echo $lang->design->story;?></th>
            <td><?php echo html::select('story', empty($stories) ? '' : $stories, '', "class='form-control chosen'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->design->type;?></th>
            <td class="required"><?php echo html::select('type', $lang->design->typeList, '', "class='form-control chosen'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->design->name;?></th>
            <td class="required"><?php echo html::input('name', '', "class='form-control'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->design->desc;?></th>
            <td colspan='2'><?php echo html::textarea('desc', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->design->file;?></th>
            <td colspan='2'><?php echo $this->fetch('file', 'buildform', 'fileCount=1&percent=0.85');?></td>
          </tr>
          <tr>
            <td colspan='3' class='text-center form-actions'><?php echo html::submitButton() . html::backButton();?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
