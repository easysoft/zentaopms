<?php
/**
 * The create view of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('projectID', $projectID);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->release->create;?></h2>
    </div>
    <form class='load-indicator main-form form-ajax' id='dataform' method='post' enctype='multipart/form-data'>
      <table class='table table-form'>
        <tbody>
          <tr>
            <th class='w-120px'><?php echo $lang->release->name;?></th>
            <td><?php echo html::input('name', '', "class='form-control' required");?></td>
            <td>
              <?php if(!$product->shadow):?>
              <div id='markerBox' class='checkbox-primary'>
                <input id='marker' name='marker' value='1' type='checkbox' />
                <label for='marker'><?php echo $lang->release->marker;?></label>
              </div>
              <?php endif;?>
              <?php if($lastRelease) echo '(' . $lang->release->last . ': ' . $lastRelease->name . ')';?>
            </td>
          </tr>
          <?php if($product->shadow):?>
          <?php echo html::hidden('product', $product->id);?>
          <?php else:?>
          <tr>
            <th><?php echo $lang->release->product;?></th>
            <td><?php echo html::select('product', $products, $product->id, "onchange='loadBuilds()' class='form-control chosen'");?></td>
            <td></td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->release->includedBuild;?></th>
            <td id='buildBox'><?php echo html::select('build[]', $builds, '', "class='form-control picker-select' multiple data-placeholder='{$lang->build->placeholder->multipleSelect}'");?></td>
            <td>
              <div class="checkbox-primary">
                <input type="checkbox" name="sync" value="1" id="sync" checked>
                <label for="sync"><?php echo $lang->release->syncFromBuilds?></label>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->release->date;?></th>
            <td><?php echo html::input('date', helper::today(), "class='form-control form-date' required");?></td><td></td>
          </tr>
          <tr class='hide'>
            <th><?php echo $lang->release->status;?></th>
            <td><?php echo html::hidden('status', 'normal', "disabled");?></td>
            <td></td>
          </tr>
          <?php $this->printExtendFields('', 'table');?>
          <tr>
            <th><?php echo $lang->release->desc;?></th>
            <td colspan='2'><?php echo html::textarea('desc', '', "rows='10' class='form-control kindeditor' hidefocus='true'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->release->mailto;?></th>
            <td colspan='2'>
              <div class="input-group">
                <?php echo html::select('mailto[]', $users, '', "class='form-control picker-select' data-placeholder='{$lang->chooseUsersToMail}' multiple");?>
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
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php js::set('multipleSelect', $lang->build->placeholder->multipleSelect);?>
<?php include '../../common/view/footer.html.php';?>
