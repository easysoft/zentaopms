<?php
/**
 * The design step bar view file of AI module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianyu Chen <chenjianyu@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>
<header id="mainMenu" class='design-steps'>
  <?php echo html::backButton("<i class='icon icon-back icon-sm'></i> $lang->goback", '', 'btn btn-info');?>
  <div class='steps'>
    <?php
    $stepstatus = array();
    $stepstatus['role']    = 'active';
    $stepstatus['data']    = 'active';
    $stepstatus['purpose'] = 'current';
    $stepstatus['target']  = 'clickable';
    $stepstatus['finalize']   = 'disabled';

    foreach($lang->ai->designStepNav as $index => $stepLang)
    {
      $arrow = '';
      $currentStepStatus = current($stepstatus);
      $nextStepStatus = next($stepstatus);
      if($index != 'finalize')
      {
        $arrowClass = $currentStepStatus == $nextStepStatus
        || ($currentStepStatus == 'clickable' && $nextStepStatus == 'disabled')
          ? 'outline-arrow'
          : 'solid-arrow';
        $arrow = "<div class='$arrowClass'></div>";
      }
      echo "<div class='step $currentStepStatus'><a href=''>$stepLang</a>$arrow</div>";
    }
    ?>
  </div>
  <?php echo html::commonButton("<i class='icon icon-save icon-sm'></i> $lang->save", '', 'btn btn-primary');?>
  <script>
    const steps = document.getElementsByClassName('steps')[0];
    if(steps)
    {
      for(let step of steps.children)
      {
        step.addEventListener('mouseenter', function (e)
        {
          if(step.lastElementChild?.classList.contains('outline-arrow'))
          {
            step.lastElementChild.classList.remove('outline-arrow');
            step.lastElementChild.classList.add('hover-arrow');
          }

          if(step.previousElementSibling?.lastElementChild?.classList.contains('outline-arrow'))
          {
            step.previousElementSibling.lastElementChild.classList.add('solid-arrow');
          }
        });
        step.addEventListener('mouseleave', function (e)
        {
          if (step.lastElementChild?.classList.contains('hover-arrow'))
          {
            step.lastElementChild.classList.remove('hover-arrow');
            step.lastElementChild.classList.add('outline-arrow');
          }

          if(step.previousElementSibling?.lastElementChild?.classList.contains('outline-arrow') && step.previousElementSibling?.lastElementChild?.classList.contains('solid-arrow'))
          {
            step.previousElementSibling.lastElementChild.classList.remove('solid-arrow');
          }
        });
      }
    }
  </script>
</header>
