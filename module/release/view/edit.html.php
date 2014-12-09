<?php
/**
 * The edit view of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: edit.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['release']);?> <strong><?php echo $release->id;?></strong></span>
      <strong><?php echo html::a(inlink('view', "release=$release->id"), $release->name);?></strong>
      <small class='text-muted'> <?php echo $lang->release->edit;?> <i class='icon icon-pencil'></i></small>
    </div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin' id='dataform' enctype='multipart/form-data'>
    <table class='table table-form'> 
      <tr>
        <th class='w-90px'><?php echo $lang->release->name;?></th>
        <td class='w-p25-f'><?php echo html::input('name', $release->name, "class='form-control'");?></td><td></td>
      </tr>  
      <tr>
        <th><?php echo $lang->release->build;?></th>
        <td><?php echo html::select('build', $builds, $release->build, "class='form-control chosen'"); ?></td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->release->date;?></th>
        <td><?php echo html::input('date', $release->date, "class='form-control form-date'");?></td><td></td>
      </tr>  
      <tr>
        <th><?php echo $lang->release->desc;?></th>
        <td colspan='2'><?php echo html::textarea('desc', htmlspecialchars($release->desc), "rows=10 class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->files;?></th>
        <td colspan='2'><?php echo $this->fetch('file', 'buildform', array('fileCount' => 1));?></td>
      </tr>  
      <tr>
        <td></td>
        <td colspan='2'><?php echo html::submitButton() . html::backButton() . html::hidden('product', $release->product);?></td>
      </tr>
    </table>
  </form>  
</div>
<?php include '../../common/view/footer.html.php';?>
