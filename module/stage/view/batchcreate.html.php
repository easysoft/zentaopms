<?php
/**
 * The batchCreate view of stage module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     stage
 * @version     $Id: batchCreate.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>.c-percent, .c-type {width:150px;}</style>
<div id="mainContent" class="main-content fade">
  <div class="main-header">
    <h2><?php echo $lang->stage->batchCreate;?></h2>
  </div>
  <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
    <table class="table table-form">
      <thead>
        <tr>
          <th class='c-id'><?php echo $lang->stage->id;?></th>
          <th class='required'><?php echo $lang->stage->name;?></th>
          <?php if(isset($config->setPercent) and $config->setPercent == 1):?>
          <th class='c-percent required'><?php echo $lang->stage->percent;?></th>
          <?php endif;?>
          <th class='c-type required'><?php echo $lang->stage->type;?></th>
        </tr>
      </thead>
      <tbody>
        <?php for($i = 1; $i <= 10; $i ++):?>
        <tr>
          <td><?php echo $i;?></td>
          <td><?php echo html::input("name[$i]", '',  "class='form-control'");?></td>
          <?php if(isset($config->setPercent) and $config->setPercent == 1):?>
          <td><?php echo html::input("percent[$i]", '',  "class='form-control'");?></td>
          <?php endif;?>
          <td><?php echo html::select("type[$i]", array('' => '') + $lang->stage->typeList, '',  "class='form-control chosen'");?></td>
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
