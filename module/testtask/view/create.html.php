<?php
/**
 * The create view of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['testtask']);?></span>
      <strong><small class='text-muted'><?php echo html::icon($lang->icons['create']);?></small> <?php echo $lang->testtask->create;?></strong>
    </div>
  </div>
  <form class='form-condensed' method='post' target='hiddenwin' id='dataform'>
    <table class='table table-form'> 
      <?php if(isset($projectID)):?>
      <tr>
        <th class='w-90px'><?php echo $lang->testtask->product;?></th>
        <td class='w-p25-f'><?php echo html::select('product', $products, '', "class='form-control chosen'");?></td><td></td>
      </tr>  
      <?php else:?>
      <tr class='hide'>
        <th class='w-90px'><?php echo $lang->testtask->product;?></th>
        <td class='w-p25-f'><?php echo html::input('product', $productID, "class='form-control'");?></td><td></td>
      </tr>  
      <?php endif;?>
      <tr>
        <th class='w-90px'><?php echo $lang->testtask->project;?></th>
        <td class='w-p25-f'><?php echo html::select('project', $projects, '', "class='form-control chosen' onchange='loadProjectRelated(this.value)'");?></td><td></td>
      </tr>  
      <tr>
        <th><?php echo $lang->testtask->build;?></th>
        <td><span id='buildBox'><?php echo html::select('build', $builds, '', "class='form-control chosen'");?></span></td>
      </tr>  
      <tr>
        <th><?php echo $lang->testtask->owner;?></th>
        <td><?php echo html::select('owner', $users, '', "class='form-control chosen'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->testtask->pri;?></th>
        <td><?php echo html::select('pri', $lang->testtask->priList, 0, "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->testtask->begin;?></th>
        <td><?php echo html::input('begin', '', "class='form-control form-date' onchange='suitEndDate()'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->testtask->end;?></th>
        <td><?php echo html::input('end', '', "class='form-control form-date'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->testtask->status;?></th>
        <td><?php echo html::select('status', $lang->testtask->statusList, '',  "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->testtask->name;?></th>
        <td colspan='2'><?php echo html::input('name', '', "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->testtask->desc;?></th>
        <td colspan='2'><?php echo html::textarea('desc', '', "rows=10 class='form-control'");?></td>
      </tr>
      <tr>
        <td></td><td colspan='2'><?php echo html::submitButton() . html::backButton();?> </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
