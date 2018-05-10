<?php
/**
 * The reset view file of score module of ZenTaoPMS.
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Memory <lvtao@cnezsoft.com>
 * @package     score
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <span class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->my->scoreRule;?></span></span>
  </div>
  <div class="btn-toolbar pull-right">
    <?php echo html::a($this->createLink('my', 'score'), $lang->score->common, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent" class='main-table'>
  <table class="table has-sort-head table-fixed">
    <thead>
      <tr>
        <th class="w-150px"><?php echo $lang->score->module;?></th>
        <th class="w-150px"><?php echo $lang->score->method;?></th>
        <th class="w-150px"><?php echo $lang->score->times;?></th>
        <th class="w-150px"><?php echo $lang->score->hour;?></th>
        <th class="w-80px"><?php echo $lang->score->score;?></th>
        <th><?php echo $lang->score->desc;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($config->score->rule as $module => $moduleRule):?>
      <?php foreach($moduleRule as $method => $rule):?>
      <tr>
        <td><?php echo $lang->score->modules[$module];?></td>
        <td><?php echo $lang->score->methods[$module][$method];?></td>
        <td><?php echo empty($rule['times']) ? $lang->score->noLimit : $rule['times'];?></td>
        <td><?php echo empty($rule['hour'])  ? $lang->score->noLimit : $rule['hour'];?></td>
        <td><?php echo $rule['score'];?></td>
        <td>
          <?php
          if(isset($lang->score->extended[$module][$method]))
          {
              $desc     = $lang->score->extended[$module][$method];
              $descRule = explode('##', $desc);
              if(!empty($descRule))
              {
                  foreach($descRule as $key => $value)
                  {
                      if($key % 2 == 1)
                      {
                          $match = explode(',', $value);
                          if(count($match) == 2)
                          {
                              $score = $config->score->ruleExtended[$module][$method][$match[0]][$match[1]];
                          }
                          else
                          {
                              $score = $config->score->ruleExtended[$module][$method][$match[0]];
                          }
                          $desc = str_replace('##' . $value . '##', $score, $desc);
                      }
                  }
              }
              echo $desc;
          }
          ?>
        </td>
      </tr>
      <?php endforeach;?>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include '../../common/view/footer.html.php'; ?>
