<?php
/**
 * The commit view of design module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     design
 * @version     $Id: commit.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='main-content' id='mainContent'>
  <div class='main-header'>
    <h2>
      <span class='label label-id'><?php echo $design->id;?></span>
      <span title='<?php echo $design->name?>'><?php echo $design->name?></span>
      <small><?php echo $lang->arrow . $lang->design->commit;?></small>
    </h2>
  </div>
  <div class='searchBox'>
    <h4><?php echo $lang->design->commitDate . '：';?></h4>
    <?php echo html::input('begin', $begin, "class='form-control form-date srearch-date'");?>
    <span>~</span>
  <?php echo html::input('end', $end, "class='form-control form-date srearch-date'");?>
  </div>
  <form id='logForm' class='main-table form-ajax' data-ride='table' action=<?php echo inlink('commit', "designID=$designID");?> method='post'>
    <table class='table'>
      <thead>
        <tr>
          <th class='w-40px'></th>
          <th class='w-80px'><?php echo $lang->repo->revisionA?></th>
          <?php if(isset($repo->SCM) and $repo->SCM == 'Git'):?>
          <th class='w-70px'><?php echo $lang->repo->commit?></th>
          <?php endif;?>
          <th class='w-120px'><?php echo $lang->repo->time?></th>
          <th class='w-100px'><?php echo $lang->repo->committer?></th>
          <th><?php echo $lang->repo->comment?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($revisions as $log):?>
        <tr>
          <td>
            <div class='checkbox-primary'>
            <input type='checkbox' name='revision[]' value="<?php echo $log->id?>" <?php if(in_array($log->revision, $linkedRevisions)) echo 'checked="checked"'?>/>
              <label></label>
            </div>
          </td>
          <td class='versions'><span class="revision"><?php echo html::a($this->repo->createLink('revision', "repoID=$repoID&revision={$log->revision}"), $repo->SCM == 'Git' ? substr($log->revision, 0, 10) : $log->revision);?></span></td>
          <?php if($repo->SCM == 'Git'):?>
          <td><?php echo $log->commit?></td>
          <?php endif;?>
          <td><?php echo substr($log->time, 0, 10);?></td>
          <td><?php echo $log->committer;?></td>
          <?php $comment = htmlspecialchars($log->comment, ENT_QUOTES);?>
          <td title='<?php echo $comment?>' class='comment'><?php echo $log->comment?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar">
        <?php echo html::submitButton('', '', 'btn btn-primary')?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
</div>
<?php js::set('designID', $designID);?>
<?php include '../../common/view/footer.lite.html.php';?>
