<?php
/**
 * The trash view file of action module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     action
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <span class='btn btn-link btn-active-text'><span class='text'><?php echo html::icon($lang->icons['trash']);?> <?php echo $lang->action->trash;?></span></span>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php if($type == 'hidden') echo html::a(inLink('trash', "type=all"),    $lang->goback, '', "class='btn'");?>
    <?php if($type == 'all')    echo html::a(inLink('trash', "type=hidden"), "<i class='icon-eye-close'></i> " . $lang->action->dynamic->hidden, '', "class='btn btn-danger'");?>
  </div>
</div>

<div id='mainContent'>
  <div class='main-table' data-ride='table'>
    <table class='table has-sort-head'>
      <?php $vars = "type=$type&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <thead>
        <tr class='colhead'>
          <th class='w-130px'><?php common::printOrderLink('objectType', $orderBy, $vars, $lang->action->objectType);?></th>
          <th class='w-id'>  <?php common::printOrderLink('objectID',   $orderBy, $vars, $lang->idAB);?></th>
          <th><?php echo $lang->action->objectName;?></th>
          <th class='w-100px'><?php common::printOrderLink('actor',     $orderBy, $vars, $lang->action->actor);?></th>
          <th class='w-150px'><?php common::printOrderLink('date',      $orderBy, $vars, $lang->action->date);?></th>
          <th class='w-100px'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($trashes as $action):?>
        <?php $module = $action->objectType == 'case' ? 'testcase' : $action->objectType;?>
        <tr>
          <td><?php echo zget($lang->action->objectTypes, $action->objectType, '');?></td>
          <td><?php echo $action->objectID;?></td>
          <td class='text-left'>
            <?php
            $params     = $action->objectType == 'user' ? "account={$action->objectName}" : "id={$action->objectID}";
            $methodName = 'view';
            if($module == 'caselib')
            {
                $methodName = 'libview';
                $module     = 'testsuite';
            }
            if(strpos(',doclib,module,webhook,', ",{$module},") !== false)
            {
                echo $action->objectName;
            }
            else
            {
                echo html::a($this->createLink($module, $methodName, $params), $action->objectName);
            }
            ?>
          </td>
          <td><?php echo $users[$action->actor];?></td>
          <td><?php echo $action->date;?></td>
          <td>
            <?php
            common::printLink('action', 'undelete', "actionid=$action->id", $lang->action->undelete, 'hiddenwin');
            if($type == 'all') common::printLink('action', 'hideOne',  "actionid=$action->id", $lang->action->hideOne, 'hiddenwin');
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class="table-footer">
      <div class="table-actions btn-toolbar" style=''>
        <?php echo html::linkButton($lang->action->hideAll, inlink('hideAll'), 'hiddenwin');?>
        <span class='text'><?php echo $lang->action->trashTips;?></span>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
