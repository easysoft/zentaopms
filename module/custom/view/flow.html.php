<?php
/**
 * The html template file of deny method of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: deny.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php if(isset($this->config->conceptSetted)):?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <div class='heading'>
      <strong><?php echo $lang->custom->flow?></strong>
    </div>
  </div>
<?php else:?>
<div class='container'>
  <div class='modal-dialog'>
    <div class='modal-header'>
      <strong><?php echo $lang->custom->flow?></strong>
    </div>
<?php endif;?>
  <form class='form-ajax' method='post'>
    <div class='modal-body'>
      <ol>
        <li>
          <div class="form-group">
            <label><?php echo $lang->custom->conceptQuestions['overview']?></label>
            <div class="checkbox"> <?php echo html::radio('sprintConcept', $lang->custom->sprintConceptList, zget($this->config->custom, 'sprintConcept', '0'))?> </div>
          </div>
        </li>
        <?php if($this->config->edition != 'max'):?>
        <li>
          <div class="form-group">
            <label id='storypoint'><?php echo $lang->custom->conceptQuestions['storypoint'];?></label>
            <div class="checkbox"> <?php echo html::radio('hourPoint', $lang->custom->conceptOptions->hourPoint, zget($this->config->custom, 'hourPoint'))?> </div>
          </div>
        </li>
        <?php endif;?>
      </ol>
      <div class="form-group">
        <label></label>
        <div><?php echo html::submitButton();?></div>
      </div>
    </div>
  </form>
<?php if(isset($this->config->conceptSetted)):?>
<?php include '../../common/view/footer.html.php';?>
<?php else:?>
<?php echo js::execute($pageJS);?>
</div>
</div>
</body>
</html>
<?php endif;?>
