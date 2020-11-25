<?php include '../../common/view/header.html.php';?>
<?php js::set('storyType', 'track');?>
<style>
.table td{white-space:nowrap;text-overflow:ellipsis;overflow:hidden;}
.requirement{background: #fff}
.main-table tbody>tr:hover { background-color: #fff; }
.main-table tbody>tr:nth-child(odd):hover { background-color: #f5f5f5; }
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
    <div class='main-table' data-ride="table">
      <table class='table table-bordered' id="trackList">
        <thead>
          <tr>
            <th><?php echo $lang->story->requirement;?></th>
            <th><?php echo $lang->story->story;?></th>
            <th><?php echo $lang->story->design;?></th>
            <th><?php echo $lang->story->case;?></th>
            <th><?php echo $lang->story->repoCommit;?></th>
            <th><?php echo $lang->story->bug;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($tracks as $key => $requirement):?>
          <?php $track = ($key == 'noRequirement') ? $requirement : $requirement->track;?>
          <?php $rowspan = count($track);?>
          <tr>
            <td <?php if($rowspan != 0) echo "rowspan=" . $rowspan;?> class='requirement'>
              <?php echo $key == 'noRequirement' ? $lang->story->noRequirement : html::a($this->createLink('story', 'view', "storyID=$requirement->id"), $requirement->title, '', "title=$requirement->title");?>
              <?php if($key != 'noRequirement'):?>
              <span class="label label-primary label-outline"><?php echo zget($lang->story->statusList, $requirement->status);?></span>
              <?php endif;?>
            </td>
            <?php if(count($track) != 0):?>
            <?php $i = 1;?>
            <?php foreach($track as $storyID => $story):?>
            <?php if($i != 1) echo '<tr>';?>
              <td style='padding-left: 10px;'><?php echo html::a($this->createLink('story', 'view', "storyID=$storyID"), $story->title, '',"title='$story->title'");?></td>
              <td>
                <?php foreach($story->design as $designID => $design):?>
                <?php echo html::a($this->createLink('design', 'view', "designID=$designID"), $design->name, '', "title='$design->name'") . '<br/>';?> 
                <?php endforeach;?>
              </td>
              <td>
                <?php foreach($story->case as $caseID => $case):?>
                <?php echo html::a($this->createLink('testcase', 'view', "caseID=$caseID"), $case->title, '', "title='$case->title'") . '<br/>';?> 
                <?php endforeach;?>
              </td>
              <td>
                <?php foreach($story->revision as $revision => $repoID):?>
                <?php 
                echo html::a($this->createLink('design', 'revision', "repoID=$revision"), '#'. $revision) . '<br/>';
                ?> 
                <?php endforeach;?>
              </td>
              <td>
                <?php foreach($story->bug as $bugID => $bug):?>
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
            <td></td>
            <?php endif;?>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
