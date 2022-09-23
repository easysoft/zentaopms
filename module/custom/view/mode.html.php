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
<?php js::set('changeModeTitleTips', $lang->custom->changeModeTitleTips);?>
<?php js::set('changeModeContentTips', $lang->custom->changeModeContentTips);?>
<?php js::set('mode', $mode);?>
<div id='mainContent' class='main-content'>
  <form id='modeForm' class="load-indicator main-form form-ajax"  method='post'>
    <div class='main-header'>
      <div class='heading'>
        <strong><?php echo $lang->custom->mode?></strong>
      </div>
    </div>
    <table class='table table-form'>
      <tr>
        <th class='text-top'><?php echo $lang->custom->mode;?></th>
        <td>
          <p>
            <label class="radio-inline"><input type="radio" name="mode" value="lean" <?php echo $mode == 'lean'? "checked='checked'" : '';?> id="modelean"><?php echo $lang->upgrade->to18Mode['lean'];?></label>
            <label class="radio-inline"><input type="radio" name="mode" value="new" <?php echo $mode == 'new'? "checked='checked'" : '';?> id="modenew"><?php echo $lang->upgrade->to18Mode['new'];?></label>
          </p>
        </td>
      </tr>
      <tr id="selectDefaultProgram" class='hide'>
        <td></td>
        <td>
          <?php
          $disabled = $mode == 'lean' ? "disabled" : '';
          echo html::select('program', $program, $programID, "class='form-control chosen' $disabled" );
          ?>
          <div class="text-danger help-text"><?php echo $lang->custom->switchModeHelper ?></div>
        </td>
      </tr>
      <tr>
        <th class='text-top'><?php echo $lang->custom->selectDefaultProgram;?></th>
        <td><?php echo html::submitButton($lang->custom->switch, 'disabled');?></td>
      </tr>
    </table>
  </form>
</div>

<div class='modal fade' id='confirmModal'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span><span class='sr-only'><?php echo $this->lang->close;?></span></button>
        <h4 class='modal-title'></h4>
      </div>
      <div class='modal-body'></div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-primary btn-wide btn-confirm'><?php echo $lang->confirm;?></button>
        <button type='button' class='btn btn-default btn-wide' data-dismiss='modal'><?php echo $this->lang->cancel;?></button>
      </div>
    </div>
  </div>
</div>
<style>
#confirmModal .modal-header {border-bottom: 0px}
#confirmModal .modal-header h4.modal-title{font-weight: 700}
#confirmModal .modal-footer {border-top: 0px; text-align: center}
</style>
<?php include '../../common/view/footer.html.php';?>
