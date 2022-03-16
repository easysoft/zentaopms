<?php
include dirname(dirname(__FILE__)) . '/resource.php';

if($this->config->edition != 'open')
{
    $lang->resource->company->todo      = 'companyTodo';
    $lang->resource->company->calendar  = 'effortCalendar';
    $lang->resource->company->allTodo   = 'allTodo';
    $lang->resource->company->effort    = 'companyEffort';
    $lang->resource->company->alleffort = 'allEffort';
    $lang->resource->sms                = new stdclass();
    $lang->resource->sms->index         = 'index';
    $lang->resource->sms->test          = 'test';
    $lang->resource->sms->reset         = 'reset';
}