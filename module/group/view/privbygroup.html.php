<?php
/**
 * The manage privilege by group view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: managepriv.html.php 1517 2011-03-07 10:02:57Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<form method='post' target='hiddenwin'>
  <table class='table-1 a-left'> 
    <caption class='caption-tl'><?php echo $group->name . $lang->colon . $lang->group->managePriv;?>
      <span>
        <?php 
        echo html::select('version', $this->lang->group->versions, '', "onchange=showPriv(this.value)");
        ?>
      </span>
    </caption>
    <tr class='colhead'>
      <th><?php echo $lang->group->module;?></th>
      <th><?php echo $lang->group->method;?></th>
    </tr>  
    <?php foreach($lang->resource as $moduleName => $moduleActions):?>
    <tr class='f-14px <?php echo cycle('even, bg-yellow');?>'>
      <th class='a-right'><?php echo $this->lang->$moduleName->common;?> <input type='checkbox' onclick='checkall(this, "<?php echo $moduleName;?>")'></td>
      <td id='<?php echo $moduleName;?>' class='pv-10px'>
        <?php $i = 1;?>
        <?php foreach($moduleActions as $action => $actionLabel):?>
        <div class='w-p20 f-left'>
          <input type='checkbox' name='actions[<?php echo $moduleName;?>][]' value='<?php echo $action;?>' <?php if(isset($groupPrivs[$moduleName][$action])) echo "checked";?> />
          <span class='priv' id=<?php echo $moduleName . '-' . $actionLabel;?>><?php echo $lang->$moduleName->$actionLabel;?></span>
        </div>
        <?php if(($i %  4) == 0) echo "<div class='c-both'></div>"; $i ++;?>
        <?php endforeach;?>
      </td>
    </tr>
    <?php endforeach;?>
    <tr>
      <th class='rowhead'><?php echo $lang->selectAll;?><input type='checkbox' onclick='checkall(this);'></th>
      <td class='a-center'>
        <?php 
        echo html::submitButton($lang->save);
        echo html::linkButton($lang->goback, $this->createLink('group', 'browse'));
        echo html::hidden('foo'); // Just a hidden var, to make sure $_POST is not empty.
        ?>
      </td>
    </tr>
  </table>
</form>
<script type='text/javascript'>
    var newPriv = <?php echo json_encode($changelogs)?>;
    var version = "<?php echo $this->config->version?>";
</script>
