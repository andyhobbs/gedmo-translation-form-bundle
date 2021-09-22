<?php

declare(strict_types=1);

namespace A2lix\TranslationFormBundle\Form\Type;

use A2lix\TranslationFormBundle\Form\DataMapper\IndexByTranslationMapper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationsFormsType extends AbstractType
{
    private $locales;
    private $required;

    /**
     * @param array $locales
     * @param bool $required
     */
    public function __construct($locales, $required)
    {
        $this->locales = $locales;
        $this->required = $required;
    }

    /** {@inheritdoc} */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setDataMapper(new IndexByTranslationMapper());

        $formOptions = $options['form_options'] ?? [];
        foreach ($options['locales'] as $locale) {
            $builder->add($locale, $options['form_type'], $formOptions);
        }
    }

    /** {@inheritdoc} */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'by_reference' => false,
            'required' => $this->required,
            'locales' => $this->locales,
            'form_type' => null,
            'form_options' => null,
        ]);
    }

    /** {@inheritdoc} */
    public function getBlockPrefix(): string
    {
        return 'a2lix_translationsForms';
    }
}
