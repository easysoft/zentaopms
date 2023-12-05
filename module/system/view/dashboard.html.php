<?php
/**
 * The html template file of index method of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package     my
 * @version     $Id$
 * @link        https://www.qucheng.com
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<div id="mainContent" class="main-row">
  <div class="main-col col-12">
    <div class="panel"><?php include 'overviewblock.html.php';?></div>
    <div class="panel"><?php include 'instancesblock.html.php';?></div>
  </div>
</div>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
