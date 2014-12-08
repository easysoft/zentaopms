<?php
/**
 * The edit view of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: edit.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div class='container'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['build']);?> <strong><?php echo $build->id;?></strong></span>
      <strong><?php echo html::a($this->createLink('build', 'view', 'build=' . $build->id), $build->name, '_blank');?></strong>
      <small class='text-muted'> <?php echo $lang->build->edit;?> <?php echo html::icon($lang->icons['edit']);?></small>
    </div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin' id='dataform' enctype='multipart/form-data'>
    <table class='table table-form'> 
      <tr>
        <th class='w-110px'><?php echo $lang->build->product;?></th>
        <td class='w-p25-f'><?php echo html::select('product', $products, $build->product, "class='form-control chosen'");?></td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->build->name;?></th>
        <td><?php echo html::input('name', $build->name, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->build->builder;?></th>
        <td><?php echo html::select('builder', $users, $build->builder, 'class="form-control chosen"');?></td>
      </tr>
      <tr>
        <th><?php echo $lang->build->date;?></th>
        <td><?php echo html::input('date', $build->date, "class='form-control form-date'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->build->scmPath;?></th>
        <td colspan='2'><?php echo html::input('scmPath', $build->scmPath, "class='form-control' placeholder='{$lang->build->placeholder->scmPath}'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->build->filePath;?></th>
        <td colspan='2'><?php echo html::input('filePath', $build->filePath, "class='form-control' placeholder='{$lang->build->placeholder->filePath}'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->build->files;?></th>
        <td colspan='2'><?php echo $this->fetch('file', 'buildForm', array('fileCount' => 1));?></td>
      </tr>
      <tr>
        <th><?php echo $lang->build->desc;?></th>
        <td colspan='2'><?php echo html::textarea('desc', htmlspecialchars($build->desc), "rows='10' class='form-control'");?></td>
      </tr>
      <tr><td></td><td colspan='2'><?php echo html::submitButton() . html::backButton() .html::hidden('project', $build->project);?></td></tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
