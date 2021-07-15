<?php
/**
 * The batch create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->gitlab->bindUser;?></h2>
  </div>
  <form method='post' class='load-indicator main-form form-ajax' enctype='multipart/form-data'>
    <div class="table-responsive">
      <table class="table table-borderless w-600px">
        <thead>
          <tr>
            <th colspan='2'><?php echo $lang->gitlab->gitlabAccount;?></th>
            <th><?php echo $lang->gitlab->zentaoAccount;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($gitlabUsers as $gitlabUser):?>
          <?php if(isset($gitlabUser->zentaoAccount)) continue;?>
          <tr>
            <td class='w-60px'><?php echo html::image($gitlabUser->avatar, "height=40");?></td>
            <td class='text-left'>
              <strong><?php echo $gitlabUser->realname;?></strong>
              <br>
              <?php echo $gitlabUser->account . " &lt;" . $gitlabUser->email . "&gt;";?>
            </td>
            <td><?php echo html::select("zentaoUsers[$gitlabUser->id]", $userPairs, '', "class='form-control select chosen'" );?></td>
         </tr>
         <?php endforeach;?>
         <?php foreach($gitlabUsers as $gitlabUser):?>
         <?php if(!isset($gitlabUser->zentaoAccount)) continue;?>
         <tr>
            <td class='w-60px'><?php echo html::image($gitlabUser->avatar, "height=40");?></td>
            <td>
              <strong><?php echo $gitlabUser->realname;?></strong>
              <br>
              <?php echo $gitlabUser->account . " &lt;" . $gitlabUser->email . "&gt;";?>
            </td>
            <td><?php echo html::select("zentaoUsers[$gitlabUser->id]", $userPairs, $gitlabUser->zentaoAccount, "class='form-control select chosen'" );?></td>
         </tr>
         <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" class="text-center form-actions">
              <?php echo html::submitButton();?>
              <?php if(!isonlybody()) echo html::a(inlink('browse', ""), $lang->goback, '', 'class="btn btn-wide"');?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
