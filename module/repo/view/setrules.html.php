<?php
/**
 * The setRules view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     repo
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class="main-header">
    <h2><?php echo $lang->repo->setRules;?></h2>
  </div>
  <form class='main-form form-ajax' method='post'>
    <table class='table table-form'>
      <tbody>
        <tr>
          <th class='w-110px'><?php echo $lang->repo->objectRule;?></th>
          <td class='w-400px'>
            <div class='input-group'>
              <?php foreach($config->repo->rules['module'] as $module => $match):?>
              <span class='input-group-addon'><?php echo $lang->{$module}->common;?></span>
              <?php echo html::input("rules[module][{$module}]", $match, "class='form-control'");?>
              <?php endforeach;?>
            </div>
          </td>
          <td class='w-150px'></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->repo->objectIdRule;?></th>
          <td>
            <div class='input-group'>
              <?php foreach($config->repo->rules['id'] as $method => $match):?>
              <span class='input-group-addon'><?php echo $lang->repo->$method;?></span>
              <?php echo html::input("rules[id][{$method}]", $match, "class='form-control'");?>
              <?php endforeach;?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->repo->actionRule;?></th>
          <td colspan='2'>
            <div class='input-group'>
              <?php $space = common::checkNotCN() ? ' ' : '';?>
              <span class='input-group-addon'><?php echo $lang->task->common . $space . $lang->task->start;?></span>
              <?php echo html::input("rules[task][start]", $config->repo->rules['task']['start'], "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->task->common . $space . $lang->task->finish;?></span>
              <?php echo html::input("rules[task][finish]", $config->repo->rules['task']['finish'], "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->bug->common . $space . $lang->bug->resolve;?></span>
              <?php echo html::input("rules[bug][resolve]", $config->repo->rules['bug']['resolve'], "class='form-control'");?>
            </div>
          </td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->repo->manHourRule;?></th>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->task->common . $space . $lang->task->logEfforts;?></span>
              <?php echo html::input("rules[task][logEfforts]", $config->repo->rules['task']['logEfforts'], "class='form-control'");?>
            </div>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->task->consumed?></span>
              <?php echo html::input("rules[task][consumed]", $config->repo->rules['task']['consumed'], "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->repo->mark?></span>
              <?php echo html::input("rules[mark][consumed]", $config->repo->rules['mark']['consumed'], "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->repo->ruleUnit?></span>
              <?php echo html::input("rules[unit][consumed]", $config->repo->rules['unit']['consumed'], "class='form-control'");?>
            </div>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->task->left?></span>
              <?php echo html::input("rules[task][left]", $config->repo->rules['task']['left'], "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->repo->mark?></span>
              <?php echo html::input("rules[mark][left]", $config->repo->rules['mark']['left'], "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->repo->ruleUnit?></span>
              <?php echo html::input("rules[unit][left]", $config->repo->rules['unit']['left'], "class='form-control'");?>
            </div>
          </td>
        </tr>
        <tr>
          <th></th>
          <td class='red' colspan='3'><?php echo $lang->repo->ruleSplit;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->repo->rules->exampleLabel;?></th>
          <td colspan='3' id='example'></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center'>
            <?php echo html::submitButton();?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php js::set('rulesExample', $lang->repo->rules->example);?>
<?php include '../../common/view/footer.html.php';?>
