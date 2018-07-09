<?php
/**
 * The edit view of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: edit.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label-id'><?php echo $build->id;?></span>
        <?php echo html::a($this->createLink('build', 'view', 'build=' . $build->id), $build->name, '', "title='$build->name'");?>
        <small><?php echo $lang->arrow . $lang->build->edit;?></small>
      </h2>
    </div>
    <form class='load-indicator main-form form-ajax' method='post' id='dataform' enctype='multipart/form-data'>
      <table class='table table-form'> 
        <tr>
          <th><?php echo $lang->build->product;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::select('product', $products, $build->product, "onchange='loadBranches(this.value);' class='form-control chosen' required");?>
              <?php
              if($build->productType != 'normal')
              {
                  if($product->branch) $branches = array($product->branch => $branches[$product->branch]);
                  echo "<span class='input-group-addon fix-padding fix-border'></span>" . html::select('branch', $branches, $build->branch, "class='form-control chosen'");
              }
              ?>
            </div>
          </td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->build->name;?></th>
          <td><?php echo html::input('name', $build->name, "class='form-control' autocomplete='off' required");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->build->builder;?></th>
          <td><?php echo html::select('builder', $users, $build->builder, 'class="form-control chosen" required');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->build->date;?></th>
          <td><?php echo html::input('date', $build->date, "class='form-control form-date' required");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->build->scmPath;?></th>
          <td colspan='2'><?php echo html::input('scmPath', $build->scmPath, "class='form-control' placeholder='{$lang->build->placeholder->scmPath}' autocomplete='off'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->build->filePath;?></th>
          <td colspan='2'><?php echo html::input('filePath', $build->filePath, "class='form-control' placeholder='{$lang->build->placeholder->filePath}' autocomplete='off'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->build->files;?></th>
          <td colspan='2'><?php echo $this->fetch('file', 'buildForm');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->build->desc;?></th>
          <td colspan='2'><?php echo html::textarea('desc', htmlspecialchars($build->desc), "rows='10' class='form-control kindeditor' hidefocus='true'");?></td>
        </tr>
        <tr>
          <td colspan="3" class="text-center form-actions">
            <?php echo html::submitButton('', '', 'btn btn-wide btn-primary');?>
            <?php echo html::backButton('', '', 'btn btn-wide');?>
            <?php echo $config->global->flow != 'onlyTest' ? html::hidden('project', $build->project) : '';?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php js::set('productGroups', $productGroups)?>
<?php include '../../common/view/footer.html.php';?>
