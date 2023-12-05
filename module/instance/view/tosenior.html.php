<?php
/**
 * The upgrade to senior App view file of instance module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   instance
 * @version   $Id$
 * @link      https://www.qucheng.cn
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
      <h2><?php echo $lang->instance->toSeniorAttention;?></h2>
  </div>
  <div>
    <form id='toSeniorForm' action="<?php echo $this->inLink('toSenior', "instanceID={$instance->id}&seniorAppID={$cloudApp->id}&confirm=yes");?>" class="cell not-watch load-indicator main-form form-ajax">
      <div><?php echo $lang->instance->toSeniorTips;?></div>
      <div>
        <?php echo html::checkbox('read', array('true'=>$lang->instance->hasRead));?>
      </div>
      <div class="text-center form-actions"><?php echo html::submitButton($lang->instance->upgrade, 'disabled');?></div>
    </form>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
