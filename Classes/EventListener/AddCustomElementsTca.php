<?php

namespace VV\T3elements\EventListener;

use TYPO3\CMS\Core\Configuration\Event\AfterTcaCompilationEvent;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use VV\T3elements\BaseElement;
use VV\T3elements\ElementRegistry;

/**
 * This event listener will add TCA configuration for the
 * custom elements stored in the ElementRegistry.
 */
class AddCustomElementsTca
{
    public function __invoke(AfterTcaCompilationEvent $event): void
    {
        $registry = GeneralUtility::makeInstance(ElementRegistry::class);

        foreach ($registry->getElements() as $element) {
            // Registers the elements icon
            $tca = $element->registerTtContent($event->getTca());

            // Registers the select item
            $tca = $element->registerTcaSelectItem($tca);

            // Registers the custom TCA (normally just the TCEFORM configuration)
            ArrayUtility::mergeRecursiveWithOverrule($tca['tt_content'], $element->registerCustomTtContent());

            $event->setTca($tca);
        }
    }
}
