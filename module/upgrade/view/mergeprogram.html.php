<?php
/**
 * The mergeProgram view file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php js::set('weekend', $config->execution->weekend);?>
<?php js::set('type', $type);?>
<?php js::set('mode', $systemMode);?>
<?php js::set('errorNoProduct', $lang->upgrade->errorNoProduct);?>
<?php js::set('errorNoExecution', $lang->upgrade->errorNoExecution);?>
<div class='container'>
  <form method='post' target='hiddenwin'>
    <div class='modal-dialog'>
      <div class='panel'>
        <div class='panel-heading text-center'>
          <h2><?php echo $lang->upgrade->mergeModes['manually'];?></h2>
        </div>
        <div class='panel-body'>
          <?php if($type == 'productline'):?>
          <?php include './mergebyline.html.php';?>
          <?php elseif($type == 'product'):?>
          <?php include './mergebyproduct.html.php';?>
          <?php elseif($type == 'sprint' or $type == 'moreLink'):?>
          <?php include './mergebysprint.html.php';?>
          <?php endif;?>
        </div>
      </div>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
