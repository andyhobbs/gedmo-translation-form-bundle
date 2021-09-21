<?php

declare(strict_types=1);

namespace A2lix\TranslationFormBundle\TranslationForm;

interface TranslationFormInterface
{
    /**
     * @param $class
     * @param $options
     */
    public function getChildrenOptions($class, $options);

    /**
     * @param $guesser
     * @param $class
     * @param $property
     * @param $options
     */
    public function guessMissingChildOptions($guesser, $class, $property, $options);
}
