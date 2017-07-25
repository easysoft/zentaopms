<?php
/**
 * The create view of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div class='container'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['build']);?></span>
      <strong><small class='text-muted'><?php echo html::icon($lang->icons['create']);?></small> <?php echo $lang->build->create;?></strong>
    </div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin' id='dataform' enctype='multipart/form-data'>
    <table class='table table-form'> 
      <tr>
        <th class='w-110px'><?php echo $lang->build->product;?></th>
        <?php if($products):?>
        <td>
          <div class='input-group'>
            <?php echo html::select('product', $products, $product->id, "onchange='loadBranches(this.value);' class='form-control chosen'");?>
            <?php
            if($product->type != 'normal')
            {
                if($product->branch) $branches = array($product->branch => $branches[$product->branch]);
                echo "<span class='input-group-addon fix-padding fix-border'></span>" . html::select('branch', $branches, $product->branch, "class='form-control' style='width:100px; display:inline-block;'");
            }
            ?>
          </div>
        </td>
        <td></td>
        <?php else:?>
        <td colspan='2'><?php if(empty($products)) printf($lang->build->noProduct, $this->createLink('project', 'manageproducts', "projectID=$projectID&from=buildCreate"));?></td>
        <?php endif;?>
      </tr>
      <tr>
        <th><?php echo $lang->build->name;?></th>
        <td class='w-p25-f'>
          <?php echo html::input('name', '', "class='form-control' autocomplete='off'");?>
        </td>
        <td>
          <?php if($lastBuild):?>
          <div class='help-block'> &nbsp; <?php echo $lang->build->last . ': <strong>' . $lastBuild->name . '</strong>';?></div>
          <?php endif;?>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->build->builder;?></th>
        <td><?php echo html::select('builder', $users, $app->user->account, 'class="form-control chosen"');?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->build->date;?></th>
        <td><?php echo html::input('date', helper::today(), "class='form-control form-date'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->build->scmPath;?></th>
        <td colspan='2'><?php echo html::input('scmPath', '', "class='form-control' placeholder='{$lang->build->placeholder->scmPath}' autocomplete='off'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->build->filePath;?></th>
        <td colspan='2'><?php echo html::input('filePath', '', "class='form-control' placeholder='{$lang->build->placeholder->filePath}' autocomplete='off'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->build->files;?></th>
        <td colspan='2'><?php echo $this->fetch('file', 'buildForm', array('fileCount' => 1));?></td>
      </tr>
      <tr>
        <th><?php echo $lang->build->desc;?></th>
        <td colspan='2'><?php echo html::textarea('desc', '', "rows='10' class='form-control'");?></td>
      </tr>  
      <tr><td></td><td colspan='2'><?php echo html::submitButton() . html::backButton();?></td></tr>
    </table>
  </form>
</div>
<?php js::set('productGroups', $productGroups)?>
<?php include '../../common/view/footer.html.php';?>
