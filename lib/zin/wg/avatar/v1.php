<?php
declare(strict_types=1);
namespace zin;

class avatar extends wg
{
    protected static array $defineProps = array(
        'className?:string',
        'style?:array',
        'size?:int=32',
        'circle?:bool=true',
        'rounded?:string|int',
        'background?:string',
        'foreColor?:string',
        'text?:string',
        'code?:string',
        'maxTextLength?:int=2',
        'hueDistance?:int=43',
        'saturation?:int=0.4',
        'lightness?:int=0.6',
        'src?:string'
    );

    private $textLen        = 0;
    private $displayTextLen = 0;
    private $sizeMap        = array('xs' => 20, 'sm' => 24, 'lg' => 48, 'xl' => 80);
    private $actualSize     = 32;
    private $finalClass     = array('avatar');
    private $finalStyle;

    protected function onAddChild($child)
    {
        if(is_string($child) && !$this->props->has('text'))
        {
            $this->setProp('text', $child);
            return false;
        }

        return $child;
    }

    protected function build(): wg
    {
        /* Attach classes. */
        $this->finalClass[]           = $this->prop('className');

        /* Init style. */
        $this->finalStyle             = new stdClass();
        $this->finalStyle->background = $this->prop('background');
        $this->finalStyle->color      = $this->prop('foreColor');

        foreach($this->props->style->toJSON() as $attr => $val) $this->finalStyle->{$attr} = $val;

        /* Init avatar size. */
        $this->initSize();
        /* Init avatar shape. */
        $this->initShape();

        $content    = $this->getContent();
        $finalStyle = json_decode(json_encode($this->finalStyle), true);
        return h::div
        (
            setClass($this->finalClass),
            setStyle($finalStyle),
            set($this->getRestProps()),
            $content,
            $this->children()
        );
    }

    private function initSize()
    {
        $size       = $this->prop('size');
        $this->actualSize = $size;

        if(!$size) return;

        if(is_numeric($size))
        {
            $fontSize = intval($size/2) > 12 ? intval($size/2) : 12;
            $this->finalStyle->width         = "{$size}px";
            $this->finalStyle->height        = "{$size}px";
            $this->finalStyle->{'font-size'} = "{$fontSize}px";

            return;
        }

        $this->finalClass[] = "size-{$size}";
        $this->actualSize   = isset($this->sizeMap[$size]) ? $this->sizeMap[$size] : 20;
    }

    private function initShape()
    {
        $circle  = $this->prop('circle');
        $rounded = $this->prop('rounded');

        /* Set circle. */
        if($circle)
        {
            $this->finalClass[] = 'rounded-full';
        }
        else if($rounded)
        {
            if(is_numeric($rounded)) $this->finalStyle->{'border-radius'} = "{$rounded}px";
            else $this->finalClass[] = "rounded-{$rounded}";
        }
    }

    private function getAvatarText()
    {
        $maxTextLen    = intval($this->prop('maxTextLength'));
        $text          = strtoupper($this->prop('text', ''));
        $this->textLen = strlen($text);

        if(preg_match('/[\x{4e00}-\x{9fa5}\s]+$/u', $text))
        {
            $this->textLen        = mb_strlen($text);
            $text                 = $this->textLen <= $maxTextLen ? $text : mb_substr($text, $this->textLen - $maxTextLen);
            $this->displayTextLen = mb_strlen($text);
            return $text;
        }

        if(preg_match('/[A-Za-z\d\s]+$/', $text))
        {
            $this->displayTextLen = 1;
            return substr($text, 0, 1);
        }

        return $this->textLen <= $maxTextLen ? $text : substr($text, 0, $maxTextLen);
    }

