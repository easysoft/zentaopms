<?php
/**
 * The fixFirst view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<form target='hiddenwin' method='post' style='padding:20px 20px'>
  <table class='table table-form'>
    <tr>
      <td>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $project->begin?></span>
          <?php echo html::input('left', $firstBurn, "class='form-control' placeholder='{$lang->project->placeholder->totalLeft}'")?>
          <span class='input-group-addon fix-border'><?php echo '(' . $lang->project->totalEstimate . $project->totalEstimate . $lang->project->workHour. ')'?></span>
          <span class='input-group-btn'><?php echo html::submitButton();?></span>
        </div>
      </td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
