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
<?php include '../../common/view/header.html.php';?>
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
        <th class='text-top w-120px'><?php echo $lang->custom->mode;?></th>
        <td class='w-300px'>
          <p>
            <label class="radio-inline"><input type="radio" name="mode" value="light" <?php echo $mode == 'light'? "checked='checked'" : '';?> id="modelight"><?php echo $lang->upgrade->to18Mode['light'];?></label>
            <label class="radio-inline"><input type="radio" name="mode" value="ALM" <?php echo $mode == 'ALM'? "checked='checked'" : '';?> id="modeALM"><?php echo $lang->upgrade->to18Mode['ALM'];?></label>
          </p>
        </td>
        <td></td>
      </tr>
      <?php if($program):?>
      <tr class='hide'>
        <th><?php echo $lang->custom->defaultProgram;?></th>
        <td>
          <?php
          $disabled = $mode == 'light' ? "disabled" : '';
          echo html::select('program', $program, $programID, "class='form-control chosen' $disabled" );
          ?>
        </td>
      </tr>
      <?php endif;?>
      <tr>
        <td></td>
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
