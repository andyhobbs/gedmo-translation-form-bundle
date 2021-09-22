<?php

declare(strict_types=1);

namespace A2lix\TranslationFormBundle\Form\DataMapper;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class IndexByTranslationMapper implements DataMapperInterface
{
    /** {@inheritdoc} */
    public function mapDataToForms($data, $forms)
    {
        if (null === $data || [] === $data) {
            return;
        }

        if (!is_array($data) && !is_object($data)) {
            throw new UnexpectedTypeException($data, 'object, array or empty');
        }

        foreach ($forms as $form) {
            $form->setData($data->get($form->getConfig()->getName()));
        }
    }

    /** {@inheritdoc} */
    public function mapFormsToData($forms, &$data)
    {
        if (null === $data) {
            return;
        }

        if (!is_array($data) && !is_object($data)) {
            throw new UnexpectedTypeException($data, 'object, array or empty');
        }

        $data = $data ?: new ArrayCollection();

        foreach ($forms as $form) {
            if (is_object($translation = $form->getData()) && !$translation->getId()) {
                $locale = $form->getConfig()->getName();
                $translation->setLocale($locale);

                $data->set($locale, $translation);
            }
        }
    }
}
