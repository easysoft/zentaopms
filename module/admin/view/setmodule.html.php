<?php
/**
 * The setmodule view file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@cnezsoft.com>
 * @package     admin
 * @version     $Id: setmodule.html.php 2568 2012-02-09 06:56:35Z shiyangyangwork@yahoo.cn $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='main-header'>
      <h2><?php echo $lang->admin->setModuleIndex;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" id="ajaxForm" method="post">
      <table class='table table-condensed table-bordered active-disabled table-fixed'>
        <thead class='text-center'>
          <tr>
            <th class='w-150px'><?php echo $lang->admin->setModule->module;?></th>
            <th><?php echo $lang->admin->setModule->optional;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($config->featureGroup as $group => $features):?>
          <?php if(strpos(",$disabledFeatures,", ",$group,") !== false) continue;?>

          <?php $hasData = false;?>
          <?php foreach($features as $feature):?>
          <?php $code = $group . ucfirst($feature);?>
          <?php if(strpos(",$disabledFeatures,", ",$code,") !== false) continue;?>
          <?php $hasData = true;?>
          <?php endforeach;?>

          <?php if($hasData):?>
          <tr>
            <td class='text-middle text-right thWidth'>
              <div class="checkbox-primary checkbox-inline checkbox-right check-all">
                <input type='checkbox' id='allChecker<?php echo $group;?>' name='allChecker'>
                <label class='text-right' for='allChecker<?php echo $group;?>'><?php echo $lang->admin->setModule->{$group};?></label>
              </div>
            </td>
            <td>
              <?php foreach($features as $feature):?>
              <?php $code = $group. ucfirst($feature);?>
              <?php if(strpos(",$disabledFeatures,", ",$code,") !== false) continue;?>
              <?php
              if($code == 'myScore')
              {
                  $value = $useScore;
              }
              else
              {
                  $value = strpos(",$closedFeatures,", ",$code,") === false ? '1' : '0';
              }
              ?>
              <div class='group-item'>
              <?php echo html::checkbox("module[{$code}]", array('1' => $lang->admin->setModule->{$feature}), $value, "data-code='{$code}'", 'inline');?>
              <?php echo html::hidden("module[{$code}][]", $value, $value ? 'disabled' : '');?>
              </div>
              <?php endforeach;?>
            </td>
          </tr>
          <?php endif;?>
          <?php endforeach;?>
          <tr>
            <td class='text-middle text-right thWidth'>
              <div class="checkbox-primary checkbox-inline checkbox-right check-all">
                <input type='checkbox' id='allChecker'>
                <label class='text-right' for='allChecker'><?php echo $lang->selectAll;?></label>
              </div>
            </td>
            <td><?php echo html::submitButton();?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
