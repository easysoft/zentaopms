<?php
/**
 * The batch create view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php $browseLink = $this->createLink('gitlab', 'browse', ""); ?>
<?php js::set('zentaoUsers', $zentaoUsers);?>
<div id="mainContent" class="main-content">
  <div class="main-header gitlab-bind">
    <?php
    echo html::linkButton('<i class="icon icon-back icon-sm"></i> ' . $lang->goback, $browseLink, 'self', "data-app='{$app->tab}'", 'btn btn-secondary');

    $allLink     = $this->createLink('gitlab', 'binduser', "gitlabID={$gitlabID}&type=all");
    $bindedLink  = $this->createLink('gitlab', 'binduser', "gitlabID={$gitlabID}&type=binded");
    $notBindLink = $this->createLink('gitlab', 'binduser', "gitlabID={$gitlabID}&type=notBind");
    if($type == 'all')
    {
        echo html::linkButton('' . $lang->gitlab->all . "<span class='gitlab-bind-all'>" . count($gitlabUsers) . "</span>", $allLink, 'self', "data-app='{$app->tab}'", 'btn btn-info active');
        echo html::linkButton('' . $lang->gitlab->notBind, $notBindLink, 'self', "data-app='{$app->tab}'", 'btn btn-info');
        echo html::linkButton('' . $lang->gitlab->binded, $bindedLink, 'self', "data-app='{$app->tab}'", 'btn btn-info');
    }
    else if($type == 'binded')
    {
        echo html::linkButton('' . $lang->gitlab->all, $allLink, 'self', "data-app='{$app->tab}'", 'btn btn-info');
        echo html::linkButton('' . $lang->gitlab->notBind, $notBindLink, 'self', "data-app='{$app->tab}'", 'btn btn-info');
        echo html::linkButton('' . $lang->gitlab->binded . "<span class='gitlab-bind-all'>" . count($gitlabUsers) . "</span>", $bindedLink, 'self', "data-app='{$app->tab}'", 'btn btn-info active');
    }
    else
    {
        echo html::linkButton('' . $lang->gitlab->all, $allLink, 'self', "data-app='{$app->tab}'", 'btn btn-info');
        echo html::linkButton('' . $lang->gitlab->notBind . "<span class='gitlab-bind-all'>" . count($gitlabUsers) . "</span>", $notBindLink, 'self', "data-app='{$app->tab}'", 'btn btn-info active');
        echo html::linkButton('' . $lang->gitlab->binded, $bindedLink, 'self', "data-app='{$app->tab}'", 'btn btn-info');
    }
    ?>
  </div>
  <form method='post' class='load-indicator main-form form-ajax' enctype='multipart/form-data'>
    <div class="table-responsive">
      <table class="table table-borderless">
        <thead>
          <tr>
            <th><?php echo $lang->gitlab->gitlabAccount;?></th>
            <th><?php echo $lang->gitlab->gitlabEmail;?></th>
            <th><?php echo $lang->gitlab->zentaoEmail;?></th>
            <th class="w-400px"><?php echo $lang->gitlab->zentaoAccount;?> <span class="gitlab-account-desc"><?php echo $lang->gitlab->accountDesc;?></span></th>
            <th><?php echo $lang->gitlab->bindingStatus;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($gitlabUsers as $gitlabUser):?>
          <?php echo html::hidden("gitlabUserNames[$gitlabUser->id]", $gitlabUser->realname);?>
          <tr>
            <td>
              <?php echo html::image($gitlabUser->avatar, "height=20 width=20 class='img-circle'");?>
              <?php echo $gitlabUser->realname . '@' . $gitlabUser->account;?>
            </td>
            <td><?php echo $gitlabUser->email;?></td>
            <td class="email"><?php echo !empty($gitlabUser->zentaoAccount) ? $zentaoUsers[$gitlabUser->zentaoAccount]->email : '';?></td>
            <td class='zentao-users'><?php echo html::select("zentaoUsers[$gitlabUser->id]", $gitlabUser->zentaoUsers, $gitlabUser->zentaoAccount, "class='form-control select chosen gitlab-user-bind'" );?></td>
            <td>
              <?php if($gitlabUser->binded === 1):?>
              <?php echo $lang->gitlab->binded;?>
              <?php elseif($gitlabUser->binded === 2):?>
              <?php echo '<span class="text-red">' . $lang->gitlab->bindedError . '</span>';?>
              <?php else:?>
              <?php echo '<span class="text-red">' . $lang->gitlab->notBind . '</span>';?>
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
<div id="userList" class="hidden">
<?php
foreach($userPairs as $account => $realname)
{
    echo "<option value='$account' title='$realname'>$realname</option>";
}
?>
</div>
<?php include '../../common/view/footer.html.php';?>
