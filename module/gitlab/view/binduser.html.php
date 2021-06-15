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
  <form method='post' class='load-indicator main-form' enctype='multipart/form-data' target='hiddenwin' id="batchCreateForm">
    <div class="table-responsive">
      <table class="table table-form w-500px">
        <thead>
          <tr>
            <th class='w-150px'><?php echo $lang->gitlab->gitlabAccount;?></th>
            <th class='w-150px'><?php echo $lang->gitlab->zentaoAccount;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($gitlabUsers as $gitlabUser):?>
          <?php if(isset($gitlabUser->zentaoAccount)) continue;?>
          <tr>
            <td>
              <?php echo html::image($gitlabUser->avatar, "height=40");?>
              <strong>
                <?php echo html::hidden('gitlabUsers[]', $gitlabUser->id) . $gitlabUser->realname;?>
                <br><?php echo "(" . $gitlabUser->account . ") &lt;" . $gitlabUser->email . "&gt;"; ?>
              </strong>
            </td>
            <td><?php echo html::select('zentaoUsers[]', $userPairs, '', "class='form-control select chosen'" );?></td>
         </tr>
         <?php endforeach;?>
         <?php foreach($gitlabUsers as $gitlabUser):?>
         <?php if(!isset($gitlabUser->zentaoAccount)) continue;?>
         <tr>
            <td>
            <strong><?php echo html::hidden('gitlabUsers[]', $gitlabUser->id) . $gitlabUser->realname;?></strong>
            <p><?php echo "(" . $gitlabUser->account . ") &lt;" . $gitlabUser->email . "&gt;"; ?></p>
            </td>
            <td><?php echo html::select('zentaoUsers[]', $userPairs, $gitlabUser->zentaoAccount, "class='form-control select chosen'" );?></td>
         </tr>
         <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="<?php echo count($visibleFields) + 3?>" class="text-center form-actions">
              <?php echo html::submitButton($lang->save);?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
