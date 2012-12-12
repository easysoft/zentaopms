<?php
/**
 * The create view of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<form method='post' target='hiddenwin' id='dataform'>
  <table class='table-1'> 
    <caption><?php echo $lang->testtask->create;?></caption>
    <?php if(isset($projectID)):?>
    <tr>
      <th class='rowhead'><?php echo $lang->testtask->product;?></th>
      <td><?php echo html::select('product', $products, '', 'class=select-3');?></td>
    </tr>  
    <?php else:?>
    <tr class='hidden'>
      <th><?php echo $lang->testtask->product;?></th>
      <td><?php echo html::input('product', $productID, 'class=select-3');?></td>
    </tr>  
    <?php endif;?>
    <tr>
      <th class='rowhead'><?php echo $lang->testtask->project;?></th>
      <td><?php echo html::select('project', $projects, '', 'class=select-3 onchange=loadProjectRelated(this.value)');?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->testtask->build;?></th>
      <td><span id='buildBox'><?php echo html::select('build', $builds, '', 'class=select-3');?></span></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->testtask->owner;?></th>
      <td><?php echo html::select('owner', $users, '', 'class=select-3');?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->testtask->pri;?></th>
      <td><?php echo html::select('pri', $lang->testtask->priList, 0, 'class=select-3');?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->testtask->begin;?></th>
      <td><?php echo html::input('begin', '', "class='text-3 date'");?>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->testtask->end;?></th>
      <td><?php echo html::input('end', '', "class='text-3 date'");?>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->testtask->status;?></th>
      <td><?php echo html::select('status', $lang->testtask->statusList, '',  "class='select-3'");?>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->testtask->name;?></th>
      <td><?php echo html::input('name', '', "class='text-1'");?>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->testtask->desc;?></th>
      <td><?php echo html::textarea('desc', '', "rows=10 class='area-1'");?>
    </tr>  
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton();?> </td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
