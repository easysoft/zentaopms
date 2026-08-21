<?php declare(strict_types=1);
/**
 * 禅道API转换器。
 * The transformer class file of ZenTao API.
 *
 * 根据路由配置中的 transform 参数，对 APIv2 请求和响应做对应的内容转换。
 * Transform APIv2 request and response content by the transform parameter in route config.
 */
class apiTransformer
{
    /**
     * 应用对象。
     * The app object.
     *
     * @var object
     * @access protected
     */
    protected $app;

    /**
     * 转换器注册表。
     * The transformer registry.
     *
     * @var array
     * @access protected
     */
    protected array $transformers = array(
        'markdown' => array(
            'request'  => 'markdownRequest',
            'response' => 'markdownResponse',
        ),
    );

    /**
     * 构造方法。
     * Construct.
     *
     * @param  object $app
     * @access public
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * 转换请求。
     * Transform request.
     *
     * @param  string $name
     * @param  array  $post
     * @access public
     * @return void
     */
    public function transformRequest(string $name, array &$post): void
    {
        if(!isset($this->transformers[$name])) return;

        $method = $this->transformers[$name]['request'];
        $this->$method($post);
    }

    /**
     * 转换响应。
     * Transform response.
     *
     * @param  string $name
     * @param  string $output
     * @access public
     * @return string
     */
    public function transformResponse(string $name, string $output): string
    {
        if(!isset($this->transformers[$name])) return $output;

        $method = $this->transformers[$name]['response'];
        return $this->$method($output);
    }

    /**
     * 将 Markdown 请求内容转换为 rawContent 和 HTML 快照。
     * Convert Markdown request content to rawContent and HTML snapshot.
     *
     * @param  array $post
     * @access protected
     * @return void
     */
    protected function markdownRequest(array &$post): void
    {
        if(empty($post['content']) && empty($post['rawContent'])) return;

        $contentType = isset($post['contentType']) ? $post['contentType'] : 'doc';
        if($contentType != 'doc' && $contentType != 'markdown') return;

        if(!empty($post['content']))
        {
            $markdown           = (string)$post['content'];
            $post['rawContent'] = json_encode(array('$migrate' => 'markdown', '$data' => $markdown), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $post['content']    = $this->markdownToHtml($markdown);
            $post['contentType'] = 'doc';
            return;
        }

        if(!empty($post['rawContent']))
        {
            $data = json_decode((string)$post['rawContent'], true);
            if(is_array($data) && isset($data['$migrate']) && $data['$migrate'] == 'markdown')
            {
                $markdown           = isset($data['$data']) && is_string($data['$data']) ? $data['$data'] : '';
                $post['content']    = $this->markdownToHtml($markdown);
                $post['contentType'] = 'doc';
            }
        }
    }

    /**
     * 将文档响应的 rawContent 转换回 Markdown。
     * Convert doc response content from rawContent to Markdown.
     *
     * @param  string $output
     * @access protected
     * @return string
     */
    protected function markdownResponse(string $output): string
    {
        if($output === '') return $output;

        $data = json_decode($output, true);
        if(!is_array($data) || !isset($data['doc']) || !is_array($data['doc'])) return $output;

        $doc = $data['doc'];
        if(($doc['contentType'] ?? '') != 'doc') return $output;

        $rawContent = isset($doc['content']) && is_string($doc['content']) ? $doc['content'] : '';
        $rawData    = json_decode($rawContent, true);
        if(!is_array($rawData) || !isset($rawData['$migrate']) || $rawData['$migrate'] != 'markdown') return $output;

        $doc['content'] = isset($rawData['$data']) && is_string($rawData['$data']) ? $rawData['$data'] : '';
        unset($doc['rawContent']);
        $data['doc'] = $doc;

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 将 Markdown 转换为 HTML。
     * Convert markdown to HTML.
     *
     * @param  string $markdown
     * @access protected
     * @return string
     */
    protected function markdownToHtml(string $markdown): string
    {
        $parsedown = $this->app->loadClass('parsedown');
        $html      = $parsedown ? $parsedown->text($markdown) : '';
        if($html === false) $html = '';

        return $html;
    }
}
