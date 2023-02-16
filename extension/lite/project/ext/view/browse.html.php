<?php
/**
 * The prjbrowse view file of project module for lite vision of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     project
 * @version     $Id: prjbrowse.html.php 4769 2013-05-05 07:24:21Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<?php include $this->app->getModuleRoot() . '/common/view/datatable.fix.html.php';?>
<?php
js::set('orderBy', $orderBy);
js::set('programID', $programID);
js::set('browseType', $browseType);
js::set('param', $param);
js::set('orderBy', $orderBy);
js::set('recTotal', $recTotal);
js::set('recPerPage', $recPerPage);
js::set('pageID', $pageID);
?>
<?php include $this->app->getModuleRoot() . '/project/view/browsebylist.html.php';?>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
