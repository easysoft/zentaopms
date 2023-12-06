<?php
/**
 * The dynamic view file of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     user
 * @version     $Id: dynamic.html.php 1477 2011-03-01 15:25:50Z wwccss $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php include './featurebar.html.php';?>
<?php js::set('period', $type);?>
<div id='mainContent'>
  <div id='contentNav'>
    <?php foreach($lang->user->featureBar['dynamic'] as $period => $label):?>
    <?php
    $label  = "<span class='text'>$label</span>";
    $active = '';
    if($period == $type)
    {
        $active = 'btn-active-text';
        $label .= " <span class='label label-light label-badge'>{$recTotal}</span>";
    }
    echo html::a(inlink('dynamic', "userID={$user->id}&type=$period"), $label, '', "class='btn btn-link $active' id='{$period}'")
    ?>
    <?php endforeach;?>
  </div>

  <div id="dynamics" class='main-content <?php echo empty($dateGroups) ? '' : 'hasData';?>'>
    <?php if(!empty($dateGroups)):?>
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
        <li <?php if($action->major) echo "class='active'";?>>
          <div>
            <span class="timeline-tag"><?php echo $action->time?></span>
            <span class="timeline-text">
              <?php echo zget($users, $action->actor);?>
              <span class='label-action'><?php echo $action->actionLabel;?></span>
              <span><?php echo $action->objectLabel;?></span>
              <span class="label label-id"><?php echo $action->objectID;?></span>
              <?php if($action->objectName) echo "<sapn class='label-name'>" . (!empty($action->objectLink) ? html::a($action->objectLink, $action->objectName, '', "data-app=''") : $action->objectName) . '</span>';?>
            </span>
          </div>
        </li>
        <?php endforeach;?>
      </ul>
    </div>
    <?php endforeach;?>
    <?php else:?>
    <div class="text-center text-muted">
      <?php echo $lang->action->noDynamic;?>
    </div>
    <?php endif;?>
  </div>
</div>
<?php if(!empty($firstAction)):?>
<?php
$firstDate = date('Y-m-d', strtotime($firstAction->originalDate) + 24 * 3600);
$lastDate  = substr($action->originalDate, 0, 10);
$hasPre    = $this->action->hasPreOrNext($firstDate, 'pre');
$hasNext   = $this->action->hasPreOrNext($lastDate, 'next');
$preLink   = $hasPre ? inlink('dynamic', "userID={$user->id}&type=$type&recTotal={$recTotal}&date=" . strtotime($firstDate) . '&direction=pre') : 'javascript:;';
$nextLink  = $hasNext ? inlink('dynamic', "userID={$user->id}&type=$type&recTotal={$recTotal}&date=" . strtotime($lastDate) . '&direction=next') : 'javascript:;';
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
