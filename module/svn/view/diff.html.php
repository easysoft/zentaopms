<?php
/**
 * The export view file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     file
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php $catLink = inlink('cat', 'url=' . helper::safe64Encode($url) . "&revision=$revision");?>
<div class='box-title'><?php echo html::a($catLink, "$url@$revision");?></div>
<div class='box-content'><xmp><?php echo $diff;?></xmp></div>
<?php include '../../common/view/footer.lite.html.php';?>
