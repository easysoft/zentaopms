<?php
/**
 * The action->dynamic view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: action->dynamic.html.php 1477 2011-03-01 15:25:50Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php foreach($lang->project->featureBar['dynamic'] as $period => $label):?>
    <?php
    $label  = "<span class='text'>$label</span>";
    $active = '';
    if($period == $type)
    {
        $active = 'btn-active-text';
        $label .= " <span class='label label-light label-badge'>{$recTotal}</span>";
    }
    echo html::a(inlink('dynamic', "projectID=$projectID&type=$period"), $label, '', "class='btn btn-link $active' id='{$period}'")
    ?>
    <?php endforeach;?>
    <div class="btn-group">
      <?php
      $withSearch = count($accountPairs) > 8;
      $active     = $param ? 'btn-active-text' : '';
      $current    = $param ? zget($accountPairs, $account, $account) : $lang->execution->viewByUser;
      $current    = "<span class='text'>" . $current . '</span>' . ' <span class="caret"></span>';
      ?>
      <?php echo html::a('###', $current, '', "class='btn btn-link $active' data-toggle='dropdown'");?>
      <div class="dropdown-menu search-list<?php if($withSearch) echo ' search-box-sink';?>" data-ride="searchList">
        <?php if($withSearch):?>
        <div class="input-control search-box has-icon-left has-icon-right search-example">
          <input id="userSearchBox" type="search" autocomplete="off" class="form-control search-input">
          <label for="userSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
          <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
        </div>
        <?php endif;?>
        <div class='list-group'>
          <?php
          $usersPinYin = common::convert2Pinyin($userIdPairs);
          foreach($userIdPairs as $userID => $name)
          {
              if(!$userID) continue;
              $searchKey = $withSearch ? ('data-key="' . zget($usersPinYin, $name, '') . '"') : '';
              echo html::a($this->createLink('project', 'dynamic', "projectID=$projectID&type=account&param=$userID"), $name, '', $searchKey);
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="mainContent" class="main-content">
  <?php if(empty($dateGroups)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->action->noDynamic;?></span></p>
  </div>
  <?php else:?>
  <div id="dynamics">
    <?php $firstAction = '';?>
    <?php foreach($dateGroups as $date => $actions):?>
    <?php $isToday = date(DT_DATE4) == $date;?>
    <div class="dynamic <?php if($isToday) echo 'active';?>">
      <div class="dynamic-date <?php if($type == 'all') echo 'w-200px';?>">
        <?php if($isToday):?>
        <span class="date-label"><?php echo $lang->action->dynamic->today;?></span>
        <?php endif;?>
        <span class="date-text"><?php echo $date;?></span>
        <button type="button" class="btn btn-info btn-icon btn-sm dynamic-btn"><i class="icon icon-caret-down"></i></button>
      </div>
      <ul class="timeline timeline-tag-left <?php if($type == 'all') echo 'margin-l-50px';?>">
        <?php if($direction == 'next') $actions = array_reverse($actions);?>
        <?php foreach($actions as $i => $action):?>
        <?php if(empty($firstAction)) $firstAction = $action;?>
        <?php $class = $action->major ? 'active' : '';?>
        <?php if(in_array($action->action, array('releaseddoc', 'collected'))) $class .= " {$action->action}";?>
        <li <?php if($action->major) echo "class='$class'";?>>
          <div>
            <span class="timeline-tag"><?php echo $action->time?></span>
            <span class="timeline-text">
              <?php echo zget($accountPairs, $action->actor);?>
              <span class='label-action'><?php echo $action->actionLabel;?></span>
              <span class="text"><?php echo $action->objectLabel;?></span>
              <span class="label label-id"><?php echo $action->objectID;?></span>
              <?php if($action->objectName) echo !empty($action->objectLink) ? html::a($action->objectLink, $action->objectName) : $action->objectName;?>
            </span>
          </div>
        </li>
        <?php endforeach;?>
      </ul>
    </div>
    <?php endforeach;?>
  </div>
  <?php endif;?>
</div>
<?php if(!empty($firstAction)):?>
<?php
$firstDate = date('Y-m-d', strtotime($firstAction->originalDate) + 24 * 3600);
$lastDate  = substr($action->originalDate, 0, 10);
$hasPre    = $this->action->hasPreOrNext($firstDate, 'pre');
$hasNext   = $this->action->hasPreOrNext($lastDate, 'next');
$preLink   = $hasPre ? inlink('dynamic', "projectID=$projectID&type=$type&param=$param&recTotal={$pager->recTotal}&date=" . strtotime($firstDate) . '&direction=pre') : 'javascript:;';
$nextLink  = $hasNext ? inlink('dynamic', "projectID=$projectID&type=$type&param=$param&recTotal={$pager->recTotal}&date=" . strtotime($lastDate) . '&direction=next') : 'javascript:;';
?>
<?php if($hasPre or $hasNext):?>
<div id="mainActions" class='main-actions'>
  <nav class="container">
    <a id="prevPage" class="btn btn-info<?php if(!$hasNext) echo ' disabled';?>" href="<?php echo $nextLink;?>"><i class="icon icon-chevron-left"></i></a>
    <a id="nextPage" class="btn btn-info<?php if(!$hasPre) echo ' disabled';?>" href="<?php echo $preLink;?>"><i class="icon icon-chevron-right"></i></a>
  </nav>
</div>
<?php endif;?>
<?php endif;?>
<script>
$(function()
{
    $('#dynamics').on('click', '.dynamic-btn', function()
    {
        $(this).closest('.dynamic').toggleClass('collapsed');
    });
})
</script>
<?php include '../../common/view/footer.html.php';?>
