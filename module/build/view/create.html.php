<?php
/**
 * The create view of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<div class='yui-d0'><div class='u-1'>
  <form method='post' target='hiddenwin'>
    <table class='table-1'> 
      <caption><?php echo $lang->build->create;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->build->product;?></th>
        <td><?php echo html::select('product', $products, '', "class='select-3'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->build->name;?></th>
        <td><?php echo html::input('name', '', "class='text-3'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->build->builder;?></th>
        <td><?php echo html::select('builder', $users, $app->user->account, 'class="select-3"');?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->build->date;?></th>
        <td><?php echo html::input('date', helper::today(), "class='text-3 date'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->build->scmPath;?></th>
        <td><?php echo html::input('scmPath', '', "class='text-1'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->build->filePath;?></th>
        <td><?php echo html::input('filePath', '', "class='text-1'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->build->desc;?></th>
        <td><?php echo html::textarea('desc', '', "rows='15' class='area-1'");?></td>
      </tr>  
      <tr><td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton();?></td></tr>
    </table>
  </form>
</div>  
<?php include '../../common/view/footer.html.php';?>
