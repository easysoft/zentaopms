<?php
/**
 * The html template file of execute method of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: execute.html.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='container'>
  <div class='modal-dialog'>
    <div class='modal-header'>
      <i class="icon-refresh"></i>
      <strong><?php echo sprintf($lang->upgrade->processCaseTitle, $total);?></strong>
    </div>
    <div class='modal-body'>
     <div>
       <div class='form-group'>
         <ul id='resultBox'>
           <li class="text-info"><?php echo $lang->upgrade->processCaseTip;?></li>
         </ul>
       </div>
     </div>
    </div>
    <div class='modal-footer'>
      <div class='from-group'>
        <?php echo html::a($this->createLink('upgrade', 'processCase'), $lang->upgrade->sureExecute, '', "class='btn btn-primary' id='execButton'");?>
        <?php echo html::a($this->createLink('upgrade', 'afterExec', "fromVersion=$fromVersion"), $lang->upgrade->next, '', "class='btn btn-primary hidden' id='nextButton'");?>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
