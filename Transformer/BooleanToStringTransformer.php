<?php

namespace Galaxy\GameBundle\Transformer;

use Symfony\Component\Form\Extension\Core\DataTransformer\BooleanToStringTransformer as BTSTrans;

class BooleanToStringTransformer extends BTSTrans
{
    /**
     * Transforms a string into a Boolean.
     *
     * @param string $value String value.
     *
     * @return Boolean Boolean value.
     *
     * @throws TransformationFailedException If the given value is not a string.
     */
    public function reverseTransform($value)
    {
        if (null === $value) {
            return false;
        }
        
        if ('' === $value) {
            return false;
        }
        
        if ('0' === $value) {
            return false;
        }

        if (!is_string($value)) {
            throw new TransformationFailedException('Expected a string.');
        }

        return true;
    }
}