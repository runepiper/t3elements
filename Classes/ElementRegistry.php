<?php

declare(strict_types=1);

namespace VV\T3elements;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Holds all available meta tag managers
 */
class ElementRegistry implements SingletonInterface
{
    /**
     * @var mixed[]
     */
    private $registry = [];

    public function __construct()
    {
    }

    /**
     * Add an Element to the registry
     *
     * @param string $name
     */
    public function add(BaseElement $element)
    {
        $this->registry[] = $element;
    }

    /**
     * Get an array of all registered Elements
     *
     * @return ElementInterface[]
     */
    public function getElements(): array
    {
        return array_unique($this->registry);
    }
}
