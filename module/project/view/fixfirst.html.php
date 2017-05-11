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
          <?php echo html::input('estimate', !empty($firstBurn->estimate) ? $firstBurn->estimate : (!empty($firstBurn->left) ? $firstBurn->left : ''), "class='form-control' placeholder='{$lang->project->placeholder->totalLeft}' autocomplete='off'")?>
          <span class='input-group-addon fix-border'><input id='withLeft' type='checkbox' checked name='withLeft' value='1' /> <label for='withLeft'><?php echo $lang->project->fixFirstWithLeft?></label></span>
          <span class='input-group-btn'><?php echo html::submitButton();?></span>
        </div>
        <div class='alert alert-info'><?php echo $lang->project->totalEstimate . $project->totalEstimate . $lang->project->workHour;?></div>
      </td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.lite.html.php';?>
