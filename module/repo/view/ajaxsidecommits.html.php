<?php
/**
 * The side logs view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2014 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     repo
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
$pathInfo = '&root=' . $this->repo->encodePath(empty($path) ? '/' : $path);
if(isset($entry)) $pathInfo .= '&type=file';
?>
<form id='logForm' class='main-table' data-ride='table' action='<?php echo $this->repo->createLink('diff', "repoID=$repoID", "entry=" . $this->repo->encodePath($path))?>' method='post'>
  <table class='table table-fixed'>
    <thead>
      <tr>
        <th class='w-40px'></th>
        <th class='w-80px'><?php echo $lang->repo->revisionA?></th>
        <?php if($repo->SCM == 'Git'):?>
        <th class='w-70px'><?php echo $lang->repo->commit?></th>
        <?php endif;?>
        <th class='w-80px'><?php echo $lang->repo->time?></th>
        <th class='w-100px'><?php echo $lang->repo->committer?></th>
        <th><?php echo $lang->repo->comment?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($revisions as $log):?>
      <tr>
        <td>
          <div class='checkbox-primary'>
            <input type='checkbox' name='revision[]' value="<?php echo $log->revision?>" />
            <label></label>
          </div>
        </td>
        <td class='versions'><span class="revision"><?php echo html::a($this->repo->createLink('revision', "repoID=$repoID&revision={$log->revision}" . $pathInfo), $repo->SCM == 'Git' ? substr($log->revision, 0, 10) : $log->revision);?></span></td>
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
    <?php if(common::hasPriv('repo', 'diff')) echo html::submitButton($lang->repo->diff, '', count($revisions) < 2 ? 'disabled btn btn-primary' : 'btn btn-primary')?>
    <?php echo html::a($this->repo->createLink('log', "repoID=$repoID&entry=&revision=HEAD&type=$logType", "entry=" . $this->repo->encodePath($path)), $lang->repo->allLog, '', "class='allLogs'");?>
    <div class='pull-right'>
      <div class='btn-group'>
        <?php
        $prePage  = $pager->pageID == 1 ? 1 : $pager->pageID - 1;
        $nextPage = $pager->pageID == $pager->pageTotal ? $pager->pageID : $pager->pageID + 1;
        $params   = "repoID=$repoID&path=&type=$logType&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
        $preLink  = $this->repo->createLink('ajaxSideCommits', "$params&pageID=$prePage", "path=" . $this->repo->encodePath($path));
        $nextLink = $this->repo->createLink('ajaxSideCommits', "$params&pageID=$nextPage", "path=" . $this->repo->encodePath($path));
        echo html::a($preLink, "<i class='icon icon-angle-left'></i>", '', "class='ajaxPager btn" . ($prePage == $pager->pageID ? ' disabled' : '') . "'");
        echo html::a($nextLink, "<i class='icon icon-angle-right'></i>", '', "class='ajaxPager btn" . ($nextPage == $pager->pageID ? ' disabled' : '') . "'");
        ?>
      </div>
    </div>
  </div>
</form>
<script>
if($("input:checkbox[name='revision[]']:checked").length < 2)
{
    $("input:checkbox[name='revision[]']:lt(2)").attr('checked', 'checked');
}
$("input:checkbox[name='revision[]']").each(function(){ if(!$(this).is(':checked')) $(this).attr("disabled","disabled")});
$("input:checkbox[name='revision[]']").click(function(){
    var checkNum = $("input:checkbox[name='revision[]']:checked").length;
    if (checkNum >= 2) 
    {
        $("input:checkbox[name='revision[]']").each(function(){ if(!$(this).is(':checked')) $(this).attr("disabled","disabled")});
    } 
    else
    {
        $('#diffRepo').remove();
        $("input:checkbox[name='revision[]']").each(function(){$(this).attr("disabled", false)});
    }
});
</script>
