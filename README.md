# Todo Reminder

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]


This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what
PSRs you support to avoid any confusion with users and contributors.

## Install

Via Composer in project

``` bash
composer require integer-net/todo-reminder
```

Via Composer globally

``` bash
composer global require integer-net/todo-reminder
```

## Usage

To check the last commit of the current repository, run
``` bash
vendor/bin/todo
```

If you installed Todo Reminder globally,

``` bash
todo --root /path/to/repository
```

To run the checks automatically after each commit, create a hook as `.git/hooks/post-commit` with content like:

``` bash
#!/bin/sh
exec vendor/bin/todo
```

Make the hook executable, so that Git can run it:

```
chmod +x .git/hooks/post-commit
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
composer test
```

Runs unit tests, mutation tests and static analysis

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email fs@integer-net.de instead of using the issue tracker.

## Credits

- [Fabian Schmengler][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.txt) for more information.

[ico-version]: https://img.shields.io/packagist/v/integer-net/todo-reminder.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/integer-net/todo-reminder/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/integer-net/todo-reminder.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/integer-net/todo-reminder.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/integer-net/todo-reminder.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/integer-net/todo-reminder
[link-travis]: https://travis-ci.org/integer-net/todo-reminder
[link-scrutinizer]: https://scrutinizer-ci.com/g/integer-net/todo-reminder/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/integer-net/todo-reminder
[link-downloads]: https://packagist.org/packages/integer-net/todo-reminder
[link-author]: https://github.com/schmengler
[link-contributors]: ../../contributors