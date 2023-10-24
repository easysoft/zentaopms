<style>
.types-line {display: table; padding: 0; margin: 0 auto;}
.types-line > li {display: table-cell;}
.types-line > li > div {position: relative; padding: 18px 5px 5px; text-align: center;}
.types-line > li > div:before {content: ''; display: block; background: #eee;  width: 100%; height: 2px; position: absolute; left: 50%; top: 4px;}
.types-line > li:last-child > div:before {display: none;}
.types-line > li > div:after {content: ''; display: block; background: #FFFFFF; box-shadow: 0 2px 4px 0 rgba(170,170,170,0.50), 0 0 5px 0 rgba(0,0,0,0.1); width: 10px; height: 10px; position: absolute; border-radius: 50%; top: 0; left: 50%; margin-left: -2px;}
.types-line > li > div > small {display: block; color: #A6AAB8;}
.types-line > li > div > span {display: block; color: #CBD0DB; font-size: 16px;}
.product-info {position: relative; height: 65px;}
.product-info + .product-info {margin-top: 10px;}
.product-info .progress {position: absolute; left: 10px; top: 35px; right: 100px;}
.product-info .progress-info {position: absolute; left: 8px; top: 10px; width: 180px; font-size: 12px;}
.product-info .type-info {color: #A6AAB8; text-align: center; position: absolute; right: 0; top: 6px; width: 100px;}
html[lang="en"] .product-info .type-info {color: #A6AAB8; text-align: center; position: absolute; right: 0; top: 6px; width: 110px;}
.product-info .type-value,
.product-info .type-label {font-size: 12px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;}
.product-info .type-value {font-size: 14px;}
.product-info .type-value > strong {font-size: 20px; color: #3C4353;}
.product-info .actions {position: absolute; left: 10px; top: 14px;}
.block-statistic .panel-body {padding-top: 0}
.block-statistic .tile {margin-bottom: 30px;}
.block-statistic .tile-title {font-size: 18px; color: #A6AAB8;}
.block-statistic .tile-amount {font-size: 48px; margin-bottom: 10px;}
.block-statistic .col-nav {border-right: 1px solid #EBF2FB; width: 210px; padding: 0;}
.block-statistic .nav-secondary > li {position: relative;}
.block-statistic .nav-secondary > li > a {font-size: 14px; color: #838A9D; position: relative; box-shadow: none; padding-left: 20px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; transition: all .2s;}
.block-statistic .nav-secondary > li > a:first-child {padding-right: 36px;}
.block-statistic .nav-secondary > li.active > a:first-child {color: #3C4353; background: transparent; box-shadow: none;}
.block-statistic .nav-secondary > li.active > a:first-child:hover,
.block-statistic .nav-secondary > li.active > a:first-child:focus,
.block-statistic .nav-secondary > li > a:first-child:hover {box-shadow: none; border-radius: 4px 0 0 4px;}
.block-statistic .nav-secondary > li.active > a:first-child:before {content: ' '; display: block; left: -1px; top: 10px; bottom: 10px; width: 4px; background: #006af1; position: absolute;}
.block-statistic .nav-secondary > li > a.btn-view {position: absolute; top: 0; right: 0; bottom: 0; padding: 8px; width: 36px; text-align: center; opacity: 0;}
.block-statistic .nav-secondary > li:hover > a.btn-view {opacity: 1;}
.block-statistic .nav-secondary > li.active > a.btn-view {box-shadow: none;}
.block-statistic .nav-secondary > li.switch-icon {display: none;}
.block-statistic.block-sm .panel-body {padding-bottom: 10px; position: relative; padding-top: 45px; border-radius: 3px;}
.block-statistic.block-sm .panel-body > .table-row,
.block-statistic.block-sm .panel-body > .table-row > .col {display: block; width: auto;}
.block-statistic.block-sm .panel-body > .table-row > .tab-content {padding: 0; margin: 0 -5px;}
.block-statistic.block-sm .tab-pane > .table-row > .col-5 {width: 125px;}
.block-statistic.block-sm .tab-pane > .table-row > .col-5 > .table-row {padding: 5px 0;}
.block-statistic.block-sm .col-nav {border-left: none; position: absolute; top: 0; left: 15px; right: 15px; background: #f5f5f5;}
.block-statistic.block-sm .nav-secondary {display: table; width: 100%; padding: 0; table-layout: fixed;}
.block-statistic.block-sm .nav-secondary > li {display: none;}
.block-statistic.block-sm .nav-secondary > li.switch-icon,
.block-statistic.block-sm .nav-secondary > li.active {display: table-cell; width: 100%; text-align: center;}
.block-statistic.block-sm .nav-secondary > li.active > a:hover {cursor: default; background: none;}
.block-statistic.block-sm .nav-secondary > li.switch-icon > a:hover {background: rgba(0, 0, 0, 0.07);}
.block-statistic.block-sm .nav-secondary > li > a {padding: 5px 10px; border-radius: 4px;}
.block-statistic.block-sm .nav-secondary > li > a:before {display: none;}
.block-statistic.block-sm .nav-secondary > li.switch-icon {width: 40px;}
.block-statistic.block-sm .nav-secondary > li.active > a:first-child:before {display: none}
.block-statistic.block-sm .nav-secondary > li.active > a.btn-view {width: auto; left: 0; right: 0;}
.block-statistic.block-sm .nav-secondary > li.active > a.btn-view > i {display: none;}
.block-statistic.block-sm .nav-secondary > li.active > a.btn-view:hover {cursor: pointer; background: rgba(0,0,0,.1);}
.block-statistic .program-info .info span+span {margin-left: 15px;}
.block-statistic .project-info {margin-top: 25px;}
.block-statistic .project-info .col-xs-5, .block-statistic .project-info .col-xs-7 {margin-top: 8px;}
.block-statistic .project-info .col-xs-5 {padding-left: 0;}

.block-statistic .data {width: 40%; text-align: left; padding: 10px 0px; font-size: 14px; font-weight: 700;}
.block-statistic .dataTitle {width: 60%; text-align: right; padding: 10px 0px; font-size: 14px;}
.block-statistic .executionName {padding: 2px 10px; font-size: 14px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;}
.block-statistic .lastIteration {padding-top: 6px;}
.block-statistic .progress-text-left {margin-right: 90px}
.block-statistic .progress-text-left .progress-text {padding-top: 2px; font-size: 14px; padding-right:5px; left: -50px;}

.status-count {margin: auto;}
.status-count tr:first-child td:last-child {color: #000; font-weight: bold;}

.block-statistic .project-info {margin-left: 3px;}
.block-statistic .project-budget {white-space: nowrap;}
.block-statistic .progress-group {margin-top: 10px;}
.block-statistic .progress-group .col {padding-left: 0;}
.block-statistic .progress-percent {margin-top: 4px;}
.block-statistic .weekly-title {font-weight: bold; color: #3C4253;}
.block-statistic .weekly-small {font-size:12px; color: #838A9D;}
.block-statistic .weekly-progress {font-weight: bold; font-size:24px;}
.block-statistic .weekly-name {font-size: 14px; color: #838A9D;}
.block-statistic .weekly-value {font-size: 14px;}
.block-statistic .col-12 .stage {margin-left: 10px;}
.block-statistic .col-12 .waterfall-title {padding-top: 3px;}
.block-statistic .col-12 .waterfall-value {padding-top: 10px; font-size: 18px; font-weight: 600;}
.block-statistic .col-12 .waterfall-title .col {padding-right: 0px; padding-left: 0px;}

.forty-percent {width: 40%;}
</style>
<script>
<?php $blockNavId = 'nav-' . uniqid(); ?>
$(function()
{
    var $nav = $('#<?php echo $blockNavId;?>');
    $nav.on('click', '.switch-icon', function(e)
    {
        var isPrev = $(this).is('.prev');
        var $activeItem = $nav.children('.active');
        var $next = $activeItem[isPrev ? 'prev' : 'next']('li:not(.switch-icon)');
        if ($next.length) $next.find('a[data-toggle="tab"]').trigger('click');
        else $nav.children('li:not(.switch-icon)')[isPrev ? 'last' : 'first']().find('a[data-toggle="tab"]').trigger('click');
        e.preventDefault();
    });

    var $projectList = $('#activeProject');
    if($projectList.length)
    {
        var projectList = $projectList[0];
        $(".col ul.nav").animate({scrollTop: projectList.offsetTop}, "slow");
    }
});
</script>
<div class="panel-body">
  <div class="table-row">
    <?php if(empty($projects)):?>
    <div class="table-empty-tip">
      <p><span class="text-muted"><?php echo $lang->block->emptyTip;?></span></p>
    </div>
    <?php else:?>
    <div class="col col-nav">
      <ul class="nav nav-stacked nav-secondary scrollbar-hover" id='<?php echo $blockNavId;?>'>
        <li class='switch-icon prev'><a><i class='icon icon-arrow-left'></i></a></li>
        <?php $selected = key($projects);?>
        <?php foreach($projects as $project):?>
        <li <?php if($project->id == $selected) echo "class='active' id='activeProject'";?> projectID='<?php echo $project->id;?>'>
          <a href="###" title="<?php echo $project->name?>" data-target='<?php echo "#tab3{$blockNavId}Content{$project->id}";?>' data-toggle="tab"><?php echo $project->name;?></a>
          <?php echo html::a(helper::createLink('project', 'index', "projectID=$project->id"), "<i class='icon-arrow-right text-primary'></i>", '', "class='btn-view' title={$lang->project->index}");?>
        </li>
        <?php endforeach;?>
        <li class='switch-icon next'><a><i class='icon icon-arrow-right'></i></a></li>
      </ul>
    </div>
    <div class="col tab-content">
      <?php foreach($projects as $project):?>
      <div class="tab-pane fade<?php if($project->id == $selected) echo ' active in';?>" id='<?php echo "tab3{$blockNavId}Content{$project->id}";?>'>
        <div class="table-row">
          <?php if(in_array($project->model, array('scrum', 'kanban', 'agileplus'))):?>
          <div class='table-row'>
            <div class="col-4 text-center">
              <div><h4><?php echo $lang->block->storyCount;?></h4></div>
              <div>
                <div class="col dataTitle"><?php echo $lang->block->allStories . "：";?></div>
                <div class="col data"><?php echo $project->allStories;?></div>
              </div>
              <div>
                <div class="col dataTitle"><?php echo $lang->block->finish . "：";?></div>
                <div class="col data"><?php echo $project->doneStories;?></div>
              </div>
              <div>
                <div class="col dataTitle"><?php echo $lang->project->surplus . "：";?></div>
                <div class="col data"><?php echo $project->leftStories;?></div>
              </div>
            </div>
            <div class="col-4 text-center">
              <div><h4><?php echo $lang->block->investment;?></h4></div>
              <div>
                <div class="col dataTitle"><?php echo $lang->block->totalPeople . "：";?></div>
                <div class="col data"><?php echo $project->teamCount;?></div>
              </div>
              <div>
                <div class="col dataTitle"><?php echo $lang->block->estimate . "：";?></div>
                <div class="col data"><?php echo $project->estimate . $lang->execution->workHourUnit;?></div>
              </div>
              <div>
                <div class="col dataTitle"><?php echo $lang->block->consumedHours . "：";?></div>
                <div class="col data"><?php echo $project->consumed . $lang->execution->workHourUnit;?></div>
              </div>
            </div>
            <div class="col-4 text-center">
              <div><h4><?php echo $lang->block->taskCount;?></h4></div>
              <div>
                <div class="col dataTitle"><?php echo $lang->block->wait . "：";?></div>
                <div class="col data"><?php echo $project->waitTasks;?></div>
              </div>
              <div>
                <div class="col dataTitle"><?php echo $lang->block->doing . "：";?></div>
                <div class="col data"><?php echo $project->doingTasks;?></div>
              </div>
              <div>
                <div class="col dataTitle"><?php echo $lang->block->done . "：";?></div>
                <div class="col data"><?php echo $project->rndDoneTasks;?></div>
              </div>
            </div>
            <div class="col-4 text-center">
              <div><h4><?php echo $lang->block->bugCount;?></h4></div>
              <div>
                <div class="col dataTitle"><?php echo $lang->block->totalBug . "：";?></div>
                <div class="col data"><?php echo $project->allBugs;?></div>
              </div>
              <div>
                <div class="col dataTitle"><?php echo $lang->bug->statusList['resolved'] . "：";?></div>
                <div class="col data"><?php echo $project->doneBugs;?></div>
              </div>
              <div>
                <div class="col dataTitle"><?php echo $lang->bug->unResolved . "：";?></div>
                <div class="col data"><?php echo $project->leftBugs;?></div>
              </div>
            </div>
          </div>
          <?php if(!empty($project->execution) and $project->multiple):?>
          <div class="table-row project-info">
            <div class="col-2 text-right"><h4><?php echo $lang->block->last;?></h4></div>
            <div class="table-row lastIteration">
              <div class='col-5 text-center executionName'><?php echo html::a($this->createLink('execution', 'task', "executionID={$project->execution->id}"), $project->execution->name, '', "title='{$project->name}'");?></div>
              <div class='col-7'>
                <div class='progress progress-text-left'>
                  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $project->execution->progress;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $project->execution->progress;?>%">
                    <span class='progress-text'><?php echo !empty($project->execution->progress) ? $project->execution->progress . '%' : '0%';?></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php endif;?>
          <?php else:?>
          <div class="col-12">
            <div class='table-row text-left weekly-row with-padding'>
              <span class='weekly-title'><?php echo $lang->project->weekly;?></span>
              <span class='stage text-muted'><?php echo $project->current;?></span>
            </div>
            <div class='table-row text-center progress-group col-12 with-padding center-block'>
              <div class='forty-percent col'>
                <div class='progress-num col'>
                  <span><?php echo $lang->project->progress . ':';?></span>
                  <span class='project-info'><?php echo $project->progress . '%';?></span>
                </div>
                <div class='progress progress-percent col-1-8'>
                  <div class="progress-bar" role="progressbar" aria-valuenow="82" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $project->progress;?>%"></div>
                </div>
              </div>
              <div class="col-1-5">
                <span><?php echo $lang->project->teamCount . ':';?></span>
                <span class='project-info'><?php echo $project->teamCount;?></span>
              </div>
              <div class="col-1-5 project-budget">
                <span><?php echo $lang->project->budget . ':';?></span>
                <span class='project-info'>
                <?php
                $projectBudget = in_array($this->app->getClientLang(), array('zh-cn','zh-tw')) ? round((float)$project->budget / 10000, 2) . $this->lang->project->tenThousand : round((float)$project->budget, 2);
                echo $project->budget != 0 ? $projectBudget : $this->lang->project->future;
                ?>
                </span>
              </div>
              <div class="col-1-5"></div>
            </div>
            <div class="table-row text-center waterfall-title small col-12 center-block">
              <?php $isChineseLang = in_array($this->app->getClientLang(), array('zh-cn','zh-tw'));?>
              <div class="col-1-5"><?php echo $isChineseLang ? $lang->project->pv . '(' . $lang->project->pvTitle . ')' : $lang->project->pv; ?></div>
              <div class="col-1-5"><?php echo $isChineseLang ? $lang->project->ev . '(' . $lang->project->evTitle . ')' : $lang->project->ev;?></div>
              <div class="col-1-5"><?php echo $isChineseLang ? $lang->project->ac . '(' . $lang->project->acTitle . ')' : $lang->project->ac;?></div>
              <div class="col-1-5"><?php echo $isChineseLang ? $lang->project->sv . '(' . $lang->project->svTitle . ')' : $lang->project->sv;?></div>
              <div class="col-1-5"><?php echo $isChineseLang ? $lang->project->cv . '(' . $lang->project->cvTitle . ')' : $lang->project->cv;?></div>
            </div>
            <div class="table-row text-center waterfall-value small col-12 center-block">
              <div class="col-1-5"><?php echo $project->pv;?></div>
              <div class="col-1-5"><?php echo $project->ev;?></div>
              <div class="col-1-5"><?php echo $project->ac;?></div>
              <div class="col-1-5"><?php echo $project->sv;?></div>
              <div class="col-1-5"><?php echo $project->cv;?></div>
            </div>
          </div>
          <?php endif;?>
        </div>
      </div>
      <?php endforeach;?>
    </div>
    <?php endif;?>
  </div>
</div>
