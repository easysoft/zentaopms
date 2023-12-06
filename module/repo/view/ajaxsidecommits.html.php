<?php
/**
 * The side logs view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     repo
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php
$pathInfo = '&root=' . $this->repo->encodePath(empty($path) ? '/' : $path);
if(isset($entry)) $pathInfo .= '&type=file';
?>
<?php js::set('paramsBase', "repoID=$repoID&path=" . $this->repo->encodePath($path) . "&objectID=$objectID&type=$logType");?>
<form id='logForm' class='main-table not-watch' method='post' onsubmit='logsubmit()'>
  <table class='table table-fixed'>
    <thead>
      <tr>
        <th class='c-checkbox w-40px'></th>
        <th class='c-version'><?php echo $lang->repo->revisionA?></th>
        <?php if($repo->SCM != 'Subversion'):?>
        <th class='c-commit'><?php echo $lang->repo->commit?></th>
        <?php endif;?>
        <th class='c-date'><?php echo $lang->repo->time?></th>
        <th class='c-committer'><?php echo $lang->repo->committer?></th>
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
        <td class='versions'><span class="revision"><?php echo html::a($this->repo->createLink('revision', "repoID=$repoID&objectID=$objectID&revision={$log->revision}" . $pathInfo), $repo->SCM != 'Subversion' ? substr($log->revision, 0, 10) : $log->revision, '', "data-app='{$this->app->tab}'");?></span></td>
        <?php if($repo->SCM != 'Subversion'):?>
        <td><?php echo $log->commit?></td>
        <?php endif;?>
        <td><?php echo substr($log->time, 0, 10);?></td>
        <td><?php echo $log->committer;?></td>
        <td title='<?php echo $log->originalComment?>' class='comment'><?php echo $log->comment?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'>
    <?php if(common::hasPriv('repo', 'diff')) echo html::submitButton($lang->repo->diff, '', count($revisions) < 2 ? 'disabled btn btn-primary' : 'btn btn-primary')?>
    <?php echo html::a($this->repo->createLink('log', "repoID=$repoID&objectID=$objectID&entry=" . $this->repo->encodePath($path) . "&revision=HEAD&type=$logType"), $lang->repo->allLog, '', "class='allLogs' data-app='{$this->app->tab}'");?>
    <div class='pull-right'>
      <ul id="repoPageSize" class="pager" data-ride="pager" data-elements="size_menu" data-rec-total="<?php echo $pager->recTotal;?>" data-rec-per-page="<?php echo $pager->recPerPage;?>" data-page="<?php echo $pager->pageID;?>"></ul>
      <div class='btn-group'>
        <?php
        $prePage  = $pager->pageID == 1 ? 1 : $pager->pageID - 1;
        $nextPage = $pager->pageID == $pager->pageTotal ? $pager->pageID : $pager->pageID + 1;
        $params   = "repoID=$repoID&path=" . $this->repo->encodePath($path) . "&objectID=$objectID&type=$logType&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
        $preLink  = $this->repo->createLink('ajaxSideCommits', "$params&pageID=$prePage");
        $nextLink = $this->repo->createLink('ajaxSideCommits', "$params&pageID=$nextPage");
        echo html::commonButton("<i class='icon icon-angle-left'></i>",  "data-href='$preLink'",  "ajaxPager btn" . ($prePage == $pager->pageID ? ' disabled' : ''));
        echo html::commonButton("<i class='icon icon-angle-right'></i>", "data-href='$nextLink'", "ajaxPager btn" . ($nextPage == $pager->pageID ? ' disabled' : ''));
        ?>
      </div>
    </div>
  </div>
</form>
<script>
if($.cookie('sideRepoSelected'))
{
    var sideRepoSelectedAry = $.cookie('sideRepoSelected').split(',');
    for(i in sideRepoSelectedAry) $("input:checkbox[value='" + sideRepoSelectedAry[i] + "']").attr('checked', 'checked');
}
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

/**
 * Method before submit
 *
 * @access public
 * @return void
 */
function logsubmit()
{
    $("input:checkbox[name='revision[]']:checked").each(function()
    {
        var sideRepoSelected = $.cookie('sideRepoSelected') ? $.cookie('sideRepoSelected').split(',') : [];
        sideRepoSelected.unshift($(this).val());
        sideRepoSelected = sideRepoSelected.slice(0, 2);
        $.cookie('sideRepoSelected', sideRepoSelected.join(','), {expires:config.cookieLife, path:config.webRoot});
    });
}

$(function()
{
    var myPager = $('#repoPageSize').data('zui.pager');
    if(!myPager) $('#repoPageSize').pager();

    $('#repoPageSize').on('onPageChange', function(e, state, oldState) {
        var link = createLink('repo', 'ajaxSideCommits', paramsBase + '&recTotal=' + state.recTotal + '&recPerPage=' + state.recPerPage + '&pageID=' + state.page);
        $('#sidebar .side-body').load(link);
    });
});
</script>
