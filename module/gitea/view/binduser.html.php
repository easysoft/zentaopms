<?php
/**
 * The bind user view of gitea module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@easycorp.ltd>
 * @package     gitea
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php $browseLink = $this->createLink('gitea', 'browse', ""); ?>
<?php js::set('zentaoUsers', $zentaoUsers);?>
<div id="mainContent" class="main-content">
  <div class="main-header gitea-bind">
    <?php
    echo html::linkButton('<i class="icon icon-back icon-sm"></i> ' . $lang->goback, $browseLink, 'self', "data-app='{$app->tab}'", 'btn btn-secondary');

    $allLink     = $this->createLink('gitea', 'binduser', "giteaID={$giteaID}&type=all");
    $bindedLink  = $this->createLink('gitea', 'binduser', "giteaID={$giteaID}&type=binded");
    $notBindLink = $this->createLink('gitea', 'binduser', "giteaID={$giteaID}&type=notBind");
    if($type == 'all')
    {
        echo html::linkButton('' . $lang->gitea->all . "<span class='gitea-bind-all'>" . count($giteaUsers) . "</span>", $allLink, 'self', "data-app='{$app->tab}'", 'btn btn-info active');
        echo html::linkButton('' . $lang->gitea->notBind, $notBindLink, 'self', "data-app='{$app->tab}'", 'btn btn-info');
        echo html::linkButton('' . $lang->gitea->binded, $bindedLink, 'self', "data-app='{$app->tab}'", 'btn btn-info');
    }
    else if($type == 'binded')
    {
        echo html::linkButton('' . $lang->gitea->all, $allLink, 'self', "data-app='{$app->tab}'", 'btn btn-info');
        echo html::linkButton('' . $lang->gitea->notBind, $notBindLink, 'self', "data-app='{$app->tab}'", 'btn btn-info');
        echo html::linkButton('' . $lang->gitea->binded . "<span class='gitea-bind-all'>" . count($giteaUsers) . "</span>", $bindedLink, 'self', "data-app='{$app->tab}'", 'btn btn-info active');
    }
    else
    {
        echo html::linkButton('' . $lang->gitea->all, $allLink, 'self', "data-app='{$app->tab}'", 'btn btn-info');
        echo html::linkButton('' . $lang->gitea->notBind . "<span class='gitea-bind-all'>" . count($giteaUsers) . "</span>", $notBindLink, 'self', "data-app='{$app->tab}'", 'btn btn-info active');
        echo html::linkButton('' . $lang->gitea->binded, $bindedLink, 'self', "data-app='{$app->tab}'", 'btn btn-info');
    }
    ?>
  </div>
  <form method='post' class='load-indicator main-form form-ajax' enctype='multipart/form-data'>
    <div class="table-responsive">
      <table class="table table-borderless">
        <thead>
          <tr>
            <th><?php echo $lang->gitea->giteaAccount;?></th>
            <th><?php echo $lang->gitea->giteaEmail;?></th>
            <th><?php echo $lang->gitea->zentaoEmail;?></th>
            <th class="w-400px"><?php echo $lang->gitea->zentaoAccount;?> <span class="gitea-account-desc"><?php echo $lang->gitea->accountDesc;?></span></th>
            <th><?php echo $lang->gitea->bindingStatus;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($giteaUsers as $giteaUser):?>
          <?php echo html::hidden("giteaUserNames[$giteaUser->id]", $giteaUser->realname);?>
          <tr>
            <td>
              <?php echo html::image($giteaUser->avatar, "height=20 width=20 class='img-circle'");?>
              <?php echo $giteaUser->realname . '@' . $giteaUser->account;?>
            </td>
            <td><?php echo $giteaUser->email;?></td>
            <td class="email"><?php echo !empty($giteaUser->zentaoAccount) ? $zentaoUsers[$giteaUser->zentaoAccount]->email : '';?></td>
            <td class='zentao-users'><?php echo html::select("zentaoUsers[$giteaUser->id]", $giteaUser->zentaoUsers, $giteaUser->zentaoAccount, "class='form-control select chosen gitea-user-bind'" );?></td>
            <td>
              <?php if($giteaUser->binded === 1):?>
              <?php echo $lang->gitea->binded;?>
              <?php elseif($giteaUser->binded === 2):?>
              <?php echo '<span class="text-red">' . $lang->gitea->bindedError . '</span>';?>
              <?php else:?>
              <?php echo '<span class="text-red">' . $lang->gitea->notBind . '</span>';?>
              <?php endif;?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5" class="text-center form-actions">
              <?php echo html::submitButton();?>
              <?php if(!isonlybody()) echo html::a($this->createLink('space', 'browse'), $lang->goback, '', 'class="btn btn-wide"');?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<div id="userList" class="hidden">
<?php
foreach($userPairs as $account => $realname)
{
    echo "<option value='$account' title='$realname'>$realname</option>";
}
?>
</div>
<?php include '../../common/view/footer.html.php';?>
