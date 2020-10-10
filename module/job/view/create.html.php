<?php
/**
 * The create view file of job module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     job
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>

<?php js::set('repoTypes', $repoTypes);?>
<?php js::set('triggerType', 'tag');?>
<?php js::set('dirChange', $lang->job->dirChange);?>
<?php js::set('buildTag', $lang->job->buildTag);?>

<div id='mainContent' class='main-row'>
  <div class='main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->job->create; ?></h2>
      </div>
      <form id='jobForm' method='post' class='form-ajax'>
        <table class='table table-form'>
          <tr>
            <th class='w-120px'><?php echo $lang->job->name; ?></th>
            <td class='required'><?php echo html::input('name', '', "class='form-control'"); ?></td>
            <td colspan="2" ></td>
          </tr>
          <tr>
            <th><?php echo $lang->job->repo; ?></th>
            <td><?php echo html::select('repo', $repoPairs, '', "class='form-control chosen'"); ?></td>
          </tr>
          <tr>
            <th><?php echo $lang->job->product; ?></th>
            <td><?php echo html::select('product', $products, '', "class='form-control chosen'"); ?></td>
          </tr>
          <tr>
            <th><?php echo $lang->job->frame; ?></th>
            <td><?php echo html::select('frame', $lang->job->frameList, '', "class='form-control chosen'"); ?></td>
          </tr>
          <tr>
            <th><?php echo $lang->job->triggerType; ?></th>
            <td><?php echo html::select('triggerType', $lang->job->triggerTypeList, '', "class='form-control chosen'");?></td>
            <td colspan="2"></td>
          </tr>
          <tr id='svnDirBox' class='svn-fields'>
            <th><?php echo $lang->job->svnDir;?></th>
            <td colspan='3'>
              <div class='input-group'>
                <?php echo html::select('svnDir[]', array('' => ''), '', "class='form-control chosen'");?>
              </div>
            </td>
          </tr>
          <tr class="comment-fields">
            <th><?php echo $lang->job->comment;?></th>
            <td class='required'><?php echo html::input('comment', '', "class='form-control'");?></td>
            <td colspan='2'><?php echo $lang->job->commitEx;?></td>
          </tr>
          <tr class="custom-fields">
            <th rowspan='2'></th>
            <td colspan="3"><?php echo html::checkbox('atDay', $lang->datepicker->dayNames, '', '', 'inline');?></td>
          </tr>
          <tr class="custom-fields">
            <td>
              <div class='input-group'>
                <span class='input-group-addon'><?php echo $lang->job->atTime;?></span>
                <?php echo html::input('atTime', '', "class='form-control form-time'");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->job->jkHost; ?></th>
            <td colspan='2'>
              <div class='table-row'>
                <div class='table-col'><?php echo html::select('jkHost', $jkHostList, '', "class='form-control chosen'"); ?></div>
                <div id='jkJobBox' class='table-col'>
                  <div class='input-group'>
                    <span class='input-group-addon'><?php echo $lang->job->jkJob; ?></span>
                    <?php echo html::select('jkJob', array('' => ''), '', "class='form-control chosen'"); ?>
                  </div>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <th></th>
            <td class='text-center form-actions'>
              <?php echo html::submitButton(); ?>
              <?php echo html::backButton(); ?>
              <?php echo html::hidden('repoType');?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
