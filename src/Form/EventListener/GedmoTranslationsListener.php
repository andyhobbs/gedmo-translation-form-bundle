<?php

declare(strict_types=1);

namespace A2lix\TranslationFormBundle\Form\EventListener;

use A2lix\TranslationFormBundle\TranslationForm\TranslationForm;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 *
 * @author David ALLIX
 */
class GedmoTranslationsListener implements EventSubscriberInterface
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
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
        );
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();

        $translatableClass = $form->getParent()->getConfig()->getDataClass();

        $formOptions = $form->getConfig()->getOptions();
        $childrenOptions = $this->translationForm->getChildrenOptions($translatableClass, $formOptions);

        foreach ($formOptions['locales'] as $locale) {
            if (isset($childrenOptions[$locale])) {
                $form->add($locale, 'a2lix_translationsFields', [
                    'fields' => $childrenOptions[$locale],
                    'translation_class' => $this->translationForm->getTranslationClass($translatableClass),
                ]);
            }
        }
    }
}
