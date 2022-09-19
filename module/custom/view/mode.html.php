<?php
/**
 * The set view file of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<div id='mainContent' class='main-content'>
  <form class="load-indicator main-form" method='post'>
    <div class='main-header'>
      <div class='heading'>
        <strong><?php echo $lang->custom->mode?></strong>
      </div>
    </div>
    <table class='table table-form mw-300px'>
      <tr>
        <th class='text-top'><?php echo $lang->custom->mode;?></th>
        <td>
          <p>
            <label class="radio-inline"><input type="radio" name="mode" value="lean" <?php echo $mode == 'lean'? "checked='checked'" : '';?> id="modelean"><?php echo $lang->upgrade->to18Mode['lean'];?></label>
            <label class="radio-inline"><input type="radio" name="mode" value="new" <?php echo $mode == 'new'? "checked='checked'" : '';?> id="modenew"><?php echo $lang->upgrade->to18Mode['new'];?></label>
          </p>
        </td>
      </tr>
      <?php
      $class = ''; if($mode == 'new') $class="class='hidden'";?>
      <tr id="selectDefaultProgram" <?php echo $class;?>>
        <td></td>
        <td><?php echo html::select('program', $program, '', "class='form-control chosen'");?></td>
      </tr>
      <tr>
        <td></td>
        <td>
          <?php echo html::submitButton($lang->custom->switch);?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
