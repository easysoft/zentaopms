<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php if(empty($executions)):?>
<div class="table-empty-tip">
  <p><span class="text-muted"><?php echo $lang->execution->noProject;?></span> <?php common::printLink('execution', 'create', '', "<i class='icon icon-plus'></i> " . $lang->execution->create, '', "class='btn btn-info'");?></p>
</div>
<?php else:?>
<?php echo $this->fetch('block', 'dashboard', 'module=execution');?>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