    /**
     * Convert HSL values to RGB value.
     *
     * @param  int $h
     * @param  int $s
     * @param  int $l
     * @access private
     * @return array
     */
    private function hslToRgb($h, $s, $l)
    {
        $h = ($h % 360) / 360;
        $s = ($s > 0 ? $s : 0);
        $s = ($s > 255) ? 255 : $s;
        $l = ($l > 0 ? $l : 0);
        $l = ($l > 255) ? 255 : $l;

        $m2 = ($l <= 0.5) ? ($l * ($s + 1)) : ($l + $s - $l * $s);
        $m1 = $l * 2 - $m2;

        $hueFn = function($val, $m1, $m2)
        {
            $val = $val < 0 ? $val + 1 : ($val > 1 ? $val - 1 : $val);

            if($val * 6 < 1) return $m1 + ($m2 - $m1) * $val * 6;
            elseif($val * 2 < 1) return $m2;
            elseif($val * 3 < 2) return $m1 + ($m2 - $m1) * (2/3 - $val) * 6;

            return $m1;
        };

        return array(
            'r' => $hueFn($h + 1/3, $m1, $m2) * 255,
            'g' => $hueFn($h, $m1, $m2) * 255,
            'b' => $hueFn($h - 1/3, $m1, $m2) * 255
        );
    }

    private function hex2Rgb($hex)
    {
        if(!str_starts_with($hex, '#') || !preg_match('/#[0-9A-F]{3,6}$/', $hex)) throw new \Exception('incorrect data format');

        $r = 0;
        $g = 0;
        $b = 0;
        if(strlen($hex) == 4) list($r, $g, $b) = sscanf($hex, "#%01x%01x%01x");
        elseif(strlen($hex) == 7) list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
        else throw new \Exception('incorrect RGB value');

        return array(
            'r' => $r,
            'g' => $g,
            'b' => $b
        );
    }

    /*
     * Get contrast color.
     *
     * @param  array|string $rgb
     * @param  string       $theme  dark|light
     * @access private
     * @return string
     */
    private function contrastColor($rgb, $themeDark = null, $themeLight = null)
    {
        $rgb = is_array($rgb) ? $rgb : $this->hex2Rgb($rgb);

        $r = $rgb['r'];
        $g = $rgb['g'];
        $b = $rgb['b'];
        if(($r * 0.299 + $g * 0.587 + $b * 0.114) > 186)
        {
            /* Is light color. */
            return $themeDark ? $themeDark : '#333333';
        }

        return $themeLight ? $themeLight : '#ffffff';
    }

    private function getTextStyle()
    {
        $hueDistance = intval($this->prop('hueDistance'));
        $saturation  = $this->prop('saturation');
        $lightness   = $this->prop('lightness');
        $background  = $this->prop('background');
        $foreColor   = $this->prop('foreColor');
        $code        = $this->prop('code');
        $avatarCode  = $code ? $code : $this->prop('text');

        if(!$background)
        {
            $val = 0;
            if(is_numeric($avatarCode))
            {
                $val = intval($avatarCode);
            }
            else
            {
                for($i = 0; $i < strlen($avatarCode); $i++) $val += ord($avatarCode[$i]);
            }

            $hue         = $val * $hueDistance % 360;
            $actualSat   = $saturation * 100;
            $actualLight = $lightness * 100;
            $this->finalStyle->background = "hsl({$hue}, {$actualSat}%, {$actualLight}%)";

            if(!$foreColor)
            {
                $rgb = $this->hslToRgb($hue, $saturation, $lightness);
                $this->finalStyle->color = $this->contrastColor($rgb);
            }
        }
        elseif (!$foreColor && $background)
        {
            $this->finalStyle->color = $this->contrastColor($background);
        }

        $textStyle = array();
        if($this->actualSize and $this->actualSize < (14 * $this->displayTextLen))
        {
            $textStyle = array(
                'transform' => 'scale(' . $this->actualSize / (14 * $this->displayTextLen) . ')',
                'white-space' => 'nowrap'
            );
        }

        return $textStyle;
    }

    private function getContent()
    {
        $src  = $this->prop('src');
        $text = $this->prop('text');
        $code = $this->prop('code');

        /* With avatar. */
        if($src)
        {
            $this->finalClass[] = 'has-img';

            return h::img
            (
                setClass('avatar-img'),
                set('src', $src),
                set('alt', $text),
                set('data-code', $code),
            );
        }

        /* Without text and image. */
        if(!$text) return null;

        $displayText = $this->getAvatarText();

        $this->finalClass[] = 'has-text';
        $this->finalClass[] = 'has-text-' . $this->textLen;

        $textStyle = $this->getTextStyle();
        return h::div
        (
            setClass('avatar-text'),
            set('data-actualSize', $this->actualSize),
            $textStyle ? setStyle($textStyle) : null,
            $displayText
        );
    }
}
