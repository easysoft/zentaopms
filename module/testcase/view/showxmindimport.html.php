<?php
/**
 * The create view of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: create.html.php 4904 2013-06-26 05:37:45Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include '../../common/view/mindmap.html.php' ?>
<?php js::set('productID', $productID);?>
<?php js::set('branch', $branch);?>
<?php js::set('userConfig_module', $settings['module']);?>
<?php js::set('userConfig_scene', $settings['scene']);?>
<?php js::set('userConfig_case', $settings['case']);?>
<?php js::set('userConfig_pri', $settings['pri']);?>
<?php js::set('userConfig_group', $settings['group']);?>

<?php js::set('jsLng',$jsLng);?>

<div id='mainContent' class='main-content' style="min-width:1000px;min-height:500px;">
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->testcase->xmindImportEdit;?>(<?php echo $product->name;?>)</h2>
      <div class="pull-right btn-toolbar">
        <!-- Place buttons for switching between XMind and table. -->
      </div>
    </div>
    <form class='load-indicator main-form'>
      <table class='table table-form'>
        <tbody>
         <tr><td>
          <div id="mindmap" class="mindmap" style="height:calc(100vh - 230px)"></div>
         </td></tr>
        </tbody>
        <tfoot>
          <tr>
            <td class='text-center form-actions'>
              <button id="xmindmapSave" type="button" class="btn btn-wide btn-primary"><?php echo $lang->testcase->save;?></button>
              <?php echo $gobackLink ? html::a($gobackLink, $lang->goback, '', 'class="btn btn-wide"') : html::backButton();?>
            </td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>

<div class="modal fade" id="moduleSelector">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php echo $lang->testcase->close;?></span></button>
        <h4 class="modal-title"><?php echo $lang->testcase->moduleSelector;?></h4>
      </div>
      <div class="modal-body">
        <table class='table table-form'>
          <tbody>
            <tr>
              <td>
                <div class='input-group' id='moduleNameBox'>
                  <span class="input-group-addon w-80px"><?php echo $lang->testcase->product;?></span>
                  <?php echo html::input('productName', $product->name, 'disabled', '');?>
                </div>
              </td>
              <td style='padding-left:15px;'>
                <div class='input-group' id='moduleIdBox'>
                  <span class="input-group-addon w-80px"><?php echo $lang->testcase->module;?></span>
                  <?php
                  echo html::select('module', $moduleOptionMenu, "/", "class='form-control chosen'");
                  if(count($moduleOptionMenu) == 1)
                  {
                      echo "<span class='input-group-addon'>";
                      echo html::a($this->createLink('tree', 'browse', "rootID=$productID&view=case&currentModuleID=0&branch=$branch", '', true), $lang->tree->manage, '', "class='text-primary' data-toggle='modal' data-type='iframe' data-width='95%'");
                      echo html::a("javascript:void(0)", $lang->refresh, '', "class='refresh' onclick='loadProductModules($productID)'");
                      echo '</span>';
                  }
                  ?>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang->testcase->close;?></button>
        <button id="sceneProperySave" type="button" class="btn btn-primary"><?php echo $lang->testcase->save;?></button>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
