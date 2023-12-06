<?php
/**
 * The setting view file of backup module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     backup
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->backup->setting;?></h2>
  </div>
  <?php if(!empty($error)):?>
  <div id='error'><?php echo $error;?></div>
  <?php else:?>
  <form method='post' target='hiddenwin'>
    <table class='w-p100'>
      <tr>
        <td style='height:80px;vertical-align:top'>
          <div class='input-group'>
            <?php echo html::checkbox('setting', $lang->backup->settingList, isset($config->backup->setting) ? $config->backup->setting : '');?>
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->backup->settingDir;?></span>
            <?php echo html::input('settingDir', !empty($config->backup->settingDir) ? $config->backup->settingDir : $this->app->getTmpRoot() . 'backup/', "class='form-control'");?>
          </div>
        </td>
      </tr>
      <?php if(common::hasPriv('backup', 'change')):?>
      <tr>
        <td>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->backup->change;?></span>
            <?php echo html::input('holdDays', $config->backup->holdDays, "class='form-control'");?>
            <span class='input-group-addon'><?php echo $lang->day;?></span>
          </div>
        </td>
      </tr>
      <?php endif;?>
      <tr><td><?php echo html::submitButton('', '', 'btn btn-primary');?></td></tr>
    </table>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.lite.html.php';?>

