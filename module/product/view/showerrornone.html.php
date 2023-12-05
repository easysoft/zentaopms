<?php
/**
 * The none view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     product
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php if($this->app->tab == 'project'):?>
<div class="table-empty-tip">
  <p><span class="text-muted"><?php echo $lang->product->noProduct;?></span> <?php common::printLink('project', 'manageProducts', "projectID=$objectID", "<i class='icon icon-plus'></i> " . $lang->project->manageProducts, '', "class='btn btn-info'  data-app='project'");?></p>
</div>
<?php elseif($this->app->tab == 'execution'):?>
<div class="table-empty-tip">
  <p><span class="text-muted"><?php echo $lang->product->noProduct;?></span> <?php common::printLink('execution', 'manageProducts', "executionID=$objectID", "<i class='icon icon-plus'></i> " . $lang->execution->manageProducts, '', "class='btn btn-info'  data-app='execution'");?></p>
</div>
<?php else:?>
<div class="table-empty-tip">
  <p><span class="text-muted"><?php echo $lang->product->noProduct;?></span> <?php common::printLink('product', 'create', "programID=0&extra=from=$moduleName", "<i class='icon icon-plus'></i> " . $lang->product->create, '', "class='btn btn-info' data-app='product'");?></p>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
