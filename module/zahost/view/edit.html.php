<?php
/**
 * The create view file of zahost module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang<wangjianhua@easycorp.ltd>
 * @package     zahost
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->zahost->editAction;?></h2>
        <icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content="<?php echo $lang->zahost->tips;?>"></icon>
      </div>
      <form method='post' target='hiddenwin' id='ajaxForm' class="load-indicator main-form form-ajax">
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->zahost->vsoft;?></th>
            <td><?php echo html::select('vsoft', $lang->zahost->softwareList, $host->vsoft, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->zahost->zaHostType;?></th>
            <td><?php echo html::select('hostType', $lang->zahost->zaHostTypeList, $host->hostType, "class='form-control chosen'");?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->zahost->name;?></th>
            <td><?php echo html::input('name', $host->name, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->zahost->IP;?></th>
            <td><?php echo html::input('extranet', $host->extranet, "class='form-control' readonly");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->zahost->cpuCores;?></th>
            <td><?php echo html::select('cpuCores', $config->zahost->cpuCoreList, $host->cpuCores, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->zahost->memory;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::input('memory', $host->memory, "class='form-control'");?>
                <span class="input-group-addon" id="memory-addon">GB</span>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->zahost->diskSize;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::input('diskSize', $host->diskSize, "class='form-control'");?>
                <span class='input-group-addon fix-border fix-padding' id='unit'>
                GB
                </span>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->zahost->desc ?></th>
            <td colspan='2'><?php echo html::textarea('desc', $host->desc, "class='form-control'")?></td>
            <td></td>
          </tr>
          <tr>
            <td colspan='2' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
