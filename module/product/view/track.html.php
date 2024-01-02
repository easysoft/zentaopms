<?php include '../../common/view/header.html.php';?>
<?php js::set('storyType', 'track');?>
<?php js::set('rawModule', $this->app->rawModule);?>
<style>
.table td{white-space:nowrap;text-overflow:ellipsis;overflow:hidden;position:unset !important;border-bottom-color:#ddd !important;}
.requirement{background: #fff}
.main-table tbody>tr:hover { background-color: #fff; }
.main-table tbody>tr:nth-child(odd):hover { background-color: #f5f5f5; }
.fix-table-copy-wrapper {overflow: unset !important;}
.table tr > th .dropdown > a.dropdown-toggle {display: flex; align-items: center;}
.table tr > th .dropdown > a.dropdown-toggle .product-name {overflow: hidden;}
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
    <?php $style  = $this->app->rawModule == 'projectstory' ? "style='overflow: unset; text-align: left'" : '';?>
    <?php $tab    = $this->app->rawModule == 'projectstory' ? 'project' : 'product';?>
    <?php $module = $this->app->rawModule == 'projectstory' ? 'projectstory' : 'story';?>
    <div class='main-table' data-ride="table">
      <table class='table table-bordered' id="trackList">
        <thead>
          <tr class='text-left'>
            <?php if($config->URAndSR):?>
            <th <?php echo $style;?>>
              <?php if($this->app->rawModule == 'projectstory' and $this->session->hasProduct): ?>
              <div class="dropdown">
                <?php echo html::a('javascript:;', "<i class='icon icon-product'></i><div class='product-name'>{$projectProducts[$productID]->name}</div><span class='caret'></span>", '', 'class="dropdown-toggle" data-toggle="dropdown"');?>
                <ul class="dropdown-menu">
                  <?php foreach($projectProducts as $product): ?>
                  <li class= "<?php if($productID == $product->id) echo 'active';?>"><?php echo html::a($this->createLink('projectstory', 'track', "projectID={$this->session->project}&productID=$product->id"), $product->name);?></li>
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
            <?php if($config->edition == 'max' or $config->edition == 'ipd'):?>
            <th><?php echo $lang->story->design;?></th>
            <?php endif;?>
            <th><?php echo $lang->story->case;?></th>
            <?php if(($config->edition == 'max' or $config->edition == 'ipd') and helper::hasFeature('devops')):?>
            <th><?php echo $lang->story->repoCommit;?></th>
            <?php endif;?>
            <th><?php echo $lang->story->bug;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($tracks as $key => $requirement):?>
          <?php $track   = ($key == 'noRequirement') ? $requirement : $requirement->track;?>
          <?php $rowspan = count($track);?>
          <?php $title   = $lang->story->noRelatedRequirement;?>
          <tr>
            <?php if($config->URAndSR):?>
            <td <?php if($rowspan != 0) echo "rowspan=" . $rowspan;?> class='requirement' title='<?php echo $key != 'noRequirement' ? $requirement->title : $lang->story->noRequirement;?>'>
              <?php if($key != 'noRequirement'):?>
              <span class="label label-primary label-outline"><?php echo zget($lang->story->statusList, $requirement->status);?></span>
              <?php $title = common::hasPriv($requirement->type, 'view') ? html::a($this->createLink('story', 'view', "storyID=$requirement->id"), $requirement->title, '', "title=$requirement->title data-app='$tab'") : $requirement->title;?>
              <?php endif;?>
              <?php echo $title;?>
            </td>
            <?php endif;?>
            <?php if(count($track) != 0):?>
            <?php $i = 0;?>
            <?php foreach($track as $storyID => $story):?>
            <?php if($i > 0) echo '<tr>';?>
              <td>
                <?php if(isset($story->parent) and $story->parent > 0):?><span class="label label-badge label-light" title="<?php echo $this->lang->story->children;?>"><?php echo $this->lang->story->childrenAB;?></span><?php endif;?>
                <?php echo html::a($this->createLink($module, 'view', "storyID=$storyID"), $story->title, '', "title='$story->title' data-app='$tab'");?>
              </td>
              <td>
                <?php foreach($story->tasks as $taskID => $task):?>
                <?php echo html::a($this->createLink('task', 'view', "taskID=$taskID"), $task->name, '', "title='$task->name'") . '<br/>';?>
                <?php endforeach;?>
              </td>
              <?php if($config->edition == 'max' or $config->edition == 'ipd'):?>
              <td>
                <?php foreach($story->designs as $designID => $design):?>
                <?php echo html::a($this->createLink('design', 'view', "designID=$designID"), $design->name, '', "title='$design->name'") . '<br/>';?>
                <?php endforeach;?>
              </td>
              <?php endif;?>
              <td>
                <?php foreach($story->cases as $caseID => $case):?>
                <?php echo html::a($this->createLink('testcase', 'view', "caseID=$caseID"), $case->title, '', "title='$case->title'") . '<br/>';?>
                <?php endforeach;?>
              </td>
              <?php if(($config->edition == 'max' or $config->edition == 'ipd') and helper::hasFeature('devops')):?>
              <td>
                <?php foreach($story->revisions as $revision => $repoComment):?>
                <?php
                echo html::a($this->createLink('design', 'revision', "repoID=$revision"), '#'. $revision . '-' . $repoComment, '', "data-app='devops'") . '<br/>';
                ?>
                <?php endforeach;?>
              </td>
              <?php endif;?>
              <td>
                <?php foreach($story->bugs as $bugID => $bug):?>
                <?php echo html::a($this->createLink('bug', 'view', "bugID=$bugID"), $bug->title, '', "title='$bug->title'") . '<br/>';?>
                <?php endforeach;?>
              </td>
            <?php if($i > 0) echo '</tr>';?>
            <?php $i++;?>
            <?php endforeach;?>
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
