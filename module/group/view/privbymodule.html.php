<?php
/**
 * The manage privilege by group view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: managepriv.html.php 1517 2011-03-07 10:02:57Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><i class='icon-lock'></i></span>
    <strong><small class='text-muted'><i class='icon-cog'></i></small> <?php echo $lang->group->managePriv ;?></strong>
    <small class='text-muted'> <?php echo $lang->group->byModuleTips; ?></small>
  </div>
</div>

<form class='form-condensed pdb-20' method='post' target='hiddenwin'>
  <table class='table table-form'>
    <tr class='text-center'>
      <td class='strong'><?php echo $lang->group->module;?></td>
      <td class='strong'><?php echo $lang->group->method;?></td>
      <td class='strong'><?php echo $lang->group->common;?></td>
    </tr>
    <tr valign='top'>
      <td class='w-p30'><?php echo html::select('module', $modules, '', " size='10' onclick='setModuleActions(this.value)' class='form-control'");?></td>
      <td class='w-p30' id='actionBox'>
        <?php
        $class = '';
        foreach($actions as $module => $moduleActions)
        {
            echo html::select('actions[]', $moduleActions, '', "multiple='multiple' class='form-control $class {$module}Actions'");
            $class = 'hidden';
        }
        ?>
      </td>
      <td><?php echo html::select('groups[]', $groups, '', "multiple='multiple' class='form-control'");?></td>
    </tr>
    <tr>
      <td class='text-center' colspan='3'>
        <?php 
        echo html::submitButton($lang->save);
        echo html::linkButton($lang->goback, $this->createLink('group', 'browse'));
        echo html::hidden('foo'); // Just make $_POST not empty..
        ?>
      </td>
    </tr>
  </table>
</form>
