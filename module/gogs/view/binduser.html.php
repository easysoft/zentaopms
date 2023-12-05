<?php
/**
 * The bind user view of gogs module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@easycorp.ltd>
 * @package     gogs
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php $browseLink = $this->createLink('gogs', 'browse', ""); ?>
<?php js::set('zentaoUsers', $zentaoUsers);?>
<div id="mainContent" class="main-content">
  <div class="main-header gogs-bind">
    <?php
    echo html::linkButton('<i class="icon icon-back icon-sm"></i> ' . $lang->goback, $browseLink, 'self', "data-app='{$app->tab}'", 'btn btn-secondary');

    $allLink     = $this->createLink('gogs', 'binduser', "gogsID={$gogsID}&type=all");
    $bindedLink  = $this->createLink('gogs', 'binduser', "gogsID={$gogsID}&type=binded");
    $notBindLink = $this->createLink('gogs', 'binduser', "gogsID={$gogsID}&type=notBind");
    if($type == 'all')
    {
        echo html::linkButton('' . $lang->gogs->all . "<span class='gogs-bind-all'>" . count($gogsUsers) . "</span>", $allLink, 'self', "data-app='{$app->tab}'", 'btn btn-info active');
        echo html::linkButton('' . $lang->gogs->notBind, $notBindLink, 'self', "data-app='{$app->tab}'", 'btn btn-info');
        echo html::linkButton('' . $lang->gogs->binded, $bindedLink, 'self', "data-app='{$app->tab}'", 'btn btn-info');
    }
    else if($type == 'binded')
    {
        echo html::linkButton('' . $lang->gogs->all, $allLink, 'self', "data-app='{$app->tab}'", 'btn btn-info');
        echo html::linkButton('' . $lang->gogs->notBind, $notBindLink, 'self', "data-app='{$app->tab}'", 'btn btn-info');
        echo html::linkButton('' . $lang->gogs->binded . "<span class='gogs-bind-all'>" . count($gogsUsers) . "</span>", $bindedLink, 'self', "data-app='{$app->tab}'", 'btn btn-info active');
    }
    else
    {
        echo html::linkButton('' . $lang->gogs->all, $allLink, 'self', "data-app='{$app->tab}'", 'btn btn-info');
        echo html::linkButton('' . $lang->gogs->notBind . "<span class='gogs-bind-all'>" . count($gogsUsers) . "</span>", $notBindLink, 'self', "data-app='{$app->tab}'", 'btn btn-info active');
        echo html::linkButton('' . $lang->gogs->binded, $bindedLink, 'self', "data-app='{$app->tab}'", 'btn btn-info');
    }
    ?>
  </div>
  <form method='post' class='load-indicator main-form form-ajax' enctype='multipart/form-data'>
    <div class="table-responsive">
      <table class="table table-borderless">
        <thead>
          <tr>
            <th><?php echo $lang->gogs->gogsAccount;?></th>
            <th><?php echo $lang->gogs->gogsEmail;?></th>
            <th><?php echo $lang->gogs->zentaoEmail;?></th>
            <th class="w-400px"><?php echo $lang->gogs->zentaoAccount;?> <span class="gogs-account-desc"><?php echo $lang->gogs->accountDesc;?></span></th>
            <th><?php echo $lang->gogs->bindingStatus;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($gogsUsers as $gogsUser):?>
          <?php echo html::hidden("gogsUserNames[$gogsUser->id]", $gogsUser->realname);?>
          <tr>
            <td>
              <?php echo html::image($gogsUser->avatar, "height=20 width=20 class='img-circle'");?>
              <?php echo $gogsUser->realname . '@' . $gogsUser->account;?>
            </td>
            <td><?php echo $gogsUser->email;?></td>
            <td class="email"><?php echo !empty($gogsUser->zentaoAccount) ? $zentaoUsers[$gogsUser->zentaoAccount]->email : '';?></td>
            <td class='zentao-users'><?php echo html::select("zentaoUsers[$gogsUser->id]", $gogsUser->zentaoUsers, $gogsUser->zentaoAccount, "class='form-control select chosen gogs-user-bind'" );?></td>
            <td>
              <?php if($gogsUser->binded === 1):?>
              <?php echo $lang->gogs->binded;?>
              <?php elseif($gogsUser->binded === 2):?>
              <?php echo '<span class="text-red">' . $lang->gogs->bindedError . '</span>';?>
              <?php else:?>
              <?php echo '<span class="text-red">' . $lang->gogs->notBind . '</span>';?>
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
