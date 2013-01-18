<?php
/**
 * The delay file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang<wwccss@gmail.com>
 * @package     project 
 * @version     $Id: delay.html.php 935 2013-01-16 07:49:24Z wwccss@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<form method='post' target='hiddenwin'>
  <table class='table-1'>
    <caption><?php echo $project->name;?></caption>
    <tr>
      <td class='rowhead'><?php echo $lang->project->begin;?></td>
      <td><?php echo html::input('begin', $project->begin, "class='text-3 date' onchange='computeWorkDays()'");?></td>
    </tr>
    <tr>
      <td class='rowhead'><?php echo $lang->project->end;?></td>
      <td><?php echo html::input('end', $project->end, "class='text-3 date' onchange='computeWorkDays()'");?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->project->days;?></th>
      <td><?php echo html::input('days', $project->days, "class='text-3'") . $lang->project->day;?></td>
    </tr>  
    <tr>
      <td class='rowhead'><?php echo $lang->comment;?></td>
      <td><?php echo html::textarea('comment', '', "rows='6' class='area-1'");?></td>
    </tr>
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton() . html::linkButton($lang->goback, $this->session->taskList); ?></td>
    </tr>
  </table>
  <?php include '../../common/view/action.html.php';?>
</form>
<?php include '../../common/view/footer.html.php';?>
