<?php
/**
 * The start view file of tutorial module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     tutorial
 * @version     $Id: browse.html.php 4728 2013-05-03 06:14:34Z sunhao@cnezsoft.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div id='start' class='bg-primary'>
  <div class='start-icon'><i class='icon icon-certificate icon-spin icon-back'></i><i class='icon icon-flag icon-front text-primary'></i></div>
  <h1><?php echo $lang->tutorial->common;?></h1>
  <p><?php echo $lang->tutorial->desc;?></p>
  <?php echo html::a(inlink('index'), $lang->tutorial->start, '_top', "class='btn btn-primary btn-lg btn-wide btn-info'");;?>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
