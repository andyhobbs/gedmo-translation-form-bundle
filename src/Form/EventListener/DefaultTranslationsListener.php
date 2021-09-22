<?php

declare(strict_types=1);

namespace A2lix\TranslationFormBundle\Form\EventListener;

use A2lix\TranslationFormBundle\TranslationForm\TranslationForm;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class DefaultTranslationsListener implements EventSubscriberInterface
{
    private $translationForm;

    /**
     * @param \A2lix\TranslationFormBundle\TranslationForm\TranslationForm $translationForm
     */
    public function __construct(TranslationForm $translationForm)
    {
        $this->translationForm = $translationForm;
    }

    /** {@inheritdoc} */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();

        $translatableClass = $form->getParent()->getConfig()->getDataClass();
        $translationClass = $translatableClass::getTranslationEntityClass();

        $formOptions = $form->getConfig()->getOptions();
        $childrenOptions = $this->translationForm->getChildrenOptions($translationClass, $formOptions);

        foreach ($formOptions['locales'] as $locale) {
            if (isset($childrenOptions[$locale])) {
                $form->add($locale, 'a2lix_translationsFields', [
                    'data_class' => $translationClass,
                    'fields' => $childrenOptions[$locale]
                ]);
            }
        }
    }
}
