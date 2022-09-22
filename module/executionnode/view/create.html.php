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
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->executionnode->create;?></h2>
  </div>
  <form method='post' target='hiddenwin' id='ajaxForm' class="load-indicator main-form form-ajax">
    <table class='table table-form'>
      <tr>
        <th class='w-120px'><?php echo $lang->executionnode->name;?></th>
        <td><?php echo html::input('name', '', "class='form-control' placeholder=\"{$lang->executionnode->nameValid}\"");?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->executionnode->hostName;?></th>
        <td><?php echo html::select('hostID', $hostPairs, '', "class='form-control chosen'")?></td>
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
        <th><?php echo $lang->executionnode->memory;?></th>
        <td><?php echo html::select('osMemory', $config->executionnode->os->memory, '', "class='form-control chosen'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->executionnode->disk;?></th>
        <td><?php echo html::select('osDisk', $config->executionnode->os->disk, '', "class='form-control chosen'")?></td>
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
        <td colspan="2" class='text-center form-actions'>
          <?php echo html::submitButton();?>
          <?php echo html::a(inlink('browse'), $lang->goback, '', "class='btn btn-wide btn-back'");?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>

