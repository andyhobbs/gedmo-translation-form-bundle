<?php

declare(strict_types=1);

namespace A2lix\TranslationFormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationsLocalesSelectorType extends AbstractType
{
    private $locales;

    /**
     * @param array $locales
     */
    public function __construct($locales)
    {
        $this->locales = $locales;
    }

    /** {@inheritdoc} */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => \array_combine($this->locales, $this->locales),
            'expanded' => true,
            'multiple' => true,
            'attr' => [
                'class' => "a2lix_translationsLocalesSelector"
            ]
        ]);
    }

    /** {@inheritdoc} */
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    /** {@inheritdoc} */
    public function getBlockPrefix(): string
    {
        return 'a2lix_translationsLocalesSelector';
    }

}
