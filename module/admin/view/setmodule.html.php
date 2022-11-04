<?php
/**
 * The setmodule view file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@cnezsoft.com>
 * @package     admin
 * @version     $Id: setmodule.html.php 2568 2012-02-09 06:56:35Z shiyangyangwork@yahoo.cn $
 * @link        http://www.zentao.net
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
            <th rowspan='2' class='w-120px'><?php echo $lang->admin->setModule->module;?></th>
            <th colspan='2'><?php echo $lang->admin->setModule->optional;?></th>
          </tr>
          <tr>
            <th><?php echo $lang->admin->setModule->opened;?></th>
            <th><?php echo $lang->admin->setModule->closed;?></th>
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
          <?php endForeach;?>

          <?php if($hasData):?>
          <tr>
            <td class='text-right'><?php echo $lang->admin->setModule->{$group};?></td>
            <td>
              <?php foreach($features as $feature):?>
              <?php $code = $group. ucfirst($feature);?>
              <?php if(strpos(",$disabledFeatures,", ",$code,") !== false) continue;?>
              <?php if(strpos(",$closedFeatures,",   ",$code,") !== false) continue;?>
              <div class='group-item'>
              <?php echo html::checkbox("module[{$code}]", array('1' => $lang->admin->setModule->{$feature}), '1', "data-code='{$code}'", 'inline');?>
              <?php echo html::hidden("module[{$code}][]", '1', 'disabled');?>
              </div>
              <?php endForeach;?>
            </td>
            <td>
              <?php foreach($features as $feature):?>
              <?php $code = $group . ucfirst($feature);?>
              <?php if(strpos(",$disabledFeatures,", ",$code,") !== false) continue;?>
              <?php if(strpos(",$closedFeatures,",   ",$code,") === false) continue;?>
              <div class='group-item'>
              <?php echo html::checkbox("module[{$code}]", array('1' => $lang->admin->setModule->{$feature}), '0', "data-code='{$code}'", 'inline');?>
              <?php echo html::hidden("module[{$code}][]", '0');?>
              </div>
              <?php endForeach;?>
            </td>
          </tr>
          <?php endif;?>
          <?php endForeach;?>
          <tr>
            <td colspan='3' class='text-center'><?php echo html::submitButton();?></td>
          </tr>
        </tbody>
      </table>
    </form>`
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
