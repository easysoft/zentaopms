<?php
/**
 * The batchCreate view of design module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     design
 * @version     $Id: batchcreate.html.php 4903 2020-09-02 09:32:59Z tianshujie@easycorp.ltd $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('type', $type);?>
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
          <th><?php echo $lang->design->desc;?></th>
        </tr>
      </thead>
      <tbody>
        <?php for($i = 1; $i <= 10; $i ++):?>
        <tr>
          <td><?php echo $i;?></td>
          <td><?php echo html::select("story[$i]", $stories, '', "class='form-control chosen'");?></td>
          <td><?php echo html::select("type[$i]", $typeList, '', "class='form-control chosen'");?></td>
          <td><?php echo html::input("name[$i]", '', "class='form-control'");?></td>
          <td><?php echo html::textarea("desc[$i]", '', "class='form-control autosize'");?></td>
        </tr>
        <?php endfor;?>
        <tr>
          <td colspan='5' class='form-actions text-center'>
            <?php echo html::submitButton() . html::backButton();?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
