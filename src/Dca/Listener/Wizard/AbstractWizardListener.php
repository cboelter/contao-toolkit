<?php

declare(strict_types=1);

namespace Netzmacht\Contao\Toolkit\Dca\Listener\Wizard;

use Contao\DataContainer;
use Netzmacht\Contao\Toolkit\Dca\DcaManager;
use Netzmacht\Contao\Toolkit\Dca\Definition;
use Netzmacht\Contao\Toolkit\View\Template\TemplateRenderer;
use Symfony\Contracts\Translation\TranslatorInterface as Translator;

/**
 * AbstractWizard is the base class for a wizard.
 */
abstract class AbstractWizardListener
{
    /**
     * Template name.
     *
     * @var string
     */
    protected $template = '';

    /**
     * Translator.
     *
     * @var Translator
     */
    protected $translator;

    /**
     * Template factory.
     *
     * @var TemplateRenderer
     */
    private $templateEngine;

    /**
     * Data container manager.
     *
     * @var DcaManager
     */
    protected $dcaManager;

    /**
     * @param TemplateRenderer $templateEngine Template Engine.
     * @param Translator       $translator     Translator.
     * @param DcaManager       $dcaManager     Data container manager.
     * @param string           $template       Template name.
     */
    public function __construct(
        TemplateRenderer $templateEngine,
        Translator $translator,
        DcaManager $dcaManager,
        string $template = ''
    ) {
        $this->translator     = $translator;
        $this->templateEngine = $templateEngine;
        $this->dcaManager     = $dcaManager;

        if (! $template) {
            return;
        }

        $this->template = $template;
    }

    /**
     * Render a template.
     *
     * @param string|null         $name       Custom template name. If null main wizard template is used.
     * @param array<string,mixed> $parameters Parameters.
     */
    protected function render($name = null, array $parameters = []): string
    {
        return $this->templateEngine->render($name ?: $this->template, $parameters);
    }

    /**
     * Get the data container definition.
     *
     * @param DataContainer $dataContainer Data container driver.
     */
    protected function getDefinition($dataContainer): Definition
    {
        return $this->dcaManager->getDefinition($dataContainer->table);
    }

    /**
     * Invoke by the callback.
     *
     * @param DataContainer $dataContainer Data container driver.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function onWizardCallback($dataContainer): string
    {
        return '';
    }
}
