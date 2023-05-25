<style type="text/css">
  .left{float:left !important;}
  .center{text-align:center;}
  .clear:after{content:'';display:block;clear:both;}
  .center-block{position:relative;}
  .definition-factory{position:absolute;top:0;right:0;width:43%;display:none;padding-top:46px;}
  .definition-factory .formula-box{height:auto;line-height:30px;border-bottom:solid 1px grey;}
  .definition-factory .formula-box span{padding-right:5px;}
  .definition-factory .material-box{padding:5px 0 0 27px;}
  .definition-factory .material-box .material{cursor:pointer;text-align: center;background-color: #e5e5e5;margin: 15px;float: left;width: 30px;height:30px;line-height:30px;}
  .definition-factory .basicmeas-box{margin-top:5px;border:1px solid #dcdcdc;height:34px;}
  .definition-factory .basicmeas-box .title{background-color:#eee;color:#3c4353;width:20%;border-right:none;height:32px;line-height:32px;}
  .definition-factory .basicmeas-box .selectbox{width:80% !important;}
</style>

<div class='definition-factory' id='definition-factory'>
  <div class='formula-box' id='formula-box'></div>
  <div class='material-box clear' id='material-box'>
    <div class="material" data-method="<?php echo $lang->measurement->plus;?>"><?php echo $lang->measurement->plus;?></div>
    <div class="material" data-method="<?php echo $lang->measurement->minus;?>"><?php echo $lang->measurement->minus;?></div>
    <div class="material" data-method="<?php echo $lang->measurement->multiplicationAlias;?>"><?php echo $lang->measurement->multiplication;?></div>
    <div class="material" data-method="<?php echo $lang->measurement->divisionAlias;?>"><?php echo $lang->measurement->division;?></div>
    <div class="material" data-method="<?php echo $lang->measurement->remainder;?>"><?php echo $lang->measurement->remainder;?></div>
    <div class="material" data-method="<?php echo $lang->measurement->leftParentheses;?>"><?php echo $lang->measurement->leftParentheses;?></div>
    <div class="material" data-method="<?php echo $lang->measurement->rightParentheses;?>"><?php echo $lang->measurement->rightParentheses;?></div>
    <div class="material" data-method="<?php echo $lang->measurement->equal;?>"><?php echo $lang->measurement->equal;?></div>
    <div class="material" data-method="<?php echo $lang->measurement->dot;?>"><?php echo $lang->measurement->dot;?></div>
    <div class="material" data-method="<?php echo $lang->measurement->del;?>"><?php echo $lang->measurement->del;?></div>
    <div class="material" data-method="<?php echo $lang->measurement->zero;?>"><?php echo $lang->measurement->zero;?></div>
    <div class="material" data-method="<?php echo $lang->measurement->one;?>"><?php echo $lang->measurement->one;?></div>
    <div class="material" data-method="<?php echo $lang->measurement->two;?>"><?php echo $lang->measurement->two;?></div>
    <div class="material" data-method="<?php echo $lang->measurement->three;?>"><?php echo $lang->measurement->three;?></div>
    <div class="material" data-method="<?php echo $lang->measurement->four;?>"><?php echo $lang->measurement->four;?></div>
    <div class="material" data-method="<?php echo $lang->measurement->five;?>"><?php echo $lang->measurement->five;?></div>
    <div class="material" data-method="<?php echo $lang->measurement->six;?>"><?php echo $lang->measurement->six;?></div>
    <div class="material" data-method="<?php echo $lang->measurement->seven;?>"><?php echo $lang->measurement->seven;?></div>
    <div class="material" data-method="<?php echo $lang->measurement->eight;?>"><?php echo $lang->measurement->eight;?></div>
    <div class="material" data-method="<?php echo $lang->measurement->nine;?>"><?php echo $lang->measurement->nine;?></div>
  </div>
  <div class='basicmeas-box clear'>
  <label class='left center title'><?php echo $lang->measurement->basicMeas;?></label>
    <div class='left selectbox' id='basicMeasBox'>
    <?php echo html::select('basicmeas', $basicMeas, '', 'class="form-control chosen"');?>
    </div>
  </div>
</div>

<script>
var definitionItem = "<?php if(isset($definitonItem)) echo $definitonItem;?>";
$(function()
{
  definition.setDefautl(definitionItem);

  $("form input").click(function()
  {
    if($(this).attr('name') == 'definition')
        $('#definition-factory').show();
    else
        $('#definition-factory').hide();
  })

  $('#material-box .material').click(function()
  {
    var method = $(this).attr('data-method');
    if(method == 'C') return definition.del();

    definition.setFormula(method,'method');
  })  

  $('#basicmeas').change(function()
  {
    var measurementID = $(this).val();
    definition.setFormula(measurementID,'basicmeas');
  })
})

var definition = {
    del:function()
    {
      $('#formula-box span').eq(-1).remove();
      definition.getFormula();
      return true;
    },
    setFormula:function(method,type)
    {
      var formulaObj = $('#formula-box');
      if(type == 'method')
      {
        var methodName = method;
        if(methodName == '*') methodName = 'x';
        if(methodName == '/') methodName = '÷';

        var formulaElement = '<span data-method="'+method+'">'+methodName+'</span>';
      }

      if(type == 'basicmeas')
      {
        var measurement    = $('#basicMeasBox .chosen-single-with-deselect .chosen-search input').attr('placeholder');
        var formulaElement = '<span data-method="{'+method+'}">'+measurement+'</span>';
      }

      formulaObj.append(formulaElement);
      definition.getFormula();
    },
    getFormula:function()
    {
      var methodObj = $('#formula-box span');
      var methods = [];
      
      methodObj.each(function()
      {
        methods.push($(this).attr('data-method'));
      })

      if(methodObj.length==0) methods = [];

      var methodJson = JSON.stringify(methods);
      $('#definitionMethods').val(methodJson);
    },
    setDefautl:function(definitionItem)
    {
      if(definitionItem != '') $('#formula-box').html(definitionItem);
    }
}
</script>
