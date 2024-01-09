<?php

declare(strict_types=1);

namespace Netzmacht\Contao\Toolkit\Controller\FrontendModule;

use Contao\ModuleModel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

use function sprintf;

/**
 * The RenderBackendViewTrait renders the backend placeholder view for modules
 */
trait ModuleRenderBackendViewTrait
{
    /**
     * The router.
     */
    protected RouterInterface $router;

    /**
     * The translator.
     */
    protected TranslatorInterface $translator;

    /**
     * Render backend view.
     *
     * @param ModuleModel $module The module model.
     */
    protected function renderModuleBackendView(ModuleModel $module): Response
    {
        $name = $this->translator->trans(sprintf('FMD.%s.0', $this->getType()), [], 'contao_modules');
        $href = $this->router->generate(
            'contao_backend',
            ['do' => 'themes', 'table' => 'tl_module', 'act' => 'edit', 'id' => $module->id],
        );

        return $this->renderResponse(
            'be:be_wildcard',
            [
                'wildcard' => sprintf('###%s###', $name),
                'id'       => $module->id,
                'link'     => $module->name,
                'href'     => $href,
            ],
        );
    }

    /**
     * Render a response.
     *
     * The template name.
     *
     * @param string              $templateName The template name.
     * @param array<string,mixed> $data         The data being passed to the template.
     */
    abstract protected function renderResponse(string $templateName, array $data): Response;

    /**
     * Get the type of the fragment.
     */
    abstract protected function getType(): string;
}
