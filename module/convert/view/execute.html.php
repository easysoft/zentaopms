<?php
/**
 * The html template file of execute method of convert module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id: execute.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='container mw-700px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon('cloud-upload');?></span>
      <strong><?php echo $lang->convert->common;?></strong>
      <small class='text-muted'><?php echo $lang->convert->execute;?> </small>
      <strong class='text-important'> <?php echo strtoupper($source);?></strong>
    </div>
  </div>
  <div class='alert'>
    <?php echo $executeResult;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
