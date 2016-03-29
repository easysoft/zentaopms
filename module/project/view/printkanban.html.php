<?php
/**
 * The kanban view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong 
 * @package     project
 * @version     $Id: kanban.html.php $
 */
?>
<?php if($_POST) die(include 'preview.html.php')?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='titlebar'>
   <div class='heading'>
     <?php echo html::icon($lang->icons['task']);?> <strong><?php echo $lang->printKanban->common;?></strong>
   </div>
 </div>
<form target='_blank' method='post'>
<table class='table'>
  <tr>
    <td align='center' class='text-middle'>
      <?php echo $lang->printKanban->content . ' ： ' . html::radio('content', $lang->printKanban->typeList, 'all')?>
      <?php echo html::submitButton($lang->printKanban->print)?>
    </td>
  </tr>
</table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
