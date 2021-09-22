<?php

declare(strict_types=1);

namespace A2lix\TranslationFormBundle\TranslationForm;

class DefaultTranslationForm extends TranslationForm
{
    /**
     * @param string $translationClass
     *
     * @return array
     */
    protected function getTranslatableFields(string $translationClass): array
    {
        $translationClass = \Doctrine\Common\Util\ClassUtils::getRealClass($translationClass);
        $manager = $this->getManagerRegistry()->getManagerForClass($translationClass);
        $metadataClass = $manager->getMetadataFactory()->getMetadataFor($translationClass);

        $fields = [];
        foreach ($metadataClass->fieldMappings as $fieldMapping) {
            if (!in_array($fieldMapping['fieldName'], ['id', 'locale'])) {
                $fields[] = $fieldMapping['fieldName'];
            }
        }

        return $fields;
    }
}
