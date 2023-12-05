<?php
/**
 * The trash view file of action module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     action
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php $activeClass = $currentObjectType == 'all' ? 'btn-active-text' : '';?>
    <?php echo html::a($this->createLink('action', 'trash', "objectType=all&type=$type"), "<span class='text'>" . $lang->all . "</span>", '', "class='btn btn-link $activeClass'");?>
    <?php
    /* Output the objectType order by preferredTypeConfig. */
    foreach($preferredTypeConfig as $objectType)
    {
        if(in_array($objectType, $preferredType))
        {
            $activeClass = $objectType == $currentObjectType ? 'btn-active-text' : '';
            echo html::a($this->createLink('action', 'trash', "objectType=$objectType&type=$type"), "<span class='text'>" . zget($lang->action->objectTypes, $objectType) . "</span>", '', "class='btn btn-link $activeClass'");
            unset($preferredType[$objectType]);
        }
    }

    /* Output the remaining types which transformed from more type. */
    foreach($preferredType as $objectType)
    {
        $activeClass = $objectType == $currentObjectType ? 'btn-active-text' : '';
        echo html::a($this->createLink('action', 'trash', "objectType=$objectType&type=$type"), "<span class='text'>" . zget($lang->action->objectTypes, $objectType) . "</span>", '', "class='btn btn-link $activeClass'");
    }

    /* Output the more types. */
    if(!empty($moreType))
    {
        $moreLabel       = $lang->more;
        $moreLabelActive = '';
        if(in_array($currentObjectType, $moreType))
        {
            $moreLabel       = "<span class='text'>" . zget($lang->action->objectTypes, $currentObjectType) . "</span>";
            $moreLabelActive = 'btn-active-text';
        }
        echo '<div class="btn-group" id="more">';
        echo html::a('javascript:;', $moreLabel . " <span class='caret'></span>", '', "data-toggle='dropdown' class='btn btn-link $moreLabelActive'");
        echo "<ul class='dropdown-menu'>";
        foreach($moreType as $objectType)
        {
            $activeClass = $objectType == $currentObjectType ? 'btn-active-text' : '';
            $objectName  = zget($lang->action->objectTypes, $objectType, '');
            if(empty($objectName)) continue;

            echo '<li>' . html::a($this->createLink('action', 'trash', "objectType=$objectType&type=$type"), "<span class='text'>" . $objectName . "</span>", '', "class='btn btn-link $activeClass'") . '</li>';
        }
        echo '</ul></div>';
    }
    ?>
    <?php if($currentObjectType != 'all'):?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->action->byQuery;?></a>
    <?php endif;?>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php if($type == 'hidden') echo html::a(inLink('trash', "browseType=all&type=all"),    $lang->goback, '', "class='btn'");?>
    <?php if($type == 'all')    echo html::a(inLink('trash', "browseType=all&type=hidden"), "<i class='icon-eye-close'></i> " . $lang->action->dynamic->hidden, '', "class='btn btn-danger'");?>
  </div>
