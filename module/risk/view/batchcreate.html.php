<?php
/**
 * The batchcreate of risk module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     risk
 * @version     $Id: batchcreate.html.php 4903 2020-09-04 09:13:59Z lyc $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="main-header">
    <h2><?php echo $lang->risk->batchCreate;?></h2>
  </div>
  <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
    <table class="table table-form">
      <thead>
        <tr>
          <th class='w-50px'><?php echo $lang->risk->id;?></th>
          <th class='required'><?php echo $lang->risk->name;?></th>
          <th class='w-200px'><?php echo $lang->risk->source;?></th>
          <th class='w-200px'><?php echo $lang->risk->category;?></th>
          <th class='w-200px'><?php echo $lang->risk->strategy;?></th>
        </tr>
      </thead>
      <tbody>
        <?php for($i = 1; $i <= 10; $i ++):?>
        <tr>
          <td><?php echo $i;?></td>
          <td><?php echo html::input("name[$i]", '',  "class='form-control'");?></td>
          <td><?php echo html::select("source[$i]", $lang->risk->sourceList,  '',  "class='form-control chosen'");?></td>
          <td><?php echo html::select("category[$i]", $lang->risk->categoryList, '',  "class='form-control chosen'");?></td>
          <td><?php echo html::select("strategy[$i]", $lang->risk->strategyList, '',  "class='form-control chosen'");?></td>
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
