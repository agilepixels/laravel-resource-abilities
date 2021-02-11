# Add abilities to Laravel API resources

[![Latest Version on Packagist](https://img.shields.io/packagist/v/agilepixels/laravel-resource-abilities.svg?style=flat)](https://packagist.org/packages/agilepixels/laravel-resource-abilities)
![Test](https://github.com/agilepixels/laravel-resource-abilities/workflows/Test/badge.svg)
![Check & fix styling](https://github.com/agilepixels/laravel-resource-abilities/workflows/Check%20&%20fix%20styling/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/agilepixels/laravel-resource-abilities.svg?style=flat)](https://packagist.org/packages/agilepixels/laravel-resource-abilities)

If you build a web application with a separate frontend and backend, all kinds of information has to be transferred between
these two parts. Part of this are your routes, but how do you share the authorized actions in the most convenient way?
One quick note: from now on, we'll call these authorized actions "abilities". To share abilities, we use API resources. That 
way we can see the abilities the current user has for that resource.

Please read the full introduction and all documentation about this package in our [GitHub Wiki](https://github.com/agilepixels/laravel-resource-abilities/wiki).

## Support us

Your support is most welcome! Feel free to send in any pull requests to improve this package. If you wish to contribute 
in any other way, do check out the "sponsor this package" to the right. We'd love to receive your support!

## Testing

``` bash
composer test
```

## Credits

- [Lex de Willigen](https://github.com/lexdewilligen)
- [All contributors](https://github.com/agilepixels/laravel-resource-abilities/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/agilepixels/laravel-resource-abilities/blob/master/LICENSE.md) for more information.