<?php

declare(strict_types=1);

namespace A2lix\TranslationFormBundle\TranslationForm;

use Gedmo\Translatable\TranslatableListener;

/**
 * @author David ALLIX
 */
class GedmoTranslationForm extends TranslationForm
{
    private $gedmoTranslatableListener;
    private $gedmoConfig;

    /**
     * @return \Gedmo\Translatable\TranslatableListener
     */
    public function getGedmoTranslatableListener(): \Gedmo\Translatable\TranslatableListener
    {
        return $this->gedmoTranslatableListener;
    }

    /**
     * @param \Gedmo\Translatable\TranslatableListener $gedmoTranslatableListener
     */
    public function setGedmoTranslatableListener(TranslatableListener $gedmoTranslatableListener)
    {
        $this->gedmoTranslatableListener = $gedmoTranslatableListener;
    }

    /**
     * @param string $translatableClass
     *
     * @return array
     */
    private function getGedmoConfig($translatableClass): array
    {
        if (isset($this->gedmoConfig[$translatableClass])) {
            return $this->gedmoConfig[$translatableClass];
        }

        $translatableClass = \Doctrine\Common\Util\ClassUtils::getRealClass($translatableClass);
        $manager = $this->getManagerRegistry()->getManagerForClass($translatableClass);
        $this->gedmoConfig[$translatableClass] = $this->gedmoTranslatableListener->getConfiguration($manager, $translatableClass);

        return $this->gedmoConfig[$translatableClass];
    }

    /**
     * @param $translatableClass
     *
     * @return array
     */
    public function getTranslationClass($translatableClass): array
    {
        $gedmoConfig = $this->getGedmoConfig($translatableClass);

        return $gedmoConfig['translationClass'];
    }

    /**
     * @param string $translatableClass
     *
     * @return array
     */
    protected function getTranslatableFields($translatableClass)
    {
        $gedmoConfig = $this->getGedmoConfig($translatableClass);

        return $gedmoConfig['fields'] ?? [];
    }
}
