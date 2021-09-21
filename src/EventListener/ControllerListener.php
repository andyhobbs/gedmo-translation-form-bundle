<?php

declare(strict_types=1);

namespace A2lix\TranslationFormBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Util\ClassUtils;
use Gedmo\Translatable\TranslatableListener;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class ControllerListener
{
    protected $annotationReader;
    protected $translatableListener;

    public function __construct(Reader $annotationReader, TranslatableListener $translatableListener)
    {
        $this->annotationReader = $annotationReader;
        $this->translatableListener = $translatableListener;
    }

    public function onKernelController(ControllerEvent $event)
    {

    }
}
