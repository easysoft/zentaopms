<?php
/**
 * The create view file of vm module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      xiawenlong <xiawenlong@cnezsoft.com>
 * @package     host
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php js::set('hostID', $hostID);?>
<?php js::set('linuxList', $config->zanode->linuxList);?>
<?php js::set('windowsList', $config->zanode->windowsList);?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->zanode->create;?></h2>
        <icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content="<?php echo $lang->zanode->tips;?>"></icon>
      </div>
      <form method='post' target='hiddenwin' id='ajaxForm' class="load-indicator main-form form-ajax">
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->zahost->type;?></th>
            <td><?php echo html::select('hostType', $lang->zanode->typeList, 'virtual', "class='form-control chosen' onchange='zanodeType()'")?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->zanode->hostName;?></th>
            <td id='hostIdBox'><?php echo html::select('parent', $hostPairs, $hostID, "class='form-control chosen'")?></td>
            <td><?php echo html::a($this->createLink('zahost', 'create', array(), '', true), $lang->zahost->create, '', "class='text-primary iframe'");?></td>
          </tr>
          <tr>
            <th class='w-120px'><?php echo $lang->zanode->name;?></th>
            <td class='p-25f'><?php echo html::input('name', '', "class='form-control' placeholder=\"{$lang->zanode->nameValid}\"");?></td>
          </tr>
          <tr class='hidden'>
            <th class='w-120px'><?php echo $lang->zahost->IP;?></th>
            <td class='p-25f required'><?php echo html::input('extranet', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->zanode->image;?></th>
            <td id="template"><?php echo html::select('image', '', '', "class='form-control chosen'")?></td>
          </tr>
          <tr>
            <th><?php echo $lang->zanode->cpuCores;?></th>
            <td><?php echo html::select('cpuCores', $config->zanode->os->cpuCores, '', "class='form-control chosen'")?></td>
          </tr>
          <tr>
            <th><?php echo $lang->zanode->memory;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::input('memory', '', "class='form-control'");?>
                <span class="input-group-addon" id="memory-addon">GB</span>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->zanode->diskSize;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::input('diskSize', '', "class='form-control'");?>
                <span class='input-group-addon fix-border fix-padding' id='unit'>
                GB
                </span>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->zanode->osName;?></th>
            <td>
              <?php echo html::input('osName', '', "class='form-control' readonly='readonly' onchange='zanodeOsChange()'")?>
              <div id="osNamePhysicsContainer" class="hidden">
                <?php echo html::select('', $config->zanode->osType, 'linux', "class='form-control' id='osNamePhysicsPre'") . html::select('osNamePhysics', $config->zanode->linuxList, '', "class='form-control chosen'"); ?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->zanode->desc ?></th>
            <td colspan='2'><?php echo html::textarea('desc', '', "class='form-control'")?></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="3" class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php echo html::a(inlink('browse'), $lang->goback, '', "class='btn btn-wide btn-back'");?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
