<?php
/**
 * The diff view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Xiying Guan
 * @package     repo
 * @version     $Id: browse.html.php $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/sortable.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php
$browser = helper::getBrowser();
js::set('browser', $browser);
?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php common::printBack(inlink('browse'), 'btn btn-primary');?>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='label label-id'><?php echo $MR->id;?></span>
      <span title='<?php echo $MR->title;?>' class='text'><?php echo $MR->title;?></span>
    </div>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <div class='tabs' id='tabsNav'>
    <ul class='nav nav-tabs'>
      <li><?php echo html::a(inlink('view', "MRID={$MR->id}"), $lang->mr->view);?></li>
      <li class='active'><?php echo html::a('#', $lang->mr->viewDiff);?></li>
      <li><?php echo html::a(inlink('link', "MRID={$MR->id}&type=story"), html::icon($lang->icons['story'], 'text-primary') . ' ' . $lang->productplan->linkedStories);?></a></li>
      <li><?php echo html::a(inlink('link', "MRID={$MR->id}&type=bug"),   html::icon($lang->icons['bug'], 'text-red')   . ' ' . $lang->productplan->linkedBugs);?></a></li>
      <li><?php echo html::a(inlink('link', "MRID={$MR->id}&type=task"),  html::icon('todo', 'text-info')  . ' ' . $lang->mr->linkedTasks);?></a></li>
    </ul>
    <?php if($browser == 'ie'):?>
    <div class='tab-content'>
      <?php include '../../common/view/form.html.php';?>
      <?php include '../../common/view/kindeditor.html.php';?>
      <?php /* If this mr is deleted in GitLab, then show this part to user. */?>
      <?php if($MR->synced and (empty($rawMR) or !isset($rawMR->id))): ?>
        <div id='mainContent'>
          <div class="table-empty-tip">
            <p>
              <span class="text-muted"><?php echo $lang->mr->notFound; ?></span>
              <?php echo html::a($this->createLink('mr', 'browse'), "<i class='icon icon-plus'></i> " . $lang->mr->browse, '', "class='btn btn-info'"); ?>
            </p>
          </div>
        </div>
      <?php else: ?>
      <?php include 'header.review.html.php';?>
      <div class="repo panel">
        <div class='panel-heading'>
          <form method='post'>
            <div class='btn-group pull-right'>
              <?php echo html::commonButton($lang->repo->viewDiffList['inline'], "id='inline'", $arrange == 'inline' ? 'active btn btn-sm' : 'btn btn-sm')?>
              <?php echo html::commonButton($lang->repo->viewDiffList['appose'], "id='appose'", $arrange == 'appose' ? 'active btn btn-sm' : 'btn btn-sm')?>
            </div>
            <div class='btn-toolbar'>
              <div class='btn-group'>
                <div class='btn-group'>
                  <?php $encoding = str_replace('-', '_', $encoding);?>
                  <?php echo html::commonButton(zget($lang->repo->encodingList, $encoding, $lang->repo->encodingList['utf_8']) . "<span class='caret'></span>", "data-toggle='dropdown'", 'btn dropdown-toggle btn-sm')?>
                  <ul class='dropdown-menu' role='menu'>
                    <?php foreach($lang->repo->encodingList as $key => $val):?>
                    <li <?php echo $key == $encoding ? "class='active'" : '';?>>
                      <?php echo html::a('javascript:changeEncoding("'. $key . '")', $val)?>
                    </li>
                    <?php endforeach;?>
                  </ul>
                </div>
              </div>
            </div>
            <?php echo html::hidden('arrange', $arrange) . html::hidden('encoding', $encoding); ?>
          </form>
        </div>
        <?php foreach($diffs as $diffFile):?>
        <div class='repoCode'>
          <table class='table diff' id='diff' data-entry='<?php echo base64_encode($diffFile->fileName);?>'>
            <caption><?php echo $diffFile->fileName;?></caption>
            <?php if(empty($diffFile->contents)) continue;?>
            <?php foreach($diffFile->contents as $content):?>
            <?php
            $oldCurrentLine = $content->oldStartLine;
            $newCurrentLine = $content->newStartLine;
            ?>
            <?php if(!in_array($oldCurrentLine, array('0', '1')) and !in_array($newCurrentLine, array('0', '1'))):?>
            <tr data-line='<?php echo $newCurrentLine ?>' class='empty'>
              <th class='w-num text-center'>...</th>
              <?php if($arrange == 'appose'):?>
              <td class='none code'></td>
              <?php endif;?>
              <th class='w-num text-center'>...</th>
              <td class='none code'></td>
            </tr>
            <?php endif?>
            <?php if($arrange == 'inline'):?>
            <?php foreach($content->lines as $line):?>
            <tr data-line='<?php echo $line->newlc ?>'>
              <th class='w-num text-center'><?php if($line->type != 'new') echo $line->oldlc?></th>
              <th class='w-num text-center'><?php if($line->type != 'old') echo $line->newlc?></th>
              <td class='line-<?php echo $line->type?> code'><?php
              echo $line->type == 'old' ? preg_replace('/^\-/', '&ndash;', $line->line) : ($line->type == 'new' ? $line->line : ' ' . $line->line);
              ?></td>
            </tr>
            <?php endforeach;?>
            <?php else:?>
            <?php foreach($content->lines as $line):?>
            <tr data-line='<?php echo $line->newlc ?>'>
              <?php
              if($line->type == 'old')
              {
                  $oldlc = $line->oldlc;
                  $newlc = '';
                  if(isset($content->new[$oldlc]))
                  {
                      $newlc = $line->oldlc;
                      $line->type = 'custom';
                  }
              }
              else
              {
                  $oldlc = $line->oldlc;
                  $newlc = $line->newlc;
                  if(!isset($content->new[$newlc])) continue;
              }
              ?>
              <th class='w-num text-center'><?php echo $oldlc?></th>
              <td class='w-code line-<?php if($line->type != 'new')echo $line->type?> <?php if($line->type == 'custom') echo "line-old"?> code'><?php
              if(!isset($content->old[$oldlc])) $content->old[$oldlc] = '';
              if(!empty($oldlc)) echo $line->type != 'all' ? preg_replace('/^\-/', '&ndash;', $content->old[$oldlc]) : ' ' . $content->old[$oldlc];
              ?></td>
              <th class='w-num text-center'><?php echo $newlc?></th>
              <td class='w-code line-<?php if($line->type != 'old') echo $line->type?> <?php if($line->type == 'custom') echo "line-new"?> code'><?php
              if(!isset($content->new[$newlc])) $content->new[$newlc] = '';
              if(!empty($newlc)) echo $line->type != 'all' ? $content->new[$newlc] : ' ' . $content->new[$newlc];
              ?></td>
              <?php
              if(isset($content->old[$oldlc])) unset($content->old[$oldlc]);
              if(isset($content->new[$newlc])) unset($content->new[$newlc]);
              ?>
            </tr>
            <?php endforeach;?>
            <?php endif;?>
            <?php endforeach;?>
          </table>
        </div>
        <?php endforeach?>
      </div>
      <?php if(empty($diffs)) echo $lang->mr->noChanges;?>
      <form method="post" id="exchange" class="hidden">
        <input type="hidden" name="revision[]" value="<?php echo $oldRevision;?>"/>
        <input type="hidden" name="revision[]" value="<?php echo $newRevision;?>"/>
      </form>
      <?php endif;?>
    </div>
    <?php else:?>
    <?php
    if(empty($diffs))
    {
        echo "<p class='detail-content'>{$lang->mr->noChanges}</p>";
    }
    else
    {
        include '../../repo/view/diffeditor.html.php';
    }
    ?>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
