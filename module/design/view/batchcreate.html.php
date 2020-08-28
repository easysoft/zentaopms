<?php
/**
 * The batchCreate view of design module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     design
 * @version     $Id: batchCreate.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="main-header"><h2><?php echo $lang->design->batchCreate;?></h2></div>
  <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
    <table class="table table-form">
      <thead>
        <tr>
          <th class='w-50px'><?php echo $lang->design->id;?></th>
          <th class='w-200px'><?php echo $lang->design->story;?></th>
          <th class='w-200px required'><?php echo $lang->design->type;?></th>
          <th class='required'><?php echo $lang->design->name;?></th>
        </tr>
      </thead>
      <tbody>
        <?php for($i = 1; $i <= 10; $i ++):?>
        <tr>
          <td><?php echo $i;?></td>
          <td><?php echo html::select("story[$i]", $stories, '', "class='form-control chosen'");?></td>
          <td><?php echo html::select("type[$i]", $lang->design->typeList, '', "class='form-control chosen'");?></td>
          <td><?php echo html::input("name[$i]", '', "class='form-control'");?></td>
        </tr>
        <?php endfor;?>
        <tr>
          <td colspan='4' class='form-actions text-center'>
            <?php echo html::submitButton() . html::backButton();?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
