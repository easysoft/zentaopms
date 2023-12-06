<?php
/**
 * The header view file of webhook module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     webhook
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'><?php // common::printAdminSubMenu('message');?></div>
  <div class='btn-toolbar pull-right'>
    <?php if(common::hasPriv('webhook', 'create') and ($app->rawMethod == 'browse') or $app->rawMethod == 'log') echo html::a($this->createLink('webhook', 'create'), "<i class='icon-plus'></i> {$lang->webhook->create}", '', "class='btn btn-primary'");?>
  </div>
</div>
