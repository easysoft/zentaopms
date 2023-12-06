<?php
/**
 * The edit view of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: edit.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='prefix'><?php echo html::icon($lang->icons['release']);?> <strong><?php echo $release->id;?></strong></span>
        <strong><?php echo html::a(inlink('view', "release=$release->id"), $release->name);?></strong>
        <small><?php echo $lang->arrow . ' ' . $lang->release->edit;?></small>
      </h2>
    </div>
    <form class='load-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform' enctype='multipart/form-data'>
      <table class='table table-form'>
        <tbody>
          <tr>
            <th class='w-120px'><?php echo $lang->release->name;?></th>
            <td><?php echo html::input('name', $release->name, "class='form-control' required");?></td>
            <?php if(!$product->shadow):?>
            <td>
              <?php $checked = !empty($release->marker) ? "checked='checked'" : '';?>
              <div id='markerBox' class='checkbox-primary'>
                <input id='marker' name='marker' value='1' type='checkbox' <?php echo $checked;?> />
                <label for='marker'><?php echo $lang->release->marker;?></label>
              </div>
            </td>
            <?php endif;?>
          </tr>
          <tr>
            <th><?php echo $lang->release->includedBuild;?></th>
            <td><?php echo html::select('build[]', $builds, $release->build, "class='form-control chosen' multiple data-placeholder='{$lang->build->placeholder->multipleSelect}'"); ?></td><td></td>
          </tr>
          <tr>
            <th><?php echo $lang->release->date;?></th>
            <td><?php echo html::input('date', $release->date, "class='form-control form-date' required");?></td><td></td>
          </tr>
          <tr>
            <th><?php echo $lang->release->status;?></th>
            <td><?php echo html::select('status', $lang->release->statusList, $release->status, "class='form-control'");?></td><td></td>
          </tr>
          <?php $this->printExtendFields($release, 'table');?>
          <tr>
            <th><?php echo $lang->release->desc;?></th>
            <td colspan='2'><?php echo html::textarea('desc', htmlSpecialString($release->desc), "rows=10 class='form-control kindeditor' hidefocus='true'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->release->mailto;?></th>
            <td colspan='2'>
              <div class="input-group">
                <?php echo html::select('mailto[]', $users, $release->mailto, "class='form-control picker-select' data-placeholder='{$lang->chooseUsersToMail}' multiple");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->files;?></th>
            <td colspan='2'><?php echo $this->fetch('file', 'buildform');?></td>
          </tr>
          <tr>
            <td colspan='3' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php echo html::backButton();?>
              <?php echo html::hidden('product', $release->product);?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
