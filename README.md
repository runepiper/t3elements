# t3elements

This extension provides a fluent and easy way to register custom fluid styled elements in TYPO3.

## How it works

In the following, we will create a custom element called Teaser.
First you need to define paths for the content elements. Do this with the following TypoScript setup:

```
lib.contentElement {
    extbase.controllerExtensionName = extensionname
    templateRootPaths.10 = EXT:extensionname/Resources/Private/Templates/FluidStyledContent/
    partialRootPaths.10 = EXT:extensionname/Resources/Private/Partials/FluidStyledContent/
    partialRootPaths.11 = EXT:extensionname/Resources/Private/Partials/
    layoutRootPaths.10 = EXT:extensionname/Resources/Private/Layouts/FluidStyledContent/
}
```

Now we have configured the paths where the templates, partials and layouts will be placed. Next we can create our first element. Create a PHP class like `Vendor\ExtensionName\ContentElements\Teaser` and extend the `VV\T3elements\BaseElement` class. If you are done, you have to override the abstract method `registerCustomTtContentThen()` method and return a TCA configuration used for display the edit form in TYPO3. Here is an example:

```php
public function registerCustomTtContent(): array
{
    return [
        'types' => [
            $this->getElementName() => [
                'showitem' => '
                    --palette--;;general,
                    --palette--;;hidden,
                    bodytext,
                    image
                ',
                'columnsOverrides' => [
                    'bodytext' => [
                        'config' => [
                            'enableRichtext' => true,
                        ],
                    ],
                ],
            ],
        ],
    ];
}
```

After that, you can register the element in your `ext_localconf.php` like so:

```php
(new \Vendor\ExtensionName\ContentElements\Teaser)->register();
```

And that is it. Now the Element will be shown under the commons palette by default. You can further customize your element, just take a look into the `BaseElement::class`.

## Template

Place your template in `EXT:extensionname/Resources/Private/Templates/FluidStyledContent/Teaser.html`

## Labels

You can place the label for title and description in your extensions language file located in: `EXT:extensionname/Resources/Private/Language/locallang.xlf`. Use the following keys:

- teaser.title
- teaser.description
