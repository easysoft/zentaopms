<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php
js::import($jsRoot . 'jquery/validation/min.js');
?>
<style>label.error {color:red}</style>
<script> 
requiredFields = "<?php echo isset($this->config->{$this->app->getModuleName()}->{$this->app->getMethodName()}->requiredFields) ? $this->config->{$this->app->getModuleName()}->{$this->app->getMethodName()}->requiredFields : '';?>";
$(document).ready(function()
{
    if(typeOf requiredFields == 'unDefined') return;
    for(i in requiredFields)
    {
        $('form #' + requiredFields[i]).addClass('required');
    }
    initValidation();
    $('form').validate();
});

function initValidation()
{
    clientLang = "<?php echo $this->app->getClientLang();?>";

    if(clientLang == 'en')
    {
        $.extend($.validator, 
        { 
            messages: 
            {
                required: "This field is required.",
                remote: "Please fix this field.",
                email: "Please enter a valid email address.",
                url: "Please enter a valid URL.",
                date: "Please enter a valid date.",
                dateISO: "Please enter a valid date (ISO).",
                number: "Please enter a valid number.",
                digits: "Please enter only digits.",
                creditcard: "Please enter a valid credit card number.",
                equalTo: "Please enter the same value again.",
                accept: "Please enter a value with a valid extension.",
                maxlength: $.validator.format("Please enter no more than {0} characters."),
                minlength: $.validator.format("Please enter at least {0} characters."),
                rangelength: $.validator.format("Please enter a value between {0} and {1} characters long."),
                range: $.validator.format("Please enter a value between {0} and {1}."),
                max: $.validator.format("Please enter a value less than or equal to {0}."),
                min: $.validator.format("Please enter a value greater than or equal to {0}.")
            }
        });
    }
    else
    {
        $.extend($.validator, 
        {
            messages: 
            {
                required: "该字段为必填字段",
                remote: "Please fix this field",
                email: "请填写合法的邮箱地址",
                url: "请填写合法的URL",
                date: "请填写合法的日期",
                dateISO: "请填写合法的日期（ISO）",
                number: "请填写合法的数字",
                digits: "只能填写数字",
                creditcard: "请填写合法信用卡号",
                equalTo: "请再输入相同的值",
                accept: "Please enter a value with a valid extension.",
                maxlength: $.validator.format("至多输入{0}个字符"),
                minlength: $.validator.format("至少输入{0}个字符"),
                rangelength: $.validator.format("值的长度必须介于{0}和{1}之间"),
                range: $.validator.format("值必须介于{0}和{1}之间"),
                max: $.validator.format("值必须小于等于{0}."),
                min: $.validator.format("值必须大于等于{0}.")
            }
        });
    }


/*
    $.extend($.validator,
    {
            methods:
            {
                required: function(value, element, param) {
                            // check if dependency is met
                            if ( !this.depend(param, element) )
                                return "dependency-mismatch";
                            switch( element.nodeName.toLowerCase() ) {
                            case 'select':
                                // could be an array for select-multiple or a string, both are fine this way
                                var val = $(element).val();
                                return val && val.length > 0;
                            case 'input':
                                if ( this.checkable(element) )
                                    return this.getLength(value, element) > 0;
                            default:
                                return $.trim(value).length > 0;
                            }
                        }
            }
    });
*/
}

</script>
