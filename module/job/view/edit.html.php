<?php
/**
 * The edit view file of job module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     job
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>

<?php js::set('repoTypes', $repoTypes);?>
<?php js::set('triggerType', $job->triggerType);?>
<?php js::set('pipeline', $job->pipeline);?>
<?php js::set('dirChange', $lang->job->dirChange);?>
<?php js::set('buildTag', $lang->job->buildTag);?>
<?php js::set('jkTask', $job->pipeline);?>
<?php js::set('job', $job);?>
<?php js::set('frameList', $lang->job->frameList);?>
<?php js::set('jenkinsPipeline', $job->engine == 'jenkins' ? $job->pipeline : '');?>

<div id='mainContent' class='main-row'>
  <div class='main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->job->edit;?></h2>
      </div>
      <form id='jobForm' method='post' class='form-ajax'>
        <table class='table table-form'>
          <tr>
            <th class='w-140px'><?php echo $lang->job->name;?></th>
            <td class='required'><?php echo html::input('name', $job->name, "class='form-control'");?></td>
            <td colspan="2" ></td>
          </tr>
          <tr>
            <th><?php echo $lang->job->engine;?></th>
            <td><?php echo zget($lang->job->engineList, $job->engine, '') . html::hidden('engine', $job->engine);?>
            </td>
          </tr>
          <tr class='commonRepo'>
            <th><?php echo $lang->job->repo; ?></th>
            <td><?php echo html::select('repo', $repoPairs, $job->repo, "class='form-control chosen'"); ?></td>
          </tr>
          <tr class='gitlabRepo hide'>
            <th><?php echo $lang->job->repo; ?></th>
            <td> <?php echo html::select('gitlabRepo', $gitlabRepos, $job->repo, "class='chosen form-control'");?> </td>
            <td> <?php echo html::select('reference', $refList, isset($job->reference) ? $job->reference : '', "class='chosen form-control'");?> </td>
          </tr>
          <tr>
            <th><?php echo $lang->job->product;?></th>
            <td><?php echo html::select('product', $products, $job->product, "class='form-control chosen'");?></td>
          </tr>
          <tr id="frameBox">
            <th><?php echo $lang->job->frame;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::select('frame', array('' => ''), '', "class='form-control chosen'"); ?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->job->triggerType;?></th>
            <?php if($repoType == 'Subversion') $lang->job->triggerTypeList['tag'] = $lang->job->dirChange;?>
            <td><?php echo html::select('triggerType', $lang->job->triggerTypeList, $job->triggerType, "class='form-control chosen'");?></td>
            <td colspan="2"></td>
          </tr>
          <tr id='svnDirBox' class='svn-fields'>
            <th><?php echo $lang->job->svnDir;?></th>
            <td colspan='3'>
              <div class='input-group'>
                <?php
                if($repoType == 'Subversion' and $job->svnDir)
                {
                    $path = '';
                    $svnDirs = trim($job->svnDir, '/');
                    $svnDirs = empty($svnDirs) ? '' : $job->svnDir;
                    $svnDirs = explode('/', $svnDirs);
                    foreach($svnDirs as $i => $svnDir)
                    {
                        $path .= '/' . $svnDir;
                        $tags = $this->loadModel('svn')->getRepoTags($repo, $path);
                        if(empty($tags)) continue;

                        $selected  = isset($svnDirs[$i + 1]) ? $path . $svnDirs[$i + 1] : '/';
                        echo "<select id='svnDir{$i}' name='svnDir[]' class='form-control chosen'>";
                        echo "<option value='/' data-encodePath='" . $this->repo->encodePath($path) . "'>/</option>";
                        foreach($tags as $dirPath => $dirName) echo "<option value='{$dirPath}' data-encodePath='" . $this->repo->encodePath($dirPath) . "' " . ($selected == $dirPath ? 'selected' : '') . ">/" . basename($dirPath) . "</option>";
                        echo "</select>";
                    }
                }
                ?>
              </div>
            </td>
          </tr>
          <tr class='sonarqube hide'>
            <th><?php echo $lang->job->sonarqubeServer;?></th>
            <td><?php echo html::select('sonarqubeServer', $sonarqubeServerList, $job->sonarqubeServer, "class='form-control chosen' required");?></td>
            <td colspan="2"></td>
          </tr>
          <tr id='sonarProject' class='sonarqube hide'>
            <th><?php echo $lang->job->projectKey;?></th>
            <td class="required">
              <div class='input-group'>
                <?php echo html::select('projectKey', array('' => ''), '', "class='form-control chosen'");?>
              </div>
            </td>
            <td colspan="2"></td>
          </tr>
          <tr class="comment-fields">
            <th><?php echo $lang->job->comment;?></th>
            <td class='required'><?php echo html::input('comment', $job->comment, "class='form-control'");?></td>
            <td colspan='2'><?php echo $lang->job->commitEx;?></td>
          </tr>
          <tr class="custom-fields">
            <th rowspan='2'></th>
            <td colspan="3"><?php echo html::checkbox('atDay', $lang->datepicker->dayNames, $job->atDay, '', 'inline');?></td>
          </tr>
          <tr class="custom-fields">
            <td>
              <div class='input-group'>
                <span class='input-group-addon'><?php echo $lang->job->atTime;?></span>
                <?php echo html::input('atTime', $job->atTime, "class='form-control form-time'");?>
              </div>
            </td>
          </tr>
          <?php if($job->engine == 'jenkins'):?>
          <tr id="jenkinsServerTR">
            <th><?php echo $lang->job->server;?></th>
            <td colspan='2'>
              <div class='table-row'>
                <div class='table-col'><?php echo html::select('jkServer', $jenkinsServerList, $job->server, "class='form-control chosen'"); ?></div>
                <div class='table-col'>
                  <div class='input-group'>
                    <span class='input-group-addon'><?php echo $lang->job->pipeline;?></span>
                    <div class='dropdown'>
                    <?php echo html::hidden('jkTask');?>
                    <button data-toggle='dropdown' type='button' class='btn jktask-label required text-right'  title=''><span class='text'><?php echo $job->pipeline;?></span> <span class='caret' style='margin-bottom: -1px'></span></button>
                    <div id='dropMenuTasks' class='dropdown-menu search-list' data-ride='searchList' data-url=''></div>
                    </div>
                  </div>
                </div>
              </div>
            </td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->job->customParam;?></th>
            <td colspan='2' id='paramDiv'>
              <?php if($job->customParam):?>
              <?php foreach(json_decode($job->customParam) as $paramName => $paramValue):?>
              <div class='table-row input-group'>
                <span class='input-group-addon w-50px'><?php echo $lang->job->paramName;?></span>
                <?php echo html::input('paramName[]', $paramName, "class='form-control'");?>
                <span class='input-group-addon w-40px'><?php echo $lang->job->paramValue;?></span>
                <?php $isCustom = zget($lang->job->paramValueList, $paramValue, '') ? false : true;?>
                <?php if($isCustom):?>
                <?php echo html::input('paramValue[]', $paramValue, "class='form-control'");?>
                <?php echo html::select('paramValue[]', $lang->job->paramValueList, '', "class='form-control hidden' onchange='setParamName(this)' disabled");?>
                <?php else:?>
                <?php echo html::input('paramValue[]', '', "class='form-control hidden' id='paramValue' disabled");?>
                <?php echo html::select('paramValue[]', $lang->job->paramValueList, $paramValue, "class='form-control' onchange='setParamName(this)'");?>
                <?php endif;?>
                <span class='input-group-addon w-90px'>
                  <div class='checkbox-primary'>
                  <input type='checkbox' name='custom' id='custom' value='1' onclick='setValueInput(this);' <?php if($isCustom) echo 'checked';?> />
                    <label for='custom'><?php echo $lang->job->custom;?></label>
                  </div>
                </span>
                <span class='input-group-addon w-40px'><a href='javascript:;' onclick='addItem(this);'><i class='icon icon-plus'></i></a></span>
                <span class='input-group-addon w-40px'><a href='javascript:;' onclick='deleteItem(this)'><i class='icon icon-close'></i></a></span>
              </div>
              <?php endforeach;?>
              <?php endif;?>
              <div class='table-row input-group'>
                <span class='input-group-addon <?php echo common::checkNotCN() ? 'w-60px' : 'w-50px'?>'><?php echo $lang->job->paramName; ?></span>
                <?php echo html::input('paramName[]', '', "class='form-control' id='paramName'");?>
                <span class='input-group-addon <?php echo common::checkNotCN() ? 'w-60px' : 'w-40px'?>'><?php echo $lang->job->paramValue; ?></span>
                <?php echo html::select('paramValue[]', $lang->job->paramValueList, '', "class='form-control' onchange='setParamName(this)'");?>
                <?php echo html::input('paramValue[]', '', "class='form-control hidden' id='paramValue' disabled");?>
                <span class='input-group-addon w-90px'>
                  <div class='checkbox-primary'>
                    <input type='checkbox' name='custom' id='custom' value='1' onclick='setValueInput(this);'/>
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
              <?php echo html::submitButton();?>
              <?php if(!isonlybody()) echo html::a(inlink('browse', "repoID={$job->repo}"), $lang->goback, '', 'class="btn btn-wide"');?>
              <?php echo html::hidden('repoType', zget($repoTypes, $job->repo, 'Git'));?>
            </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
