<?php
/**
 * The showSyncCommit view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     repo
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainContent" class="main-content">
  <div class='cell'>
    <div class='alert with-icon'>
      <i class="icon-check-sign"></i>
      <div class='content'>
        <h3><?php echo $lang->repo->notice->syncing;?></h3>
        <hr>
        <p><?php echo $lang->repo->notice->syncedCount?><span id='commits'><?php echo $version?></span></p>
      </div>
    </div>
  </div>
</div>
<script language='Javascript'>
$(function(){
    <?php if(empty($branch)):?>
    var link = createLink('repo', 'ajaxSyncCommit', "repoID=<?php echo $repoID?>");
    <?php else:?>
    var link = createLink('repo', 'ajaxSyncBranchCommit', "repoID=<?php echo $repoID?>&branch=<?php echo helper::safe64Encode(base64_encode($branch));?>");
    <?php endif;?>
    function syncComments()
    {
        $.get(link, function(data)
        {
            if(data == 'finish')
            {
                $('#caption').text('<?php echo $lang->repo->notice->syncComplete?>');
                return self.location = '<?php echo $browseLink;?>';
            }
            if(data == 'error')
            {
                $('#mainContent .content p').text('<?php echo $lang->repo->notice->syncFailed?>');
                return;
            }
            $('#commits').html(parseInt($('#commits').html()) + parseInt(data));
            setTimeout(syncComments, 10);
        });
    }
    setTimeout(syncComments, 500);
})
</script>
<?php include '../../common/view/footer.html.php';?>
