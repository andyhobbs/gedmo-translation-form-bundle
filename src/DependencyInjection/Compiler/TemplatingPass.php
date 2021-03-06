<?php

declare(strict_types=1);

namespace A2lix\TranslationFormBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TemplatingPass implements CompilerPassInterface
{
    /** {@inheritdoc} */
    public function process(ContainerBuilder $container): void
    {
        if (false !== ($template = $container->getParameter('a2lix_translation_form.templating'))) {
            $resources = $container->getParameter('twig.form.resources');

            if (\in_array($template, $resources, true)) {
                return;
            }

            $resources[] = $template;
            $container->setParameter('twig.form.resources', $resources);
        }
    }
}
