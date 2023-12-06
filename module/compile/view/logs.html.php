<?php
/**
 * The browse view file of compile module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     compile
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <div class="page-title">
      <strong><?php echo $lang->compile->logs;?></strong>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if($job->engine == 'gitlab') echo html::a(helper::createLink('ci', "checkCompileStatus", "compileID={$build->id}"), "<i class='icon icon-eye icon-sm'></i> ". $lang->compile->refresh, '', "class='btn btn-secondary' id='refreshBtn'");?>
    <?php echo html::a(helper::createLink('compile', "browse", "repoID={$job->repo}&jobID={$build->job}"), "<i class='icon icon-back icon-sm'></i> ". $lang->goback, '', "class='btn btn-secondary'");?>
  </div>
</div>
<div id='mainContent'>
  <div class='main-content'><?php echo $logs;?></div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
