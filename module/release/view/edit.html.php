<?php
/**
 * The edit view of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<form method='post' target='hiddenwin'>
  <table class='table-1'> 
    <caption><?php echo $lang->release->edit;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->release->name;?></th>
      <td><?php echo html::input('name', $release->name, "class='text-3'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->release->build;?></th>
      <td><?php echo html::select('build', $builds, $release->build, 'class="select-3"'); ?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->release->date;?></th>
      <td><?php echo html::input('date', $release->date, "class='text-3 date'");?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->release->desc;?></th>
      <td><?php echo html::textarea('desc', htmlspecialchars($release->desc), "rows='20' class='area-1'");?></td>
    </tr>  
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton() . html::resetButton() . html::hidden('product', $release->product);?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
