<?php
/**
 * The bind user view of gitea module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@easycorp.ltd>
 * @package     gitea
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->gitea->bindUser;?></h2>
  </div>
  <form method='post' class='load-indicator main-form form-ajax' enctype='multipart/form-data'>
    <div class="table-responsive">
      <table class="table table-borderless w-600px">
        <thead>
          <tr>
            <th colspan='2'><?php echo $lang->gitea->giteaAccount;?></th>
            <th class='w-150px'><?php echo $lang->gitea->zentaoAccount;?></th>
            <th class='w-150px'><?php echo $lang->gitea->bindingStatus;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($giteaUsers as $giteaUser):?>
          <?php if(isset($giteaUser->zentaoAccount)) continue;?>
          <?php echo html::hidden("giteaUserNames[$giteaUser->account]", $giteaUser->realname);?>
          <tr>
            <td class='w-60px'><?php echo html::image($giteaUser->avatar, "height=40");?></td>
            <td class='text-left'>
              <strong><?php echo $giteaUser->realname;?></strong>
              <br>
              <?php echo $giteaUser->account;?>
              <?php if($giteaUser->email) echo " &lt;" . $giteaUser->email . "&gt;";?>
            </td>
            <td><?php echo html::select("zentaoUsers[$giteaUser->account]", $userPairs, '', "class='form-control select chosen'" );?></td>
            <td><?php echo $lang->gitea->notBind;?></td>
          </tr>
          <?php endforeach;?>
          <?php foreach($giteaUsers as $giteaUser):?>
          <?php if(!isset($giteaUser->zentaoAccount)) continue;?>
          <?php echo html::hidden("giteaUserNames[$giteaUser->account]", $giteaUser->realname);?>
          <tr>
            <td class='w-60px'><?php echo html::image($giteaUser->avatar, "height=40");?></td>
            <td>
              <strong><?php echo $giteaUser->realname;?></strong>
              <br>
              <?php echo $giteaUser->account;?>
              <?php if($giteaUser->email) echo " &lt;" . $giteaUser->email . "&gt;";?>
            </td>
            <td><?php echo html::select("zentaoUsers[$giteaUser->account]", $userPairs, $giteaUser->zentaoAccount, "class='form-control select chosen'" );?></td>
            <td>
              <?php if(isset($bindedUsers[$giteaUser->zentaoAccount])):?>
              <?php $zentaoAccount = zget($userPairs, $giteaUser->zentaoAccount, '');?>
              <?php if(!empty($zentaoAccount)):?>
              <?php echo $lang->gitea->binded;?>
              <?php else:?>
              <?php echo '<span class="text-red">' . $lang->gitea->bindedError . '</span>';?>
              <?php endif;?>
              <?php else:?>
              <?php echo $lang->gitea->notBind;?>
              <?php endif;?>
            </td>
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
