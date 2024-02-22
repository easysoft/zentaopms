<?php
$config->chart->widthInput = 128;
$config->chart->widthDate  = 248;

$config->chart->labelMaxLength  = 8;
$config->chart->canLabelRotate  = array('line', 'cluBarX', 'cluBarY', 'stackedBar', 'stackedBarY');
$config->chart->chartMaxChecked = 6;

$config->chart->dateConvert = array();
$config->chart->dateConvert['year']  = 'YEAR';
$config->chart->dateConvert['month'] = 'MONTH';
$config->chart->dateConvert['week']  = 'YEARWEEK';
$config->chart->dateConvert['day']   = 'DATE';

$config->chart->dataZoom = new stdclass();

$config->chart->dataZoom->common = new stdclass();
$config->chart->dataZoom->common->inside = new stdclass();
$config->chart->dataZoom->common->inside->type             = 'inside';
$config->chart->dataZoom->common->inside->startValue       = 0;
$config->chart->dataZoom->common->inside->endValue         = 5;
$config->chart->dataZoom->common->inside->minValueSpan     = 10;
$config->chart->dataZoom->common->inside->maxValueSpan     = 10;
$config->chart->dataZoom->common->inside->zoomOnMouseWheel = false;
$config->chart->dataZoom->common->inside->moveOnMouseWheel = true;
$config->chart->dataZoom->common->inside->moveOnMouseMove  = true;

$config->chart->dataZoom->common->slider = new stdclass();
$config->chart->dataZoom->common->slider->type            = 'slider';
$config->chart->dataZoom->common->slider->realtime        = true;
$config->chart->dataZoom->common->slider->startValue      = 0;
$config->chart->dataZoom->common->slider->endValue        = 5;
$config->chart->dataZoom->common->slider->zoomLock        = true;
$config->chart->dataZoom->common->slider->brushSelect     = false;
$config->chart->dataZoom->common->slider->width           = '80%';
$config->chart->dataZoom->common->slider->height          = 5;
$config->chart->dataZoom->common->slider->fillerColor     = '#ccc';
$config->chart->dataZoom->common->slider->borderColor     = '#33aaff00';
$config->chart->dataZoom->common->slider->backgroundColor = '#cfcfcf00';
$config->chart->dataZoom->common->slider->handleSize      = 0;
$config->chart->dataZoom->common->slider->showDataShadow  = false;
$config->chart->dataZoom->common->slider->showDetail      = false;
$config->chart->dataZoom->common->slider->bottom          = 0;
$config->chart->dataZoom->common->slider->left            = '10%';
