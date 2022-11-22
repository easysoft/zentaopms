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
<?php js::set('hasProgram', !empty($programs));?>
<div id='mainContent' class='main-content'>
  <form id='modeForm' class="load-indicator main-form form-ajax"  method='post'>
    <div class='main-header'>
      <h2><?php echo $lang->custom->modeManagement;?></h2>
    </div>
    <table class='table table-form w-700px'>
      <tr>
        <td colspan='2'><?php echo sprintf($lang->custom->currentModeTips, $lang->custom->modeList[$mode], $lang->custom->modeList[$mode == 'light' ? 'ALM' : 'light']);?> </td>
      </tr>
      <tr>
        <td>
          <label class='radio-inline'><input type='radio' name='mode' value='light' <?php echo $mode == 'light'? "checked='checked'" : '';?> id="modelight"><strong><?php echo $lang->custom->modeList['light'];?></strong></label>
          <p class='with-padding pd-l-20 text-muted'><?php echo $lang->custom->modeIntroductionList['light'];?></p>
        </td>
        <td>
          <label class='radio-inline'><input type='radio' name='mode' value='ALM' <?php echo $mode == 'ALM'? "checked='checked'" : '';?> id="modeALM"><strong><?php echo $lang->custom->modeList['ALM'];?></strong></label>
          <p class='with-padding pd-l-20 text-muted'><?php echo $lang->custom->modeIntroductionList['ALM'];?></p>
        </td>
      </tr>
      <tr>
        <td colspan='2'>
          <table class='table table-bordered'>
            <thead>
              <tr>
                <th><?php echo $this->lang->custom->mode;?></th>
                <th class="text-center"><?php echo $lang->custom->modeList['light'];?></th>
                <th class="text-center"><?php echo $lang->custom->modeList['ALM'];?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($disabledFeatures as $feature):?>
              <?php if(is_array($feature) && empty($disabledScrumFeatures)) continue;?>
              <tr class='text-center'>
                <td class='text-left'><?php echo (is_array($feature) && !empty($disabledScrumFeatures)) ? sprintf($this->lang->custom->scrum->common, implode($lang->comma, $disabledScrumFeatures)) : $this->lang->custom->features[$feature];?></td>
                <td><i class='icon text-red icon-close'></i></td>
                <td><i class='icon text-success icon-check'></i></td>
              </tr>
              <?php endforeach;?>

              <?php foreach($config->custom->allFeatures as $feature):?>
              <?php if(in_array($feature, $disabledFeatures)) continue;?>
              <?php if($feature == 'scrumDetail' && empty($enabledScrumFeatures)) continue;?>
              <tr class='text-center'>
                <td class='text-left'><?php echo ($feature == 'scrumDetail' && !empty($enabledScrumFeatures)) ? sprintf($this->lang->custom->scrum->common, implode($lang->comma, $enabledScrumFeatures)) : $this->lang->custom->features[$feature];?></td>
                <td><i class='icon text-success icon-check'></i></td>
                <td><i class='icon text-success icon-check'></i></td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </td>
      </tr>
      <tr>
        <td colspan='2'><?php echo html::submitButton($lang->custom->switch, 'disabled');?></td>
      </tr>
    </table>

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
                <td><?php echo html::select('program', $programs, $programID, "class='form-control chosen'");?></td>
              </tr>
            </table>
          </div>
          <div class='modal-footer'>
            <button type='button' class='btn btn-primary btn-wide btn-save'><?php echo $lang->save;?></button>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
