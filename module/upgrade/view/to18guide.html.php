<?php
/**
 * The to20 view file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <div class='modal-dialog'>
    <div class='panel'>
      <form method='post'>
        <h1 class='text-center'><?php echo $title;?></h1>
        <div class='main-row' id='mainContent'>
          <div class='main-col main-table'>
            <table class='table'>
              <thead>
                <tr class='text-center'>
                  <th class='text-left'><?php echo $this->lang->custom->mode;?></th>
                  <th><?php echo $this->lang->custom->modeList['light'];?></th>
                  <th><?php echo $this->lang->custom->modeList['ALM'];?></th>
                </tr>
              </thead>
              <tbody>
                <tr class='text-center'>
                  <td class='text-left'><?php echo $lang->custom->usage;?></td>
                  <td><?php echo $lang->custom->modeIntroductionList['light'];?></td>
                  <td><?php echo $lang->custom->modeIntroductionList['ALM'];?></td>
                </tr>
                <?php foreach($disabledFeatures as $feature):?>
                <?php if(is_array($feature) && empty($disabledScrumFeatures)) continue;?>
                <tr class='text-center'>
                  <td class='text-left'><?php echo (is_array($feature) && !empty($disabledScrumFeatures)) ? sprintf($this->lang->custom->scrum->common, implode($lang->comma, $disabledScrumFeatures)) : $this->lang->custom->features[$feature];?></td>
                  <td><i class='icon text-red icon-ban-circle'></i></td>
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
                <tr class='text-center'>
                  <td class='text-left strong text-14px'><?php echo $this->lang->custom->selectUsage;?></td>
                  <td><?php echo html::commonButton($lang->custom->useLight, "id='useLight'", 'btn btn-wide');?></td>
                  <td><?php echo html::commonButton($lang->custom->useALM, "id='useALM'", 'btn btn-wide');?></td>
                </tr>
                <tr>
                  <td><?php echo $this->lang->upgrade->remark;?></td>
                  <td colspan='2'><?php echo $this->lang->upgrade->remarkDesc;?></td>
                </tr>
              </tbody>
              <?php echo html::hidden('mode', '');?>
            </table>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
