<?php
/**
 * The set view file of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     custom
 * @version     $Id$
 * @link        https://www.zentao.net
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
    <div class='main-table'>
      <p class='strong'><?php echo $currentModeTips;?></p>
      <table class='table table-bordered'>
        <thead>
          <tr>
            <th><?php echo $this->lang->custom->mode;?></th>
            <th class='text-center'><?php echo $lang->custom->modeList['light'];?></th>
            <th class='text-center'><?php echo $lang->custom->modeList['ALM'];?></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo $lang->custom->usage;?></td>
            <td class='text-center'><?php echo $lang->custom->modeIntroductionList['light'];?></td>
            <td class='text-center'><?php echo $lang->custom->modeIntroductionList['ALM'];?></td>
          </tr>
          <?php foreach($disabledFeatures as $feature):?>
          <?php if(is_array($feature) and empty($disabledScrumFeatures)) continue;?>
          <tr class='text-center'>
            <td class='text-left'><?php echo (is_array($feature) and !empty($disabledScrumFeatures)) ? sprintf($this->lang->custom->scrum->common, implode($lang->comma, $disabledScrumFeatures)) : $this->lang->custom->features[$feature];?></td>
            <td><i class='icon text-red icon-ban-circle'></i></td>
            <td><i class='icon text-success icon-check'></i></td>
          </tr>
          <?php endforeach;?>

          <?php foreach($config->custom->allFeatures as $feature):?>
          <?php if(in_array($feature, $disabledFeatures)) continue;?>
          <?php if($feature == 'scrumDetail' and empty($enabledScrumFeatures)) continue;?>
          <tr class='text-center'>
            <td class='text-left'><?php echo ($feature == 'scrumDetail' and !empty($enabledScrumFeatures)) ? sprintf($this->lang->custom->scrum->common, implode($lang->comma, $enabledScrumFeatures)) : $this->lang->custom->features[$feature];?></td>
            <td><i class='icon text-success icon-check'></i></td>
            <td><i class='icon text-success icon-check'></i></td>
          </tr>
          <?php endforeach;?>
          <tr class='text-center select-mode'>
            <td class='text-left strong'><?php echo $this->lang->custom->selectUsage;?></td>
            <?php $title = $mode == 'light' ? "title='{$currentModeTips}'" : '';?>
            <td <?php echo $title;?>>
              <?php $disabled = $mode == 'light' ? 'disabled' : '';?>
              <?php echo html::commonButton($lang->custom->useLight, "id='useLight' data-mode='light' $disabled", "btn btn-wide btn-primary");?>
            </td>
            <?php $title = $mode == 'ALM' ? "title='{$currentModeTips}'" : '';?>
            <td <?php echo $title;?>>
              <?php $disabled = $mode == 'ALM' ? 'disabled' : '';?>
              <?php echo html::commonButton($lang->custom->useALM, "id='useALM' data-mode='ALM' $disabled", "btn btn-wide btn-primary");?>
              <?php echo html::hidden('mode', $mode);?>
            </td>
          </tr>
        </tbody>
      </table>
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
