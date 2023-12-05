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
    <?php foreach($lang->my->featureBar['dynamic'] as $period => $label):?>
    <?php
    $label  = "<span class='text'>$label</span>";
    $active = '';
    if($period == $type)
    {
        $active = 'btn-active-text';
        $label .= " <span class='label label-light label-badge'>{$originTotal}</span>";
    }
    echo html::a(inlink('dynamic', "type=$period"), $label, '', "class='btn btn-link $active' id='{$period}'")
    ?>
    <?php endforeach;?>
  </div>
</div>
<div id="mainContent" class="main-content">
  <div id="dynamics">
    <?php if(empty($dateGroups)):?>
    <div class="table-empty-tip">
      <p><span class="text-muted"><?php echo $lang->action->noDynamic;?></span></p>
    </div>
    <?php else:?>
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
        <?php if($action->action == 'adjusttasktowait') continue;?>
        <?php if(empty($firstAction)) $firstAction = $action;?>
        <?php $class = $action->major ? 'active' : '';?>
        <?php if(in_array($action->action, array('releaseddoc', 'collected'))) $class .= " {$action->action}";?>
        <li <?php if($action->major) echo "class='$class'";?>>
          <div>
            <span class="timeline-tag"><?php echo $action->time?></span>
            <span class="timeline-text">
              <?php echo $app->user->realname;?>
              <span class='label-action'><?php echo $action->actionLabel;?></span>
              <?php if($action->action != 'login' and $action->action != 'logout'):?>
              <?php echo $action->objectLabel;?>
              <?php if($action->objectID):?>
              <span class="label label-id"><?php echo (strpos(',module,chartgroup,', ",$action->objectType,") !== false and strpos(',created,edited,moved,', "$action->action") !== false) ? trim($action->extra, ',') : $action->objectID;?></span>
              <?php endif;?>
              <?php $tab = '';?>
              <?php if($action->objectType == 'meeting') $tab = $action->project ? "data-app='project'" : "data-app='my'";?>
              <span class='label-name'>
              <?php
              if((in_array($config->edition, array('max', 'ipd')) and strpos($config->action->assetType, ",{$action->objectType},") !== false) and empty($action->objectName))
              {
                  echo '#' . $action->objectID;
              }
              elseif(empty($action->objectID) and $action->extra)
              {
                  echo $action->extra;
              }
              elseif(empty($action->objectLink))
              {
                  echo $action->objectName;
              }
              else
              {
                  echo html::a($action->objectLink, $action->objectName, '', $tab);
              }
              ?>
              </span>
              <?php endif;?>
            </span>
          </div>
        </li>
        <?php endforeach;?>
      </ul>
    </div>
    <?php endforeach;?>
    <?php endif;?>
  </div>
</div>
<?php if(!empty($firstAction)):?>
<?php
$firstDate = date('Y-m-d', strtotime($firstAction->originalDate) + 24 * 3600);
$lastDate  = substr($action->originalDate, 0, 10);
$hasPre    = $this->action->hasPreOrNext($firstDate, 'pre');
$hasNext   = $this->action->hasPreOrNext($lastDate, 'next');
$preLink   = $hasPre ? inlink('dynamic', "type=$type&recTotal={$pager->recTotal}&date=" . strtotime($firstDate) . '&direction=pre&originTotal=' . $originTotal) : 'javascript:;';
$nextLink  = $hasNext ? inlink('dynamic', "type=$type&recTotal={$pager->recTotal}&date=" . strtotime($lastDate) . '&direction=next&originTotal=' . $originTotal) : 'javascript:;';
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
