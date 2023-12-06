<?php
/**
 * The create vm image view file of zahost module of ZenTaoPMS.
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
<?php js::set('zahostConfig', $config->zahost);?>
<?php js::set('zahostLang', $lang->zahost);?>
<?php js::set('hostID', $host->hostID);?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->zahost->image->createImage;?></h2>
  </div>
  <form method='post' id='ajaxForm' class="load-indicator main-form form-ajax">
    <table class='table table-form'>
      <tr>
        <th class='w-150px'><?php echo $lang->zahost->common;?></th>
        <td class='w-300px'><?php echo $host->name;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->zahost->image->choseImage;?></th>
        <td><?php echo html::select('address', $imageOptions, '', "class='form-control chosen'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->zahost->name;?></th>
        <td><?php echo html::input('name', '', "class='form-control'");?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->zahost->memory;?></th>
        <td>
          <div class='input-group'>
            <?php echo html::input('memory', '', "class='form-control'");?>
            <span class='input-group-addon'>MB</span>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->zahost->disk;?></th>
        <td>
          <div class='input-group'>
            <?php echo html::input('disk', '', "class='form-control'");?>
            <span class='input-group-addon' id='unit'>GB</span>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->zahost->image->osCategory;?></th>
        <td><?php echo html::select('osCategory', $config->zahost->os->list, '', "class='form-control chosen'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->zahost->image->osType;?></th>
        <td><?php echo html::select('osType', '', '', "class='form-control chosen'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->zahost->image->osVersion;?></th>
        <td><?php echo html::select('osVersion', '', '', "class='form-control chosen'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->zahost->image->osLang;?></th>
        <td><?php echo html::select('osLang', $lang->zahost->langList, '', "class='form-control chosen'")?></td>
      <tr>
        <td colspan='2' class='text-center form-actions'>
          <?php echo html::submitButton();?>
          <?php echo html::backButton();?>
          <?php echo html::hidden('hostID', $host->id);?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
