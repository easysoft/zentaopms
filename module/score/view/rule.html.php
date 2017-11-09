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
      <th class="w-150px"><?php echo $lang->score->module;?></th>
      <th class="w-150px"><?php echo $lang->score->method;?></th>
      <th class="w-150px"><?php echo $lang->score->times;?></th>
      <th class="w-150px"><?php echo $lang->score->hour;?></th>
      <th class="w-150px"><?php echo $lang->score->score;?></th>
      <th><?php echo $lang->score->desc;?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($rule as $module => $value):?>
    <?php if($module == 'extended') break;?>
    <?php foreach($value as $oneKey => $oneValue):?>
    <tr>
      <td class="text-center"><?php echo $lang->score->models[$module];?></td>
      <td class="text-center"><?php echo $lang->score->methods[$module][$oneKey];?></td>
      <td class="text-center"><?php echo empty($oneValue['times']) ? $lang->score->noLimit : $oneValue['times'];?></td>
      <td class="text-center"><?php echo empty($oneValue['hour']) ? $lang->score->noLimit : $oneValue['hour'];?></td>
      <td class="text-center"><?php echo $oneValue['score'];?></td>
      <td>
        <?php
        if(isset($lang->score->extended->{$module . $oneKey}))
        {
            $str      = $lang->score->extended->{$module . $oneKey};
            $strArray = explode('#', $str);
            if(!empty($strArray)) 
            {
                foreach($strArray as $strKey => $strVal)
                {
                    if($strKey % 2 == 1)
                    {
                        $ab    = explode(',', $strVal);
                        $score = count($ab) == 3 ? $rule->extended->{$ab[0]}[$ab[1]][$ab[2]] : $rule->extended->{$ab[0]}[$ab[1]];
                        $str   = str_replace('#' . $strVal . '#', $score, $str);
                    }
                }
            }
            echo $str;
        }
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    <?php endforeach;?>
  </tbody>
</table>
<?php include '../../common/view/footer.html.php'; ?>
