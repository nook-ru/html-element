<?php

namespace Spatie\HtmlElement;

class Attributes
{
    /** @var array */
    protected $attributes = [];

    /** @var array */
    protected $classes = [];

    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        foreach ($attributes as $attribute => $value) {

            if ($attribute === 'class') {
                $this->addClass($value);
                continue;
            }

            if (is_int($attribute)) {
                $attribute = $value;
                $value = '';
            }

            $this->setAttribute($attribute, $value);
        }

        return $this;
    }

    /**
     * @param string $attribute
     * @param string $value
     *
     * @return $this
     */
    public function setAttribute($attribute, $value = '')
    {
        if ($attribute === 'class') {
            $this->addClass($value);

            return $this;
        }

        $this->attributes[$attribute] = $value;

        return $this;
    }

    /**
     * @param string|array $class
     *
     * @return $this
     */
    public function addClass($class)
    {
        if (!is_array($class)) {
            $class = [$class];
        }

        $this->classes = array_unique(
            array_merge($this->classes, $class)
        );

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->attributes) && empty($this->classes);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if (empty($this->classes)) {
            return $this->attributes;
        }

        return array_merge($this->attributes, ['class' => implode(' ', $this->classes)]);
    }

    /**
     * @return string
     */
    public function toString()
    {
        if ($this->isEmpty()) {
            return '';
        }

        $attributeStrings = [];

        foreach ($this->toArray() as $attribute => $value) {
            if (is_null($value) || $value === '') {
                $attributeStrings[] = $attribute;
                continue;
            }

            $attributeStrings[] = "{$attribute}=\"{$value}\"";
        }

        return implode(' ', $attributeStrings);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
