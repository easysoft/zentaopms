<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

class SendmailTester extends tester
{
    public $domain;

    /**
     * 渲染需求邮件模板, 并返回邮件内容
     * Render Story mail template
     * @param $story storyModel
     * @return string
     */
    private function renderStoryMail($story)
    {
        global $app;
        // 以下两个变量在sendmail.html.php模板中会被使用
        $this->app = $app;
        $object    = $story;

        global $config;
        $domain       = isset($config->mail->domain) && $config->mail->domain ? rtrim($config->mail->domain, '/') : 'http://localhost';
        $this->domain = $domain;
        // 使用ob_start和ob_get_contents来获取模板渲染后的内容
        ob_start();
        $templateFile = dirname(__FILE__, 4) . '/ui/sendmail.html.php';
        if(file_exists($templateFile)) include $templateFile;
        $mailContent = ob_get_contents();
        ob_end_clean();
        return $mailContent;
    }

    /**
     * 测试需求邮件模板内容是否完整
     * Test Story mail template content completeness
     * @param $story storyModel
     * @return       object
     */
    public function testTemplateContent($story)
    {
        $content      = $this->renderStoryMail($story);
        $mailTitle    = 'STORY #' . $story->id . ' ' . $story->title;
        $hasMailTitle = strpos($content, $mailTitle) !== false;
        $hasSpec      = strpos($content, $story->spec) !== false;
        $hasTable     = strpos($content, '<table') !== false;
        if($hasMailTitle && $hasSpec && $hasTable) return $this->success('需求邮件模板内容验证成功');
        return $this->failed('需求邮件模板内容不完整');
    }

    /**
     * 测试需求邮件模板链接功能
     * Test Story mail template link functionality
     * @param $story storyModel
     * @return       object
     */
    public function testLinkFunctionality($story)
    {
        $content   = $this->renderStoryMail($story);
        $expected  = helper::createLink('story', 'view', "storyID={$story->id}", 'html');
        $fullUrl   = rtrim($this->domain ?? '', '/') . $expected;
        $hasHref   = strpos($content, 'href=') !== false;
        $hasLegend = strpos($content, '<legend') !== false;
        $hasUrl    = strpos($content, $fullUrl) !== false;
        $hasSlug   = strpos($content, 'story-view') !== false;
        if($hasLegend && $hasHref && ($hasUrl || $hasSlug)) return $this->success('需求邮件链接功能验证成功');
        return $this->failed('需求邮件链接生成失败');
    }

    /**
     * 测试Mail模块使用需求邮件模板生成内容
     * Test Story mail template integration with mail model
     * @param $story storyModel
     * @return       object
     */
    public function testIntegrationMail($story)
    {
        global $uiTester;
        $mailModel = $uiTester->loadModel('mail');
        $_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'localhost';
        if(!isset($mailModel->config->mail)) $mailModel->config->mail = new stdclass();

        $mailModel->config->mail->domain = rtrim($this->domain ?? 'http://localhost', '/');
        $mailModel->config->webRoot      = '/';
        $mailModel->config->requestType  = 'PATH_INFO';

        $object = $mailModel->getObjectForMail('story', (int)$story->id);
        if(!$object)
        {
            $object        = new stdclass();
            $object->type  = 'story';
            $object->id    = (int)$story->id;
            $object->title = isset($story->title) ? $story->title : 'SendmailTest';
            $object->spec  = isset($story->spec) ? $story->spec : '这是需求的详细描述';
            $object->color = isset($story->color) ? $story->color : '#333';
        }
        else $object->type = $object->type ?: 'story';

        $action         = new stdClass();
        $action->id     = 1;
        $action->action = 'created';

        // 通过getMailContent方法获取邮件内容
        $content = $mailModel->getMailContent('story', $object, $action);
        if($content === '') return $this->failed('mail model邮件内容验证失败');

        $type     = isset($object->type) && $object->type ? $object->type : 'story';
        $hasTitle = strpos($content, strtoupper($type) . ' #' . $object->id) !== false;
        $hasSpec  = isset($object->spec) ? strpos($content, (string)$object->spec) !== false : true;
        $hasLink  = strpos($content, $type . '-view') !== false || strpos($content, 'index.php?m=' . $type . '&f=view') !== false;

        if($hasTitle && $hasSpec && $hasLink) return $this->success('mail model邮件内容验证成功');
        return $this->failed('mail model邮件内容验证失败');
    }
}
