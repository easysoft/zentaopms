<?php
/**
 * The create view file of vm module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      xiawenlong <xiawenlong@cnezsoft.com>
 * @package     host
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->executionnode->create;?></h2>
      </div>
      <form method='post' target='hiddenwin' id='ajaxForm' class="load-indicator main-form form-ajax">
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->executionnode->hostName;?></th>
            <td><?php echo html::select('hostID', $hostPairs, '', "class='form-control chosen'")?></td>
          </tr>
          <tr>
            <th class='w-120px'><?php echo $lang->executionnode->name;?></th>
            <td class='p-25f'><?php echo html::input('name', '', "class='form-control' placeholder=\"{$lang->executionnode->nameValid}\"");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->executionnode->vmTemplate;?></th>
            <td id="template"><?php echo html::select('templateID', '', '', "class='form-control chosen'")?></td>
          </tr>
          <tr>
            <th><?php echo $lang->executionnode->cpu;?></th>
            <td><?php echo html::select('osCpu', $config->executionnode->os->cpu, '', "class='form-control chosen'")?></td>
          </tr>
          <tr>
            <th><?php echo $lang->executionnode->osMemory;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::input('osMemory', '', "class='form-control'");?>
                <span class="input-group-addon"><?php echo $lang->zahost->unitList['GB'];?></span>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->executionnode->osDisk;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::input('osDisk', '', "class='form-control'");?>
                <span class='input-group-addon fix-border fix-padding' id='unit'>
                  <?php echo html::select('unit', $lang->zahost->unitList, 'GB', "class='form-control chosen w-50px'");?>
                </span>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->executionnode->osCategory;?></th>
            <td><?php echo html::input('osCategory', '', "class='form-control' disabled")?></td>
          </tr>
          <tr>
            <th><?php echo $lang->executionnode->osType;?></th>
            <td><?php echo html::input('osType', '', "class='form-control' disabled")?></td>
          </tr>
          <tr>
            <th><?php echo $lang->executionnode->osVersion;?></th>
            <td><?php echo html::input('osVersion', '', "class='form-control' disabled")?></td>
          </tr>
          <tr>
            <th><?php echo $lang->executionnode->osLang;?></th>
            <td><?php echo html::input('osLang', '', "class='form-control' disabled")?></td>
          </tr>
          <tr>
            <th><?php echo $lang->executionnode->desc ?></th>
            <td colspan='2'><?php echo html::textarea('desc', '', "class='form-control'")?></td>
            <td></td>
          </tr>
          <tr>
            <td colspan="2" class='text-center form-actions'>
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

