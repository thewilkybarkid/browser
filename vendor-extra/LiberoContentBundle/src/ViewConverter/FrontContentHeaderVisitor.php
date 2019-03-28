<?php

declare(strict_types=1);

namespace Libero\LiberoContentBundle\ViewConverter;

use FluentDOM\DOM\Element;
use Libero\ViewsBundle\Views\SimplifiedVisitor;
use Libero\ViewsBundle\Views\View;
use Libero\ViewsBundle\Views\ViewConverter;
use Libero\ViewsBundle\Views\ViewConverterVisitor;
use function Libero\ViewsBundle\array_has_key;

final class FrontContentHeaderVisitor implements ViewConverterVisitor
{
    use SimplifiedVisitor;

    private $converter;

    public function __construct(ViewConverter $converter)
    {
        $this->converter = $converter;
    }

    protected function handle(Element $object, View $view) : View
    {
        $title = $object->ownerDocument->xpath()
            ->firstOf('libero:title[1]', $object);

        if (!$title instanceof Element) {
            return $view;
        }

        return $view->withArgument(
            'contentTitle',
            $this->converter
                ->convert($title, '@LiberoPatterns/heading.html.twig', $view->getContext())
                ->getArguments()
        );
    }

    protected function canHandleTemplate(?string $template) : bool
    {
        return '@LiberoPatterns/content-header.html.twig' === $template;
    }

    protected function canHandleElement(string $element) : bool
    {
        return '{http://libero.pub}front' === $element;
    }

    protected function canHandleArguments(array $arguments) : bool
    {
        return !array_has_key($arguments, 'contentTitle');
    }
}
