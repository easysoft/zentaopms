<?php include '../../common/view/header.html.php';?>
<?php js::set('browseType', $browseType);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->repo->createLink('review', "repoID=$repoID&browseType=all"), "<span class='text'>{$lang->bug->allBugs}</span>", '', "id='allTab' class='btn btn-link'");?>
    <?php echo html::a($this->repo->createLink('review', "repoID=$repoID&browseType=assignToMe"), "<span class='text'>{$lang->bug->assignToMe}</span>", '', "id='assigntomeTab' class='btn btn-link'");?>
    <?php echo html::a($this->repo->createLink('review', "repoID=$repoID&browseType=openedByMe"), "<span class='text'>{$lang->bug->openedByMe}</span>", '', "id='openedbymeTab' class='btn btn-link'");?>
    <?php echo html::a($this->repo->createLink('review', "repoID=$repoID&browseType=resolvedByMe"), "<span class='text'>{$lang->bug->resolvedByMe}</span>", '', "id='resolvedbymeTab' class='btn btn-link'");?>
    <?php echo html::a($this->repo->createLink('review', "repoID=$repoID&browseType=assignToNull"), "<span class='text'>{$lang->bug->assignToNull}</span>", '', "id='assigntonullTab' class='btn btn-link'");?>
    <?php echo html::a($this->repo->createLink('review', "repoID=$repoID&browseType=unResolved"), "<span class='text'>{$lang->bug->unResolved}</span>", '', "id='unresolvedTab' class='btn btn-link'");?>
    <?php echo html::a($this->repo->createLink('review', "repoID=$repoID&browseType=unclosed"), "<span class='text'>{$lang->bug->unclosed}</span>", '', "id='unclosedTab' class='btn btn-link'");?>
  </div>
</div>

<div id='mainContent' class='main-table' data-ride='table'>
  <table class='table' id='bugList'>
    <thead>
      <tr class="colhead">
      <th class="c-id"><?php echo 'ID';?></th>
      <th><?php echo $lang->repo->title?></th>
      <th><?php echo $lang->repo->file . '/' . $lang->repo->location?></th>
      <th class='w-100px'><?php echo $lang->repo->revisionA?></th>
      <th class='w-80px'><?php echo $lang->repo->type?></th>
      <th class='w-80px'><?php echo $lang->repo->status?></th>
      <th class='w-100px'><?php echo $lang->repo->openedBy?></th>
      <th class='w-100px'><?php echo $lang->repo->assignedTo?></th>
      <th class='w-120px'><?php echo $lang->repo->openedDate?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($bugs as $bug):?>
      <tr>
        <td><?php echo sprintf('%03d', $bug->id);?></td>
        <?php $lines = explode(',', trim($bug->lines, ','));?>
        <td><?php echo html::a($this->createLink('bug', 'view', "bugID={$bug->id}"), $bug->title)?></td>
        <td>
          <?php
          $entry = $repo->name . '/' . $this->repo->decodePath($bug->entry);
          if(empty($bug->v1))
          {
              $revision = $repo->SCM == 'Git' ? $this->repo->getGitRevisionName($bug->v2, $historys[$bug->v2]) : $bug->v2;
              $link     = $this->repo->createLink('view', "repoID=$repoID&entry=&revision={$bug->v2}", "entry={$bug->entry}") . "#L$lines[0]";
          }
          else
          {
              $revision  = $repo->SCM == 'Git' ? substr($bug->v1, 0, 10) : $bug->v1;
              $revision .= ' : ';
              $revision .= $repo->SCM == 'Git' ? substr($bug->v2, 0, 10) : $bug->v2;
              if($repo->SCM == 'Git') $revision .= ' (' . $historys[$bug->v1] . ' : ' . $historys[$bug->v2] . ')';
              $link = $this->repo->createLink('diff', "repoID=$repoID&entry=&oldRevision={$bug->v1}&newRevision={$bug->v2}", "entry={$bug->entry}") . "#L$lines[0]";
          }
          echo html::a($link, $entry);
          echo "<span class='label label-info'>$bug->lines</span>";
          ?>
        </td>
        <td><?php echo $repo->SCM == 'Git' ? substr($bug->v2, 0, 10) : $bug->v2?></td>
        <td><?php echo isset($lang->repo->typeList[$bug->repoType]) ? $lang->repo->typeList[$bug->repoType] : ''?></td>
        <td class='bug-<?php echo $bug->status?>'><?php echo $lang->bug->statusList[$bug->status]?></td>
        <td><?php echo zget($users, $bug->openedBy)?></td>
        <td><?php echo zget($users, $bug->assignedTo)?></td>
        <td><?php echo substr($bug->openedDate, 2, 14)?></td>
      </tr>
      <?php endforeach;?>
      </tbody>
  </table>
  <?php if($bugs):?>
  <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
