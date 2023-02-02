<?php
namespace zin\wg;

use \zin\core\h5;

require_once dirname(dirname(__DIR__)) . DS . 'core' . DS . 'wg.class.php';

class pageheading extends \zin\core\wg
{
    static $tag = 'div';

    static $defaultProps = array('id' => 'heading');

    static $customProps = 'text,icon,url';

    /**
     * Accept child node.
     *
     * @param  object|string $child
     * @param  bool          $strAsHtml
     * @access protected
     * @return object|string
     */
    protected function acceptChild($child, $strAsHtml = false)
    {
        /* Invoke parent method; */
        $child = parent::acceptChild($child, $strAsHtml);

        if(!$strAsHtml && is_string($child) && !$this->props->has('text'))
        {
            $this->prop('text', $child);
            return NULL;
        }

        return $child;
    }

    /**
     * Build builder.
     *
     * @param  bool      $isPrint
     * @param  object    $parent
     * @access protected
     * @return object
     */
    protected function build($isPrint = false, $parent = NULL)
    {
        $icon = $this->prop('icon');
        $text = $this->prop('text');
        $url  = $this->prop('url');

        $builder = parent::build($isPrint, $parent);

        if(!empty($icon)) $builder->append(new icon($icon));

        if(empty($text)) return $builder;

        if(!empty($url)) $builder->append(\zin\btn($text, \zin\set('url', $url))->addClass('primary'));
        else $builder->append(h5::span($text)->addClass('text'));

        return $builder;
    }
}
