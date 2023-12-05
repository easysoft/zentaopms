<?php
/**
 * The manage privilege by group view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: managepriv.html.php 1517 2011-03-07 10:02:57Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <?php echo $lang->group->managePriv ;?>
      <small class='text-muted'> <?php echo $lang->group->byModuleTips; ?></small>
    </h2>
  </div>

  <form class='table-bymodule pdb-20 form-ajax' method='post' target='hiddenwin'>
    <table class='table table-form'>
      <tr class='text-center'>
        <td class='strong'><?php echo $lang->group->module;?></td>
        <td class='strong'><?php echo $lang->privpackage->common;?></td>
        <td class='strong'><?php echo $lang->group->method;?></td>
        <td class='strong'><?php echo $lang->group->common;?></td>
      </tr>
      <tr class='text-top'>
        <td class='w-p30'><?php echo html::select('module', $modulePairs, '', " size='10' onclick='setModulePackages(this.value)' class='form-control'");?></td>
        <td class='w-p30' id='packageBox'>
          <?php
          $hiddenClass = '';
          foreach($packageGroup as $module => $modulePackages)
          {
              echo html::select('packages[]', $modulePackages, '', "onclick='setActions()' multiple='multiple' class='form-control $hiddenClass' data-module='$module'");
              $hiddenClass = 'hidden';
          }
          ?>
        </td>
        <td class='w-p30' id='actionBox'>
          <?php echo html::select('actions[]', $indexPrivs, '', "multiple='multiple' class='form-control'");?>
        </td>
        <td><?php echo html::select('groups[]', $groups, '', "multiple='multiple' class='form-control'");?></td>
      </tr>
      <tr>
        <td class='text-center form-actions' colspan='4'>
          <?php echo html::submitButton('', "onclick='setNoChecked()'");?>
          <?php echo html::backButton();?>
          <?php echo html::hidden('foo'); // Just make $_POST not empty..?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php js::set('selectedPrivIdList', '[]');?>
<?php js::set('relatedPrivData', '[]');?>
<?php js::set('excludeIdList', '[]');?>
