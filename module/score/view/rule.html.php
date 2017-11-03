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
  <div id='titlebar'>
    <div class='heading'><?php echo $lang->my->scoreRule; ?></div>
    <div class='actions'>
        <?php echo html::a($this->createLink('my', 'score'), $lang->score->common, '', "class='btn btn-primary'");?>
    </div>
  </div>
  <table class="table table-striped">
    <thead>
    <tr>
      <th class="w-150px"><?php echo $this->lang->score->module;?></th>
      <th class="w-150px"><?php echo $this->lang->score->method;?></th>
      <th class="w-150px"><?php echo $this->lang->score->times;?></th>
      <th class="w-150px"><?php echo $this->lang->score->hour;?></th>
      <th class="w-150px"><?php echo $this->lang->score->score;?></th>
      <th>备注</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($rule  as $key => $value):?>
        <?php if($key == 'extended') break;?>
        <?php foreach($value as $oneKey => $oneValue):?>
    <tr>
      <td class="text-center"><?php echo $this->lang->score->models[$key];?></td>
      <td class="text-center"><?php echo $this->lang->score->methods[$key][$oneKey];?></td>
      <td class="text-center"><?php echo empty($oneValue['times']) ? $this->lang->score->noLimit : $oneValue['times'];?></td>
      <td class="text-center"><?php echo empty($oneValue['hour']) ? $this->lang->score->noLimit : $oneValue['hour'];?></td>
      <td class="text-center"><?php echo $oneValue['score'];?></td>
      <td>
          <?php
          if(isset($this->lang->score->extended->{$key . $oneKey}))
          {
              $str      = $this->lang->score->extended->{$key . $oneKey};
              $strArray = explode('#', $str);
              if(!empty($strArray)) foreach($strArray as $strKey => $strVal)
              {
                  if($strKey % 2 == 1)
                  {
                      $ab    = explode(',', $strVal);
                      $score = count($ab) == 3 ? $rule->extended->{$ab[0]}[$ab[1]][$ab[2]] : $rule->extended->{$ab[0]}[$ab[1]];
                      $str   = str_replace('#' . $strVal . '#', $score, $str);
                  }
              }
              echo $str;
          }
          ?>
      </td>
    </tr>
        <?php endforeach;?>
    <?php endforeach;?>
    </tfoot>
  </table>
<?php include '../../common/view/footer.html.php'; ?>