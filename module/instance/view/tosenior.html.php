<?php
/**
 * The upgrade to senior App view file of instance module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QurCheng Software Co,LTD, www.qucheng.cn)
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
