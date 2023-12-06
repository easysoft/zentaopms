<?php
/**
 * The notify file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guangming Sun<sunguangming@cnezsoft.com>
 * @package     bug
 * @version     $Id: notify.html.php 4129 2021-12-01 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>.checkbox-primary {display: inline-block; margin-left: 10px;}</style>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $release->id;?></span>
        <?php echo $release->name . ' - ' . $lang->release->notify;?>
      </h2>
    </div>
    <form method='post' enctype='multipart/form-data' class='form-ajax'>
      <table class='table table-form'>
        <tr> 
          <th><?php echo $lang->release->notifyUsers;?></th>
          <td><?php echo html::checkbox('notify', $notifyList, 'FB');?></td>
        </tr> 
        <?php echo html::hidden('releaseID', $release->id);?>
        <tr> 
          <td class='form-actions text-center' colspan='2'><?php echo html::submitButton($lang->release->notify);?></td>
        </tr> 
      </table>
    </form>
    <hr class='small' />
    <div class='main'><?php include '../../common/view/action.html.php';?></div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
