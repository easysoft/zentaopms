<?php
global $lang;

$feedbackManage = new stdClass();
$feedbackManage->name    = 'feedbackManage';
$feedbackManage->title   = $lang->tutorial->feedbackManage->title;
$feedbackManage->icon    = 'feedback text-danger';
$feedbackManage->type    = 'advance';
$feedbackManage->modules = 'feedback';
$feedbackManage->app     = 'feedback';
$feedbackManage->tasks   = array();

$feedbackManage->tasks['feedback'] = array();
$feedbackManage->tasks['feedback']['name']     = 'feedback';
$feedbackManage->tasks['feedback']['title']    = $lang->tutorial->feedbackManage->feedback->title;
$feedbackManage->tasks['feedback']['startUrl'] = array('feedback', 'admin');
$feedbackManage->tasks['feedback']['steps']    = array();

$feedbackManage->tasks['feedback']['steps'][] = array(
    'type'  => 'openApp',
    'app'   => 'feedback',
    'title' => $lang->tutorial->feedbackManage->feedback->step1->name,
    'desc'  => $lang->tutorial->feedbackManage->feedback->step1->desc
);

$feedbackManage->tasks['feedback']['steps'][] = array(
    'type'   => 'click',
    'target' => '#actionBar a.create-feedback-btn',
    'page'   => 'feedback-admin',
    'title'  => $lang->tutorial->feedbackManage->feedback->step2->name,
    'desc'   => $lang->tutorial->feedbackManage->feedback->step2->desc
);

$feedbackManage->tasks['feedback']['steps'][] = array(
    'type'   => 'form',
    'page'   => 'feedback-create',
    'title'  => $lang->tutorial->feedbackManage->feedback->step3->name
);

$feedbackManage->tasks['feedback']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'feedback-create',
    'title'  => $lang->tutorial->feedbackManage->feedback->step4->name,
    'desc'   => $lang->tutorial->feedbackManage->feedback->step4->desc
);

$feedbackManage->tasks['feedback']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-row="2"][data-col="actions"] a.feedback-review-btn',
    'page'   => 'feedback-admin',
    'url'    => array('feedback', 'admin', 'productID=1'),
    'title'  => $lang->tutorial->feedbackManage->feedback->step5->name,
    'desc'   => $lang->tutorial->feedbackManage->feedback->step5->desc
);

$feedbackManage->tasks['feedback']['steps'][] = array(
    'type'   => 'form',
    'target' => '#reviewFeedbackForm',
    'page'   => 'feedback-admin',
    'title'  => $lang->tutorial->feedbackManage->feedback->step6->name
);

$feedbackManage->tasks['feedback']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'feedback-admin',
    'title'  => $lang->tutorial->feedbackManage->feedback->step7->name,
    'desc'   => $lang->tutorial->feedbackManage->feedback->step7->desc
);

$feedbackManage->tasks['feedback']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-row="1"][data-col="actions"] a.feedback-toBug-btn',
    'page'   => 'feedback-admin',
    'url'    => array('feedback', 'admin', 'productID=1'),
    'title'  => $lang->tutorial->feedbackManage->feedback->step8->name,
    'desc'   => $lang->tutorial->feedbackManage->feedback->step8->desc
);

$feedbackManage->tasks['feedback']['steps'][] = array(
    'type'   => 'form',
    'target' => '#form-bug-create',
    'page'   => 'feedback-admin',
    'title'  => $lang->tutorial->feedbackManage->feedback->step9->name
);

$feedbackManage->tasks['feedback']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'feedback-admin',
    'title'  => $lang->tutorial->feedbackManage->feedback->step10->name,
    'desc'   => $lang->tutorial->feedbackManage->feedback->step10->desc
);

$feedbackManage->tasks['feedback']['steps'][] = array(
    'type'   => 'click',
    'target' => 'div.dtable div[data-row="1"][data-col="actions"] a.feedback-close-btn',
    'page'   => 'feedback-admin',
    'url'    => array('feedback', 'admin', 'productID=1'),
    'title'  => $lang->tutorial->feedbackManage->feedback->step11->name,
    'desc'   => $lang->tutorial->feedbackManage->feedback->step11->desc
);

$feedbackManage->tasks['feedback']['steps'][] = array(
    'type'   => 'form',
    'target' => '#closeFeedbackForm',
    'page'   => 'feedback-admin',
    'title'  => $lang->tutorial->feedbackManage->feedback->step12->name
);

$feedbackManage->tasks['feedback']['steps'][] = array(
    'type'   => 'saveForm',
    'page'   => 'feedback-admin',
    'title'  => $lang->tutorial->feedbackManage->feedback->step13->name
);
