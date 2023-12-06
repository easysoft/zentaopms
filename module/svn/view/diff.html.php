<?php
/**
 * The export view file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     file
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php $catLink = $this->loadModel('repo')->buildURL('cat', $url, $revision);?>
<div class='detail'>
  <div class='detail-title'><?php echo html::a($catLink, "$url@$revision");?></div>
  <div class='detail-content'><xmp><?php echo $diff;?></xmp></div>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
