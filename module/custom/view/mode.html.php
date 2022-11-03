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
<?php js::set('changeModeTips', sprintf($lang->custom->changeModeTips, $lang->custom->modeList[$mode == 'light' ? 'ALM' : 'light']));?>
<?php js::set('mode', $mode);?>
<div id='mainContent' class='main-content'>
  <form id='modeForm' class="load-indicator main-form form-ajax"  method='post'>
    <div class='main-header'>
      <h2><?php echo $lang->custom->mode;?></h2>
    </div>
    <table class='table table-form w-700px'>
      <tr>
        <td colspan='2'><?php echo sprintf($lang->custom->currentModeTips, $lang->custom->modeList[$mode], $lang->custom->modeList[$mode == 'light' ? 'ALM' : 'light']);?> </td>
      </tr>
      <tr>
        <td>
          <label class='radio-inline'><input type='radio' name='mode' value='light' <?php echo $mode == 'light'? "checked='checked'" : '';?> id="modelight"><strong><?php echo $lang->upgrade->to18Mode['light'];?></strong></label>
          <p class='with-padding text-muted'><?php echo $lang->custom->modeIntrodutionList['light'];?></p>
        </td>
        <td>
          <label class='radio-inline'><input type='radio' name='mode' value='ALM' <?php echo $mode == 'ALM'? "checked='checked'" : '';?> id="modeALM"><strong><?php echo $lang->upgrade->to18Mode['ALM'];?></strong></label>
          <p class='with-padding text-muted'><?php echo $lang->custom->modeIntrodutionList['ALM'];?></p>
        </td>
      </tr>
      <tr>
        <td colspan='2'>
          <table class='table table-bordered'>
            <thead>
              <tr>
                <th><?php echo $this->lang->upgrade->mode;?></th>
                <th class="text-center"><?php echo $this->lang->upgrade->to18Mode['light'];?></th>
                <th class="text-center"><?php echo $this->lang->upgrade->to18Mode['ALM'];?></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?php echo $this->lang->upgrade->program;?></td>
                <td class="text-center"><i class="icon text-red text-red icon-close"></i></td>
                <td class="text-center"><i class="icon text-success text-success icon-check"></i></td>
              </tr>
              <tr>
                <td><?php echo $this->lang->upgrade->productRR;?></td>
                <td class="text-center"><i class="icon text-success icon-check"></i></td>
                <td class="text-center"><i class="icon text-success icon-check"></i></td>
              </tr>
              <tr>
                <td><?php echo $this->lang->upgrade->productUR;?></td>
                <td class="text-center"><i class="icon text-red icon-close"></i></td>
                <td class="text-center"><i class="icon text-success icon-check"></i></td>
              </tr>
              <tr>
                <td><?php echo $this->lang->upgrade->productLine;?></td>
                <td class="text-center"><i class="icon text-red icon-close"></i></td>
                <td class="text-center"><i class="icon text-success icon-check"></i></td>
              </tr>
              <tr>
                <td><?php echo $this->lang->upgrade->projectScrum;?></td>
                <td class="text-center"><i class="icon text-success icon-check"></i></td>
                <td class="text-center"><i class="icon text-success icon-check"></i></td>
              </tr>
              <?php if($config->edition == 'max'):?>
              <tr>
                <td><?php echo $this->lang->upgrade->scrumDetail;?></td>
                <td class="text-center"><i class="icon text-red icon-close"></i></td>
                <td class="text-center"><i class="icon text-success icon-check"></i></td>
              </tr>
              <?php endif;?>
              <tr>
                <td><?php echo $this->lang->upgrade->projectWaterfall;?></td>
                <td class="text-center"><i class="icon text-red icon-close"></i></td>
                <td class="text-center"><i class="icon text-success icon-check"></i></td>
              </tr>
              <tr>
                <td><?php echo $this->lang->upgrade->projectKanban;?></td>
                <td class="text-center"><i class="icon text-success icon-check"></i></td>
                <td class="text-center"><i class="icon text-success icon-check"></i></td>
              </tr>
              <tr>
                <td><?php echo $this->lang->upgrade->execution;?></td>
                <td class="text-center"><i class="icon text-success icon-check"></i></td>
                <td class="text-center"><i class="icon text-success icon-check"></i></td>
              </tr>
              <?php if($config->edition == 'max'):?>
              <tr>
                <td><?php echo $this->lang->upgrade->assetlib;?></td>
                <td class="text-center"><i class="icon text-red icon-close"></i></td>
                <td class="text-center"><i class="icon text-success icon-check"></i></td>
              </tr>
              <?php endif;?>
              <?php if($config->edition == 'biz' or $config->edition == 'max'):?>
              <tr>
                <td><?php echo $this->lang->upgrade->oa;?></td>
                <td class="text-center"><i class="icon text-success icon-check"></i></td>
                <td class="text-center"><i class="icon text-success icon-check"></i></td>
              </tr>
              <?php endif;?>
              <tr>
                <td><?php echo $this->lang->upgrade->vision;?></td>
                <td class="text-center"><i class="icon text-success icon-check"></i></td>
                <td class="text-center"><i class="icon text-success icon-check"></i></td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
      <tr>
        <td colspan='2'><?php echo html::submitButton($lang->custom->switch, 'disabled');?></td>
      </tr>
    </table>
  </form>
</div>

<div class='modal fade' id='selectProgramModal'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span><span class='sr-only'><?php echo $this->lang->close;?></span></button>
        <h4 class='modal-title'><?php echo $lang->custom->selectDefaultProgram;?></h4>
      </div>
      <div class='modal-body'>
        <div class='alert alert-primary'>
          <p class='text-info'><?php echo $lang->custom->selectProgramTips;?></p>
        </div>
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->custom->defaultProgram;?></th>
            <td><?php echo html::select('program', $program, $programID, "class='form-control chosen'");?></td>
          </tr>
        </table>
      </div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-primary btn-wide btn-save'><?php echo $lang->save;?></button>
      </div>
    </div>
  </div>
</div>
<style>
#selectProgramModal .modal-dialog {width: 550px}
#selectProgramModal .modal-header {border-bottom: 0px}
#selectProgramModal .modal-header h4.modal-title{font-weight: 700}
#selectProgramModal .modal-footer {border-top: 0px; text-align: center}
</style>
<?php include '../../common/view/footer.html.php';?>
