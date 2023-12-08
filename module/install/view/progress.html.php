<?php
/**
 * The progress view file of install module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     install
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php css::import($jsRoot . 'xterm/xterm.css');?>
<?php js::import($jsRoot  . 'xterm/xterm.js'); ?>
<?php js::set('solutionID', $solution->id);?>
<?php js::set('hasError', !in_array($solution->status, array('installing', 'installed', 'finish')));?>
<?php js::set('notices', $lang->solution->notices);?>
<?php js::set('errors', $lang->solution->errors);?>
<?php js::set('installLabel', $lang->solution->install);?>
<?php js::set('configLabel', $lang->solution->config);?>
<?php js::set('startInstall', $install);?>
<?php js::set('skipLang', $lang->install->solution->skip);?>
<?php js::set('backgroundLang', $lang->solution->background);?>
<?php js::set('isonlybody', isonlybody());?>

<?php if(isonlybody()):?>
<style>
.solution-progress {margin-top: -20px;}
.m-install-progress .modal-dialog {margin-top: 0;}
.step-title {font-size: 13px;}
.arrow {padding-bottom: 12px;}
.app-list {margin-top: -40px;}
</style>
<?php endif;?>

<div class='container'>
  <div class='modal-dialog'>
    <?php if(!isonlybody()):?>
    <div class='modal-header'>
      <h3><?php echo $lang->install->solution->progress;?></h3>
    </div>
    <?php endif;?>
    <div class='modal-body'>
    <div class='solution-progress'>
      <div id='terminal'><h3><?php echo $lang->install->solution->log;?></h3></div>
      <div class='text-center app-list'>
        <?php $components = json_decode($solution->components);?>
        <?php $order = 0;?>
        <?php foreach($components as $category => $cloudApp):?>
        <?php $active = (isset($cloudApp->status) && $cloudApp->status !='waiting') ? 'active' : '';?>
        <?php if($order++ > 0):?>
            <div class='arrow app-<?php echo $cloudApp->id;?> <?php echo $active;?>'>&rarr;</div>
        <?php endif;?>
        <div class='step app-<?php echo $cloudApp->id;?> <?php echo $active;?>'>
          <div class='step-no <?php echo $active;?>'><?php echo $order;?></div>
          <div class='step-title'><span id='<?php echo $cloudApp->alias;?>-status'></span><?php echo $cloudApp->alias;?></div>
        </div>
        <?php endforeach;?>
      </div>
      <div class='text-center'>
        <span class='progress load-indicator loading'></span>
        <span class='progress-message'></span>
      </div>
      <div class='error-message text-red text-center'></div>
      <div class='form-actions text-center'>
        <?php if(!isonlybody()) echo html::a(inlink('step6'), $lang->solution->background, '', "class='btn btn-install btn-primary btn-wide' id='skipInstallBtn'");?>
        <?php echo html::commonButton($lang->solution->retryInstall, "id='retryInstallBtn' class='hide'", 'btn btn-primary btn-wide');?>
        <?php if(!isonlybody()) echo html::commonButton($lang->solution->cancelInstall, "id='cancelInstallBtn'", 'btn btn-wide');?>
      </div>
    </div>
    </div>
  </div>
</div>
<?php include  $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
