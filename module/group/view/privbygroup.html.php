<?php
/**
 * The manage privilege by group view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: managepriv.html.php 1517 2011-03-07 10:02:57Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<form class='form-condensed' method='post' target='hiddenwin'>
  <div id='featurebar'>
    <div class='heading'><i class='icon-lock'> <?php echo $group->name;?></i></div>
    <ul class='nav'>
      <?php $params = "type=byGroup&param=$groupID&menu=%s&version=$version";?>
      <li <?php echo empty($menu) ? "class='active'" : ""?>>
        <?php echo html::a(inlink('managePriv', sprintf($params, '')), $lang->group->all)?>
      </li>

      <?php foreach($lang->menu as $module => $title):?>
      <?php if(!is_string($title)) continue;?>
      <li <?php echo $menu == $module ? "class='active'" : ""?>>
        <?php echo html::a(inlink('managePriv', sprintf($params, $module)), substr($title, 0, strpos($title, '|')))?>
      </li>
      <?php endforeach;?>

      <li <?php echo $menu == 'other' ? "class='active'" : "";?>>
        <?php echo html::a(inlink('managePriv', sprintf($params, 'other')), $lang->group->other);?>
      </li>

      <li class='w-150px'><?php echo html::select('version', $this->lang->group->versions, $version, "onchange=showPriv(this.value) class='form-control chosen'");?></li>
    </ul>
  </div>
  <table class='table table-hover table-striped table-bordered table-form'> 
    <thead>
      <tr>
        <th><?php echo $lang->group->module;?></th>
        <th><?php echo $lang->group->method;?></th>
      </tr>
    </thead>
    <?php foreach($lang->resource as $moduleName => $moduleActions):?>
    <?php if(!count((array)$moduleActions)) continue;?>
    <?php if(!$this->group->checkMenuModule($menu, $moduleName)) continue;?>
    <?php
    /* Check method in select version. */
    if($version)
    {
        $hasMethod = false;
        foreach($moduleActions as $action => $actionLabel)
        {
            if(strpos($changelogs, ",$moduleName-$actionLabel,") !== false)
            {
                $hasMethod = true;
                break;
            }
        }
        if(!$hasMethod) continue;
    }
    ?>
    <tr class='<?php echo cycle('even, bg-gray');?>'>
      <th class='text-right w-150px'><?php echo $this->lang->$moduleName->common;?><?php echo html::selectAll($moduleName, 'checkbox')?></th>
      <td id='<?php echo $moduleName;?>' class='pv-10px'>
        <?php $i = 1;?>
        <?php if($moduleName == 'caselib') $moduleName = 'testsuite';?>
        <?php foreach($moduleActions as $action => $actionLabel):?>
        <?php if(!empty($version) and strpos($changelogs, ",$moduleName-$actionLabel,") === false) continue;?>
        <div class='group-item'>
          <input type='checkbox' name='actions[<?php echo $moduleName;?>][]' value='<?php echo $action;?>' <?php if(isset($groupPrivs[$moduleName][$action])) echo "checked";?> />
          <span class='priv' id="<?php echo $moduleName . '-' . $actionLabel;?>"><?php echo $lang->$moduleName->$actionLabel;?></span>
        </div>
        <?php endforeach;?>
      </td>
    </tr>
    <?php endforeach;?>
    <tr>
      <th class='text-right'><?php echo $lang->selectAll . html::selectAll('', 'checkbox')?></th>
      <td>
        <?php 
        echo html::submitButton($lang->save, "onclick='setNoChecked()'");
        echo html::linkButton($lang->goback, $this->createLink('group', 'browse'));
        echo html::hidden('foo'); // Just a hidden var, to make sure $_POST is not empty.
        echo html::hidden('noChecked'); // Save the value of no checked.
        ?>
      </td>
    </tr>
  </table>
</form>
<script type='text/javascript'>
var groupID = <?php echo $groupID?>;
var menu    = "<?php echo $menu?>";
</script>
