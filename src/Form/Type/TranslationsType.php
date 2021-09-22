<?php

declare(strict_types=1);

namespace A2lix\TranslationFormBundle\Form\Type;

use A2lix\TranslationFormBundle\Form\DataMapper\IndexByTranslationMapper;
use A2lix\TranslationFormBundle\Form\EventListener\DefaultTranslationsListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationsType extends AbstractType
{
    private $translationsListener;
    private $locales;
    private $required;

    /**
     * @param \A2lix\TranslationFormBundle\Form\EventListener\DefaultTranslationsListener $translationsListener
     * @param array $locales
     * @param bool $required
     */
    public function __construct(DefaultTranslationsListener $translationsListener, $locales, $required)
    {
        $this->translationsListener = $translationsListener;
        $this->locales = $locales;
        $this->required = $required;
    }

    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setDataMapper(new IndexByTranslationMapper());
        $builder->addEventSubscriber($this->translationsListener);
    }

    /** {@inheritdoc} */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'by_reference' => false,
            'required' => $this->required,
            'locales' => $this->locales,
            'fields' => array(),
        ));
    }

    /** {@inheritdoc} */
    public function getBlockPrefix(): string
    {
        return 'a2lix_translations';
    }
}
