<?php
/**
 * The edit view of stage module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     stage
 * @version     $Id: edit.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->stage->edit;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->stage->name;?></th>
            <td><?php echo html::input('name', $stage->name, "class='form-control'");?></td>
            <td></td>
            <td></td>
          </tr>
          <?php if(isset($config->setPercent) and $config->setPercent == 1):?>
          <tr>
            <th><?php echo $lang->stage->percent;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::input('percent', $stage->percent, "class='form-control'");?>
                <span class='input-group-addon'>%</span>
              </div>
            </td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->stage->type;?></th>
            <td><?php echo html::select('type', $lang->stage->typeList, $stage->type,  "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <td></td>
            <td colspan='3' class='form-actions'>
              <?php echo html::submitButton() . html::backButton();?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
<?php include '../../common/view/footer.html.php';?>
