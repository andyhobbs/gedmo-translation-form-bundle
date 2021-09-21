<?php

declare(strict_types=1);

namespace A2lix\TranslationFormBundle\TranslationForm;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Form\FormRegistry;

abstract class TranslationForm implements TranslationFormInterface
{
    private $typeGuesser;
    private $managerRegistry;

    /**
     * @param \Symfony\Component\Form\FormRegistry     $formRegistry
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $managerRegistry
     */
    public function __construct(FormRegistry $formRegistry, Registry $managerRegistry)
    {
        $this->typeGuesser = $formRegistry->getTypeGuesser();
        $this->managerRegistry = $managerRegistry;
    }

    /**
     *
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    public function getManagerRegistry(): \Doctrine\Bundle\DoctrineBundle\Registry
    {
        return $this->managerRegistry;
    }

    /**
     * {@inheritdoc}
     */
    public function getChildrenOptions($class, $options): array
    {
        $childrenOptions = array();

        // Clean some options
        unset($options['inherit_data']);
        unset($options['translatable_class']);

        // Custom options by field
        foreach (array_unique(array_merge(array_keys($options['fields']), $this->getTranslatableFields($class))) as $child) {
            $childOptions = ($options['fields'][$child] ?? []) + array('required' => $options['required']);

            if (!isset($childOptions['display']) || $childOptions['display']) {
                $childOptions = $this->guessMissingChildOptions($this->typeGuesser, $class, $child, $childOptions);

                // Custom options by locale
                if (isset($childOptions['locale_options'])) {
                    $localesChildOptions = $childOptions['locale_options'];
                    unset($childOptions['locale_options']);

                    foreach ($options['locales'] as $locale) {
                        $localeChildOptions = $localesChildOptions[$locale] ?? [];
                        if (!isset($localeChildOptions['display']) || $localeChildOptions['display']) {
                            $childrenOptions[$locale][$child] = $localeChildOptions + $childOptions;
                        }
                    }

                // General options for all locales
                } else {
                    foreach ($options['locales'] as $locale) {
                        $childrenOptions[$locale][$child] = $childOptions;
                    }
                }
            }
        }

        return $childrenOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function guessMissingChildOptions($guesser, $class, $property, $options)
    {
        if (!isset($options['field_type']) && ($typeGuess = $guesser->guessType($class, $property))) {
            $options['field_type'] = $typeGuess->getType();
        }

        if (!isset($options['attr']['pattern']) && ($patternGuess = $guesser->guessPattern($class, $property))) {
            $options['attr']['pattern'] = $patternGuess->getValue();
        }

        if (!isset($options['attr']['max_length']) && ($maxLengthGuess = $guesser->guessMaxLength($class, $property))) {
            $options['attr']['max_length'] = $maxLengthGuess->getValue();
        }

        return $options;
    }
}
