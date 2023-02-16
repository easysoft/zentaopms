<?php
/**
 * The bind user view of gogs module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@easycorp.ltd>
 * @package     gogs
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->gogs->bindUser;?></h2>
  </div>
  <form method='post' class='load-indicator main-form form-ajax' enctype='multipart/form-data'>
    <div class="table-responsive">
      <table class="table table-borderless w-800px">
        <thead>
          <tr>
            <th class='w-60px'><?php echo $lang->gogs->gogsAvatar;?></th>
            <th><?php echo $lang->gogs->gogsAccount;?></th>
            <th><?php echo $lang->gogs->gogsEmail;?></th>
            <th class='w-150px'><?php echo $lang->gogs->zentaoAccount;?></th>
            <th><?php echo $lang->gogs->bindingStatus;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($gogsUsers as $gogsUser):?>
          <?php if(isset($gogsUser->zentaoAccount)) continue;?>
          <?php echo html::hidden("gogsUserNames[$gogsUser->account]", $gogsUser->realname);?>
          <tr>
            <td><?php echo html::image($gogsUser->avatar, "height=40");?></td>
            <td class='text-left'>
              <strong><?php echo $gogsUser->realname;?></strong>
              <br>
              <?php echo $gogsUser->account;?>
            </td>
            <td><?php echo $gogsUser->email;?></td>
            <td><?php echo html::select("zentaoUsers[$gogsUser->account]", $userPairs, '', "class='form-control select chosen'" );?></td>
            <td><?php echo $lang->gogs->notBind;?></td>
          </tr>
          <?php endforeach;?>
          <?php foreach($gogsUsers as $gogsUser):?>
          <?php if(!isset($gogsUser->zentaoAccount)) continue;?>
          <?php echo html::hidden("gogsUserNames[$gogsUser->account]", $gogsUser->realname);?>
          <tr>
            <td><?php echo html::image($gogsUser->avatar, "height=40");?></td>
            <td>
              <strong><?php echo $gogsUser->realname;?></strong>
              <br>
              <?php echo $gogsUser->account;?>
            </td>
            <td><?php echo $gogsUser->email;?></td>
            <td><?php echo html::select("zentaoUsers[$gogsUser->account]", $userPairs, $gogsUser->zentaoAccount, "class='form-control select chosen'" );?></td>
            <td>
              <?php if(isset($bindedUsers[$gogsUser->zentaoAccount])):?>
              <?php $zentaoAccount = zget($userPairs, $gogsUser->zentaoAccount, '');?>
              <?php if(!empty($zentaoAccount)):?>
              <?php echo $lang->gogs->binded;?>
              <?php else:?>
              <?php echo '<span class="text-red">' . $lang->gogs->bindedError . '</span>';?>
              <?php endif;?>
              <?php else:?>
              <?php echo $lang->gogs->notBind;?>
              <?php endif;?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5" class="text-center form-actions">
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
