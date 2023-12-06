<?php
/**
 * The create view file of vm module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      xiawenlong <xiawenlong@cnezsoft.com>
 * @package     host
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->zanode->editAction;?></h2>
        <icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content="<?php echo $lang->zanode->tips;?>"></icon>
      </div>
      <form method='post' target='hiddenwin' id='ajaxForm' class="load-indicator main-form form-ajax">
        <table class='table table-form'>
          <?php if($zanode->hostType != 'physics'):?>
          <tr>
            <th><?php echo $lang->zanode->hostName;?></th>
            <td class='p-25f'><?php echo html::input('', $host->name, "class='form-control'  readonly='readonly'");?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th class='w-120px'><?php echo $lang->zanode->name;?></th>
            <td class='p-25f'><?php echo html::input('name', $zanode->name, "class='form-control' placeholder=\"{$lang->zanode->nameValid}\" " . ($zanode->hostType != 'physics' ? "readonly='readonly'" : ''));?></td>
            <td></td>
          </tr>
          <?php if($zanode->hostType != 'physics'):?>
          <tr>
            <th><?php echo $lang->zanode->image;?></th>
            <td class='p-25f'><?php echo html::input('', $image->name, "class='form-control'  readonly='readonly'");?></td>
          </tr>
          <?php else:?>
          <tr>
            <th class='w-120px'><?php echo $lang->zanode->IP;?></th>
            <td class='p-25f'><?php echo html::input('extranet', $zanode->extranet, "class='form-control' readonly='readonly'");?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->zanode->cpuCores;?></th>
            <td class='p-25f'><?php echo html::input('', zget($config->zanode->os->cpuCores, $zanode->cpuCores), "class='form-control'  readonly='readonly'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->zanode->memory;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::input('memory', $zanode->memory, "class='form-control' readonly='readonly'");?>
                <span class="input-group-addon" id="memory-addon">GB</span>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->zanode->diskSize;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::input('diskSize', $zanode->diskSize, "class='form-control' readonly='readonly'");?>
                <span class='input-group-addon fix-border fix-padding' id='unit'>
                GB
                </span>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->zanode->osName;?></th>
            <td><?php echo html::input('osName',  $zanode->hostType != 'physics' ? $zanode->osName : zget($config->zanode->linuxList, $zanode->osName, zget($config->zanode->windowsList, $zanode->osName)), "class='form-control' readonly='readonly'")?></td>
          </tr>
          <tr>
            <th><?php echo $lang->zanode->desc ?></th>
            <td colspan='2'><?php echo html::textarea('desc', $zanode->desc, "class='form-control'")?></td>
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

