<?php
js::import($jsRoot  . '/zui/tabs/tabs.min.js');
js::import($jsRoot . 'misc/base64.js');
$entry    = count($diffs) ? $diffs[0]->fileName : '';
$file     = $entry ? pathinfo($entry) : array();
$showBug  = isset($showBug) ? $showBug : true;
$objectID = isset($objectID) ? $objectID : 0;

js::set('diffs', $diffs);
js::set('file', $file);
js::set('entry', $entry);
js::set('openedFiles', array($entry));
js::set('isonlybody', isonlybody());
js::set('urlParams', "repoID=$repoID&objectID=$objectID&entry=%s&oldRevision=$oldRevision&newRevision=$newRevision&showBug=$showBug&encoding=$encoding");
?>
<?php if(!isonlybody()):?>
<div id="mainContent" class="main-row fade diff-page">
  <?php $sideWidth = common::checkNotCN() ? '270' : '240';?>
  <div class="side-col" id='sidebar' style="width: <?php echo $sideWidth + 15;?>px; padding-top: <?php echo isonlybody() ? 22 : 0;?>px;">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell file-tree" style="width: <?php echo $sideWidth;?>px;" data-min-width="<?php echo $sideWidth;?>">
      <div id="filesTree" class="cell load-indicator <?php if(isonlybody()) echo 'pull-left';?>">
        <?php echo $this->repo->getFileTree($repo, '', $diffs);?>
      </div>
    </div>
  </div>
  <?php endif;?>
  <div class="main-col repoCode main">
    <div class="content panel">
      <div class='btn-toolbar'>
        <?php if(!isonlybody()):?>
        <div class="btn btn-left pull-left"><i class="icon icon-chevron-left"></i></div>
        <div class="dropdown pull-right">
          <button class="btn" type="button" data-toggle="context-dropdown"><i class="icon icon-ellipsis-v icon-rotate-90"></i></button>
          <ul class="dropdown-menu">
            <?php
            if(common::hasPriv('repo', 'download')) echo '<li>' . html::a($this->repo->createLink('download', "repoID=$repoID&path=" . $this->repo->encodePath($entry) . "&fromRevison=$oldRevision&toRevision=$newRevision&type=path"), '<i class="icon icon-download"></i> ' . $lang->repo->downloadDiff, 'hiddenwin') . '</li>';
            echo '<li>' . html::a('javascript:;', '<i class="icon icon-inline"></i> ' . $lang->repo->viewDiffList['inline'], '', "class='inline-appose' id='inline'") . '</li>';
            echo '<li>' . html::a('javascript:;', '<i class="icon icon-appose"></i> ' . $lang->repo->viewDiffList['appose'], '', "class='inline-appose' id='appose'") . '</li>';
            ?>
          </ul>
        </div>
        <div class="btn btn-right  pull-right"><i class="icon icon-chevron-right"></i></div>
        <?php endif;?>
        <div class='panel-title'>
          <div class="tabs w-10" id="fileTabs"></div>
        </div>
      </div>
    </div>
  </div>
<?php if(!isonlybody()):?>
</div>
<?php endif;?>
<a href='' class='iframe' data-width='90%' id='linkObject'></a>
