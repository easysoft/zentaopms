<?php
/**
 * The manage privilege by group view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: managepriv.html.php 1517 2011-03-07 10:02:57Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<form method='post' target='hiddenwin'>
  <table class='table-6 a-center' align='center'> 
    <caption class='caption-tl'><?php echo $lang->group->managePriv;?></caption>
    <tr class='colhead'>
      <th><?php echo $lang->group->module;?></th>
      <th><?php echo $lang->group->method;?></th>
      <th><?php echo $lang->group->common;?></th>
    </tr>  
    <tr valign='top'>
      <td><?php echo html::select('module', $modules, '', " size='10' onclick='setModuleActions(this.value)'");?></td>
      <td id='actionBox'>
        <?php
        $class = '';
        foreach($actions as $module => $moduleActions)
        {
            echo html::select('actions[]', $moduleActions, '', "multiple='multiple' class='$class {$module}Actions'");
            $class = 'hidden';
        }
        ?>
      </td>
      <td><?php echo html::select('groups[]', $groups, '', "multiple='multiple'");?></td>
    </tr>
    <tr>
      <td class='a-center' colspan='3'>
        <?php 
        echo html::submitButton($lang->save);
        echo html::linkButton($lang->goback, $this->createLink('group', 'browse'));
        echo html::hidden('foo'); // Just make $_POST not empty..
        ?>
      </td>
    </tr>
  </table>
</form>
