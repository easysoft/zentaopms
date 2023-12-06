<?php
/**
 * The create view file of job module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     job
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>

<?php js::set('repoTypes', $repoTypes);?>
<?php js::set('triggerType', 'tag');?>
<?php js::set('dirChange', $lang->job->dirChange);?>
<?php js::set('buildTag', $lang->job->buildTag);?>
<?php js::set('frameList', $lang->job->frameList);?>

<div id='mainContent' class='main-row'>
  <div class='main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->job->create; ?></h2>
      </div>
      <form id='jobForm' method='post' class='form-ajax'>
        <table class='table table-form'>
          <tr>
            <th class='w-140px'><?php echo $lang->job->name; ?></th>
            <td class='required'><?php echo html::input('name', '', "class='form-control'"); ?></td>
            <td colspan="2" ></td>
          </tr>
          <tr>
            <th><?php echo $lang->job->engine; ?></th>
            <td>
              <div class='table-row'>
                <div class='table-col'><?php echo html::select('engine', $lang->job->engineList, '', "class='form-control chosen'"); ?></div>
                </div>
              </div>
            </td>
            <td colspan='2'>
              <span id="gitlabServerTR"><?php echo $lang->job->engineTips->success;?></span>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->job->repo; ?></th>
            <td><?php echo html::select('repo', $repoPairs, '', "class='form-control chosen'"); ?></td>
            <td class='reference hide'><?php echo html::select('reference', array(), '', "class='chosen form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->job->product; ?></th>
            <td><?php echo html::select('product', '', '', "class='form-control chosen'"); ?></td>
          </tr>
          <tr id="frameBox">
            <th><?php echo $lang->job->frame; ?></th>
            <td>
              <div class='input-group'>
                <?php echo html::select('frame', array('' => ''), '', "class='form-control chosen'"); ?>
              </div>
            </td>
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
          <tr class='sonarqube hide'>
            <th><?php echo $lang->job->sonarqubeServer;?></th>
            <td><?php echo html::select('sonarqubeServer', $sonarqubeServerList, '', "class='form-control chosen' required");?></td>
            <td colspan="2"></td>
          </tr>
          <tr id='sonarProject' class='sonarqube hide'>
            <th><?php echo $lang->job->projectKey;?></th>
            <td class='required'>
              <div class='input-group'>
                <?php echo html::select('projectKey', array('' => ''), '', "class='form-control chosen'");?>
              </div>
            </td>
            <td colspan="2"></td>
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
          <tr id="jenkinsServerTR">
            <th><?php echo $lang->job->jkHost; ?></th>
            <td colspan='2'>
              <div class='table-row'>
                <div class='table-col'><?php echo html::select('jkServer', $jenkinsServerList, '', "class='form-control chosen'"); ?></div>
                <div class='table-col'>
                  <div class='input-group'>
                    <span class='input-group-addon'><?php echo $lang->job->pipeline;?></span>
                    <div class='dropdown'>
                    <?php echo html::hidden('jkTask');?>
                    <button data-toggle='dropdown' type='button' class='btn jktask-label required text-right'  title=''><span class='text'></span> <span class='caret' style='margin-bottom: -1px'></span></button>
                    <div id='dropMenuTasks' class='dropdown-menu search-list' data-ride='searchList' data-url=''></div>
                    </div>
                  </div>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->job->customParam;?></th>
            <td colspan='2' id='paramDiv'>
              <div class='table-row input-group'>
                <span class='input-group-addon <?php echo common::checkNotCN() ? 'w-60px' : 'w-50px'?>'><?php echo $lang->job->paramName; ?></span>
                <?php echo html::input('paramName[]', '', "class='form-control'"); ?>
                <span class='input-group-addon <?php echo common::checkNotCN() ? 'w-60px' : 'w-40px'?>'><?php echo $lang->job->paramValue; ?></span>
                <?php echo html::select('paramValue[]', $lang->job->paramValueList, '', "class='form-control' onchange='setParamName(this)'"); ?>
                <?php echo html::input('paramValue[]', '', "class='form-control hidden' id='paramValue' disabled"); ?>
                <span class='input-group-addon w-90px'>
                  <div class='checkbox-primary'>
                    <input type='checkbox' name='custom' id='custom' value='1' onclick='setValueInput(this);' />
                    <label for='custom'><?php echo $lang->job->custom;?></label>
                  </div>
                </span>
                <span class='input-group-addon w-40px'><a href='javascript:;' onclick='addItem(this);'><i class='icon icon-plus'></i></a></span>
                <span class='input-group-addon w-40px'><a href='javascript:;' onclick='deleteItem(this)'><i class='icon icon-close'></i></a></span>
              </div>
            </td>
          </tr>
          <tr>
            <td colspan="3" class='text-center form-actions'>
              <?php echo html::submitButton(); ?>
              <?php if(!isonlybody()) echo html::a(inlink('browse', ""), $lang->goback, '', 'class="btn btn-wide"');?>
              <?php echo html::hidden('repoType');?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
