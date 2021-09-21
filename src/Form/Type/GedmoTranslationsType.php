<?php

declare(strict_types=1);

namespace A2lix\TranslationFormBundle\Form\Type;

use A2lix\TranslationFormBundle\Form\DataMapper\GedmoTranslationMapper;
use A2lix\TranslationFormBundle\Form\EventListener\GedmoTranslationsListener;
use A2lix\TranslationFormBundle\TranslationForm\GedmoTranslationForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GedmoTranslationsType extends AbstractType
{
    private $translationsListener;
    private $translationForm;
    private $locales;
    private $required;

    /**
     *
     * @param \A2lix\TranslationFormBundle\Form\EventListener\GedmoTranslationsListener $translationsListener
     * @param \A2lix\TranslationFormBundle\TranslationForm\GedmoTranslationForm $translationForm
     * @param array $locales
     * @param bool $required
     */
    public function __construct(GedmoTranslationsListener $translationsListener, GedmoTranslationForm $translationForm, $locales, $required)
    {
        $this->translationsListener = $translationsListener;
        $this->translationForm = $translationForm;
        $this->locales = $locales;
        $this->required = $required;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Simple way is enough
        if (!$options['inherit_data']) {
            $builder->setDataMapper(new GedmoTranslationMapper());
            $builder->addEventSubscriber($this->translationsListener);

        } else {
            if (!$options['translatable_class']) {
                throw new \Exception("If you want include the default locale with translations locales, you need to fill the 'translatable_class' option");
            }

            $childrenOptions = $this->translationForm->getChildrenOptions($options['translatable_class'], $options);
            $defaultLocale = (array) $this->translationForm->getGedmoTranslatableListener()->getDefaultLocale();

            $builder->add('defaultLocale', GedmoTranslationsLocalesType::class, [
                'locales' => $defaultLocale,
                'fields_options' => $childrenOptions,
                'inherit_data' => true,
            ]);

            $builder->add($builder->getName(), GedmoTranslationsLocalesType::class, [
                'locales' => array_diff($options['locales'], $defaultLocale),
                'fields_options' => $childrenOptions,
                'inherit_data' => false,
                'translation_class' => $this->translationForm->getTranslationClass($options['translatable_class'])
            ]);
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['simple_way'] = !$options['inherit_data'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $translatableListener = $this->translationForm->getGedmoTranslatableListener();

        $resolver->setDefaults(array(
            'required' => $this->required,
            'locales' => $this->locales,
            'fields' => array(),
            'translatable_class' => null,

            // inherit_data is needed only if there is no persist of default locale and default locale is required to display
            'inherit_data' => function(Options $options) use ($translatableListener) {
                return (!$translatableListener->getPersistDefaultLocaleTranslation()
                    && (in_array($translatableListener->getDefaultLocale(), $options['locales'])));
            },
        ));
    }

    public function getName()
    {
        return 'a2lix_translations_gedmo';
    }
}
