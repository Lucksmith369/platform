<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Adapter\Twig\Extension;

use Shopware\Core\Content\Category\CategoryEntity;
use Shopware\Core\Content\Category\Service\AbstractCategoryUrlGenerator;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CategoryUrlExtension extends AbstractExtension
{
    /**
     * @var AbstractExtension
     */
    private $routingExtension;

    /**
     * @var AbstractCategoryUrlGenerator
     */
    private $categoryUrlGenerator;

    public function __construct(RoutingExtension $extension, AbstractCategoryUrlGenerator $categoryUrlGenerator)
    {
        $this->routingExtension = $extension;
        $this->categoryUrlGenerator = $categoryUrlGenerator;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('category_url', [$this, 'getCategoryUrl'], ['needs_context' => true, 'is_safe_callback' => [$this->routingExtension, 'isUrlGenerationSafe']]),
        ];
    }

    public function getCategoryUrl(array $twigContext, CategoryEntity $category): ?string
    {
        $salesChannel = null;
        if (\array_key_exists('context', $twigContext) && $twigContext['context'] instanceof SalesChannelContext) {
            $salesChannel = $twigContext['context']->getSalesChannel();
        }

        return $this->categoryUrlGenerator->generate($category, $salesChannel);
    }
}
