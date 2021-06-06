<?php include '../../common/view/header.html.php';?>
<?php js::set('storyType', 'track');?>
<?php js::set('rawModule', $this->app->rawModule);?>
<style>
.table td{white-space:nowrap;text-overflow:ellipsis;overflow:hidden;position:unset !important;border-bottom-color:#ddd !important;}
.requirement{background: #fff}
.main-table tbody>tr:hover { background-color: #fff; }
.main-table tbody>tr:nth-child(odd):hover { background-color: #f5f5f5; }
.fix-table-copy-wrapper {overflow: unset !important;}
</style>
<div id="mainContent" class="main-row fade">
  <div class="main-col">
    <?php if(empty($tracks)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->noData;?></span>
      </p>
    </div>
    <?php else:?>
    <?php $style   = $this->app->rawModule == 'projectstory' ? "style='overflow: unset; text-align: left'" : '';?>
    <?php $openApp = $this->app->rawModule == 'projectstory' ? 'project' : 'product';?>
    <?php $module  = $this->app->rawModule == 'projectstory' ? 'projectstory' : 'story';?>
    <div class='main-table' data-ride="table">
      <table class='table table-bordered' id="trackList">
        <thead>
          <tr class='text-center'>
            <?php if($config->URAndSR):?>
            <th <?php echo $style;?>>
              <?php if($this->app->rawModule == 'projectstory'): ?>
              <div class="dropdown">
                <?php echo html::a('javascript:;', "<i class='icon icon-product'></i>". $projectProducts[$productID]->name . '<span class="caret"></span>', '', 'class="dropdown-toggle" data-toggle="dropdown"');?>
                <ul class="dropdown-menu">
                  <?php foreach($projectProducts as $product): ?>
                  <li><?php echo html::a($this->createLink('projectstory', 'track', "projectID={$this->session->project}&productID=$product->id"), $product->name);?></li>
                  <?php endforeach;?>
                </ul>
              </div>
              <?php else:?>
              <?php echo $lang->story->requirement;?>
              <?php endif;?>
            </th>
            <?php endif;?>
            <th><?php echo $lang->story->story;?></th>
            <th><?php echo $lang->story->tasks;?></th>
            <?php if(isset($config->maxVersion)):?>
            <th><?php echo $lang->story->design;?></th>
            <?php endif;?>
            <th><?php echo $lang->story->case;?></th>
            <?php if(isset($config->maxVersion)):?>
            <th><?php echo $lang->story->repoCommit;?></th>
            <?php endif;?>
            <th><?php echo $lang->story->bug;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($tracks as $key => $requirement):?>
          <?php $track = ($key == 'noRequirement') ? $requirement : $requirement->track;?>
          <?php $rowspan = count($track);?>
          <tr>
            <?php if($config->URAndSR):?>
            <td <?php if($rowspan != 0) echo "rowspan=" . $rowspan;?> class='requirement'>
              <?php if($key != 'noRequirement'):?>
              <span class="label label-primary label-outline"><?php echo zget($lang->story->statusList, $requirement->status);?></span>
              <?php endif;?>
              <?php echo $key == 'noRequirement' ? $lang->story->noRequirement : html::a($this->createLink($module, 'view', "storyID=$requirement->id"), $requirement->title, '', "title=$requirement->title data-app='$openApp'");?>
            </td>
            <?php endif;?>
            <?php if(count($track) != 0):?>
            <?php $i = 1;?>
            <?php foreach($track as $storyID => $story):?>
            <?php if($i != 1) echo '<tr>';?>
              <td style='padding-left: 10px;'><?php echo html::a($this->createLink($module, 'view', "storyID=$storyID"), $story->title, '',"title='$story->title' data-app='$openApp'");?></td>
              <td>
                <?php foreach($story->tasks as $taskID => $task):?>
                <?php echo html::a($this->createLink('task', 'view', "taskID=$taskID"), $task->name, '', "title='$task->name'") . '<br/>';?>
                <?php endforeach;?>
              </td>
              <?php if(isset($config->maxVersion)):?>
              <td>
                <?php foreach($story->designs as $designID => $design):?>
                <?php echo html::a($this->createLink('design', 'view', "designID=$designID"), $design->name, '', "title='$design->name'") . '<br/>';?>
                <?php endforeach;?>
              </td>
              <?php endif;?>
              <td>
                <?php foreach($story->cases as $caseID => $case):?>
                <?php echo html::a($this->createLink('testcase', 'view', "caseID=$caseID", '', false, $case->project), $case->title, '', "title='$case->title'") . '<br/>';?>
                <?php endforeach;?>
              </td>
              <?php if(isset($config->maxVersion)):?>
              <td>
                <?php foreach($story->revisions as $revision => $repoID):?>
                <?php
                echo html::a($this->createLink('design', 'revision', "repoID=$revision"), '#'. $revision, '', "data-app='devops'") . '<br/>';
                ?>
                <?php endforeach;?>
              </td>
              <?php endif;?>
              <td>
                <?php foreach($story->bugs as $bugID => $bug):?>
                <?php echo html::a($this->createLink('bug', 'view', "bugID=$bugID"), $bug->title, '', "title='$bug->title'") . '<br/>';?>
                <?php endforeach;?>
              </td>
            <?php if($i != 1) echo '</tr>';?>
            <?php $i++;?>
            <?php endforeach;?>
            <?php else:?>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <?php if(isset($config->maxVersion)):?>
            <td></td>
            <td></td>
            <?php endif;?>
            <?php endif;?>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    </div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