</div>
<div class="cell<?php if($byQuery) echo ' show';?>" id="queryBox" data-module='trash'></div>
<div id='mainContent' class="main-row">
  <?php if(empty($trashes)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->noData;?></span></p>
  </div>
  <?php else:?>
  <div class='main-table' data-ride='table'>
    <table class='table has-sort-head'>
      <?php $vars = "browseType=$currentObjectType&type=$type&byQuery=$byQuery&queryID=$queryID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <thead>
        <tr class='colhead'>
          <th class='c-object-type'><?php common::printOrderLink('objectType', $orderBy, $vars, $lang->action->objectType);?></th>
          <th class='c-id'><?php common::printOrderLink('objectID', $orderBy, $vars, $lang->idAB);?></th>
          <th><?php echo $lang->action->objectName;?></th>
          <?php if($currentObjectType == 'execution'):?>
          <th class='w-250px'><?php echo $this->lang->project->project;?></th>
          <?php endif;?>
          <?php if(strpos(',story,requirement,', ",$currentObjectType,") !== false):?>
          <th class='w-250px'><?php echo $this->lang->story->product;?></th>
          <?php endif;?>
          <?php if($currentObjectType == 'task'):?>
          <th class='w-250px'><?php echo $this->lang->task->execution;?></th>
          <?php endif;?>
          <th class='c-user'><?php common::printOrderLink('actor', $orderBy, $vars, $lang->action->actor);?></th>
          <th class='c-full-date'><?php common::printOrderLink('date', $orderBy, $vars, $lang->action->date);?></th>
          <th class='c-actions'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($trashes as $action):?>
        <?php $module = $action->objectType == 'case' ? 'testcase' : $action->objectType;?>
        <tr>
          <td><?php echo zget($lang->action->objectTypes, $action->objectType, '');?></td>
          <td><?php echo $action->objectID;?></td>
          <?php if($action->objectType == 'scene'): ?>
          <td class='text-left'>
            <?php echo $action->objectName;?>
          </td>
          <?php else: ?>
          <td class='text-left'>
            <?php
            $params     = $action->objectType == 'user' ? "account={$action->objectName}" : "id={$action->objectID}";
            $methodName = 'view';
            if($module == 'caselib')
            {
                $methodName = 'view';
                $module     = 'caselib';
            }
            if($module == 'basicmeas')
            {
                $module     = 'measurement';
                $methodName = 'setSQL';
                $params     = "id={$action->objectID}";
            }
            if($module == 'deploystep')
            {
                $module     = 'deploy';
                $methodName = 'viewStep';
            }
            if($action->objectType == 'api')
            {
                $params     = "libID=0&moduelID=0&apiID={$action->objectID}";
                $methodName = 'index';
            }
            if(strpos('traincourse,traincontents', $module) !== false)
            {
                $methodName = $module == 'traincourse' ? 'viewcourse' : 'viewchapter';
                $module     = 'traincourse';
            }
            if(isset($config->action->customFlows[$action->objectType]))
            {
                $flow   = $config->action->customFlows[$action->objectType];
                $module = $flow->module;
            }
            if(strpos($this->config->action->noLinkModules, ",{$module},") !== false)
            {
                echo $action->objectName;
            }
            else
            {
                $tab     = '';
                $canView = common::hasPriv($module, $methodName);
                if($action->objectType == 'meeting') $tab = $action->project ? "data-app='project'" : "data-app='my'";
                if($module == 'requirement') $module = 'story';
                echo $canView ? html::a($this->createLink($module, $methodName, $params), $action->objectName, '_self', "title='{$action->objectName}' $tab") : "<span title='$action->objectName'>$action->objectName</span>";
            }
            ?>
          </td>
        <?php endif ?>
          <?php if($currentObjectType == 'execution'):?>
          <td class="c-name flex" title="<?php echo $projectList[$action->project]->name;?>">
            <span class="text-ellipsis"><?php echo $projectList[$action->project]->name;?></span>
            <?php if($projectList[$action->project]->deleted):?>
            <span class='label label-danger'><?php echo $this->lang->project->deleted;?></span>
            <?php endif;?>
          </td>
          <?php endif;?>
          <?php if(strpos(',story,requirement,', ",$currentObjectType,") !== false):?>
          <td class="c-name flex" title="<?php echo $productList[$action->objectID]->productTitle;?>">
            <span class="text-ellipsis"><?php echo $productList[$action->objectID]->productTitle;?></span>
            <?php if($productList[$action->objectID]->productDeleted):?>
            <span class='label label-danger'><?php echo $this->lang->story->deleted;?></span>
            <?php endif;?>
          </td>
          <?php endif;?>
          <?php if($currentObjectType == 'task'):?>
          <td class="c-name flex" title="<?php echo $executionList[$action->execution]->name;?>">
            <span class="text-ellipsis"><?php echo $executionList[$action->execution]->name;?></span>
            <?php if($executionList[$action->execution]->deleted):?>
            <span class='label label-danger'><?php echo $this->lang->execution->deleted;?></span>
            <?php endif;?>
          </td>
          <?php endif;?>
          <td><?php echo zget($users, $action->actor);?></td>
          <td><?php echo $action->date;?></td>
          <td>
            <?php
            common::printLink('action', 'undelete', "actionid=$action->id&browseType=$currentObjectType", $lang->action->undelete, 'hiddenwin');
            if($type == 'all') common::printLink('action', 'hideOne',  "actionid=$action->id&browseType=$currentObjectType", $lang->action->hideOne, 'hiddenwin');
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class="table-footer">
      <div class="table-actions btn-toolbar" style=''>
        <?php echo html::linkButton($lang->action->hideAll, inlink('hideAll'), 'hiddenwin');?>
        <span class='table-statistic'><?php echo $lang->action->trashTips;?></span>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
