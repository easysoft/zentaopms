<?php
/**
 * The cat view file of git module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     file
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='detail'>
  <div class='detail-title'><?php echo "$path@$revision";?></div>
  <div class='detail-content'><xmp><?php echo $code;?></xmp></div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
