<?php
/**
 * The browse view file of plan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     plan
 * @version     $Id: browse.html.php 4707 2013-05-02 06:57:41Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmDelete', $lang->productplan->confirmDelete)?>
<?php js::set('browseType', $browseType);?>
<?php js::set('productID', $productID);?>
<?php js::set('noLinkedProject', $lang->productplan->noLinkedProject);?>
<?php js::set('enterProjectList', $lang->productplan->enterProjectList);?>
<?php js::set('projectNotEmpty', $lang->productplan->projectNotEmpty)?>
<?php
if($viewType == 'bykanban')
{
    include 'browsebykanban.html.php';
}
else
{
    include 'browsebylist.html.php';
}
?>
<?php include '../../common/view/footer.html.php';?>
