<?php

namespace Spatie\HtmlElement;

use Spatie\HtmlElement\Helpers\Arr;

class AbbreviationParser
{
    /** @var string */
    protected $element = 'div';

    /** @var array */
    protected $classes = [];

    /** @var array */
    protected $attributes = [];

    /**
     * @param string $tag
     * @return array
     */
    public static function parse($tag)
    {
        $parsed = new static($tag);

        return [
            'element' => $parsed->element,
            'classes' => $parsed->classes,
            'attributes' => $parsed->attributes,
        ];
    }

    /**
     * AbbreviationParser constructor.
     * @param string $tag
     */
    protected function __construct($tag)
    {
        $this->parseTag($tag);
    }

    /**
     * @param string $tag
     */
    protected function parseTag($tag)
    {
        foreach ($this->explodeTag($tag) as $part) {

            switch ($part[0]) {
                case '.':
                    $this->parseClass($part);
                    break;
                case '#':
                    $this->parseId($part);
                    break;
                case '[':
                    $this->parseAttribute($part);
                    break;
                default:
                    $this->parseElement($part);
                    break;
            }
        }
    }

    /**
     * @param string $class
     */
    protected function parseClass($class)
    {
        $this->classes[] = ltrim($class, '.');
    }

    /**
     * @param string $id
     */
    protected function parseId($id)
    {
        $this->attributes['id'] = ltrim($id, '#');
    }

    /**
     * @param string $attribute
     */
    protected function parseAttribute($attribute)
    {
        $keyValueSet = explode('=', trim($attribute, '[]'), 2);

        $key = $keyValueSet[0];
        $value = isset($keyValueSet[1]) ? $keyValueSet[1] : null;

        $this->attributes[$key] = trim($value, '\'"');
    }

    /**
     * @param string $element
     */
    protected function parseElement($element)
    {
        $this->element = $element;
    }

    /**
     * @param string $tag
     * @return array
     */
    protected function explodeTag($tag)
    {
        // First split out the attributes set with `[...=...]`
        $parts = preg_split('/(?=( \[[^]]+] ))/x', $tag);

        // Afterwards we can extract the rest of the attributes
        return Arr::flatMap($parts, function ($part) {

            if (strpos($part, '[') === 0) {
                list($attributeValue, $rest) = explode(']', $part, 2);

                return [$attributeValue] + $this->explodeTag($rest);
            }

            return preg_split('/(?=( (\.) | (\#) ))/x', $part);
        });
    }
}
