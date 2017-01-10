<?php
/**
 * The edit view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: edit.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix' title='GROUP'><?php echo html::icon($lang->icons['group']);?> <strong><?php echo $group->id;?></strong></span>
    <strong><?php echo $group->name;?></strong>
    <small class='text-muted'> <?php echo $lang->group->edit;?> <?php echo html::icon($lang->icons['edit']);?></small>
  </div>
</div>

<form class='form-condensed mw-500px pdb-20' method='post' target='hiddenwin' id='dataform'>
  <table align='center' class='table table-form'> 
    <tr>
      <th class='w-100px'><?php echo $lang->group->name;?></th>
      <td><?php echo html::input('name', $group->name, "class='form-control' autocomplete='off'");?></td>
    </tr>  
    <tr>
      <th><?php echo $lang->group->desc;?></th>
      <td><?php echo html::textarea('desc', $group->desc, "rows='5' class='form-control'");?></td>
    </tr>  
    <tr><th></th><td><?php echo html::submitButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
