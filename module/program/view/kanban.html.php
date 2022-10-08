<?php
/**
 * The html product kanban file of kanban method of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2021-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     ZenTaoPMS
 * @version     $Id
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kanban.html.php';?>
<?php if(empty($kanbanGroup)):?>
<div class="table-empty-tip cell">
  <p class="text-muted"><?php echo $lang->program->noProgram;?></p>
</div>
<?php else:?>
<div id='kanbanList'>
  <?php foreach($kanbanGroup as $type => $programs):?>
  <?php if(empty($programs)) continue;?>
  <div class='panel kanban-panel'>
    <div class='panel-heading'>
      <strong><?php echo $lang->program->kanban->typeList[$type];?></strong>
    </div>
    <div class='panel-body'>
      <div id='kanban-<?php echo $type;?>' class='kanban'></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php
$kanbanColumns = array();
$kanbanColumns['unclosedProduct'] = array('name' => $lang->program->kanban->openProducts, 'type' => 'unclosedProduct');
$kanbanColumns['unexpiredPlan']   = array('name' => $lang->program->kanban->unexpiredPlans, 'type' => 'unexpiredPlan');
$kanbanColumns['waitProject']     = array('name' => $lang->program->kanban->waitingProjects, 'type' => 'waitProject');
$kanbanColumns['doingProject']    = array('name' => $lang->program->kanban->doingProjects, 'type' => 'doingProject');
$kanbanColumns['doingExecution']  = array('name' => $lang->program->kanban->doingExecutions, 'type' => 'doingExecution');
$kanbanColumns['normalRelease']   = array('name' => $lang->program->kanban->normalReleases, 'type' => 'normalRelease');
$userPrivs = array();
$userPrivs['product']     = common::hasPriv('product', 'browse');
$userPrivs['productplan'] = common::hasPriv('productplan', 'view');
$userPrivs['project']     = common::hasPriv('project', 'index');
$userPrivs['execution']   = common::hasPriv('execution', 'task');
$userPrivs['release']     = common::hasPriv('release', 'view');
js::set('kanbanColumns', array_values($kanbanColumns));
js::set('userPrivs',     $userPrivs);
js::set('kanbanGroup',   $kanbanGroup);
js::set('doingText',     $lang->program->statusList['doing']);
?>
<?php endif; ?>
<?php include '../../common/view/footer.html.php';?>
