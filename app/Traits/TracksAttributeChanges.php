<?php

namespace App\Traits;

use ReflectionObject;

trait TracksAttributeChanges
{
    /**
     * Values when this model was initiated. Each attribute in the initial state. Used to determine changes.
     *
     * @var array
     */
    private $original;

    private function constructWithAttributes(array $attributes = [])
    {
        // initiate original values array with initial values
        $this->markUnchanged();
        // now initiate attributes (we want to mark them changed)
        $this->setAttributes($attributes);
    }

    protected function setAttributes(array $attributes = [])
    {
        if (! empty($attributes)) {
            $article = new ReflectionObject($this);
            foreach ($attributes as $name=>$value) {
                // only set attribute if it is defined in this class
                if ($article->hasProperty($name)) {
                    $this->$name = $value;
                }
            }
        }
    }

    protected function markUnchanged()
    {
        $this->original = $this->toArray();
    }
}
