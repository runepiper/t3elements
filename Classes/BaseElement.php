<?php

declare(strict_types=1);

namespace VV\T3elements;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

abstract class BaseElement
{
    /**
     * The icon to be used
     *
     * @var string
     */
    protected $iconIdentifier = 'content-text';

    /**
     * The palette for new content element wizard
     *
     * @var string
     */
    protected $palette = 'common';

    abstract public function registerCustomTtContent(): array;

    /**
     * Main function which is used to fully register
     * a custom element
     *
     * @return BaseElement
     */
    public function register(): BaseElement
    {
        $this->registerPageTSConfig();
        $this->registerRenderingDefinitions();

        $registry = GeneralUtility::makeInstance(ElementRegistry::class);
        $registry->add($this);

        return $this;
    }

    protected function registerPageTSConfig(): void
    {
        ExtensionManagementUtility::addPageTSConfig('
            mod.wizards.newContentElement.wizardItems.' . $this->palette . ' {
                elements {
                    ' . $this->getElementName() . ' {
                        iconIdentifier = ' . $this->iconIdentifier . '
                        title = LLL:EXT:' . $this->getExtensionName() . '/Resources/Private/Language/locallang.xlf:' . $this->getElementName() . '.title
                        description = LLL:EXT:' . $this->getExtensionName() . '/Resources/Private/Language/locallang.xlf:' . $this->getElementName() . '.description
                        tt_content_defValues {
                            CType = ' . $this->getElementName() . '
                        }
                    }
                }
                show := addToList(' . $this->getElementName() . ')
            }
        ');
    }

    protected function registerRenderingDefinitions(): void
    {
        ExtensionManagementUtility::addTypoScriptSetup('
            tt_content {
                ' . $this->getElementName() . ' < lib.contentElement
                ' . $this->getElementName() . ' {
                    templateName = ' . $this->getTemplateName() . '.html
                }
                dataProcessing {
                    23 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
                    23 {
                        references.fieldName = image
                        references.table = tt_content
                        as = images
                    }
                }
            }
        ');
    }

    public function registerTcaSelectItem(array $tca): array
    {
        $tca['tt_content']['columns']['CType']['config']['items'][] = [
            'LLL:EXT:' . $this->getExtensionName() . '/Resources/Private/Language/locallang.xlf:' . $this->getElementName() . '.title',
            $this->getElementName(),
            $this->iconIdentifier,
        ];

        return $tca;
    }

    public function registerTtContent(array $tca): array
    {
        // Add icons for custom CTypes to page view
        $tca['tt_content']['ctrl']['typeicon_classes'] += [
            $this->getElementName() => $this->iconIdentifier,
        ];

        return $tca;
    }

    public function getTemplateName(): string
    {
        if (isset($this->templateName) && $this->templateName !== '') {
            return $this->templateName;
        }

        return array_pop(explode('\\', get_class($this)));
    }

    public function getExtensionName(): string
    {
        if (isset($this->extensionName) && $this->extensionName !== '') {
            return $this->extensionName;
        }

        return strtolower(explode('\\', get_class($this))[1]);
    }

    /**
     * Helper to get the elements name based
     * on the class name
     *
     * @return string
     */
    public function getElementName(): string
    {
        if (isset($this->elementName) && $this->elementName !== '') {
            return $this->elementName;
        }

        return preg_replace_callback(
            '~([^\\\\]*\\\\)*([A-Z][a-z]*)~',
            fn($m) => ($m[1] ? '' : '_') . lcfirst($m[2]),
            get_class($this)
        );
    }
}
