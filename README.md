# languages

This repository contains the Pagekit language files. It is frequently updated with the current translations from Transifex.

## Contributing

Translations are managed via [Transifex](https://www.transifex.com/pagekit/pagekit-cms/). Please do not send any pull requests to this repository. Instead, we are thankful for every contribution to our translation files on Transifex.

## Maintainer instructions

This repository includes an update script to fetch new translations from Transifex.

1. `composer install`
2. `cp config.example.php config.php`
3. Fill in your Transifex user credentials in `config.php` (no worries, the file is on `.gitignore`)
4. `php update.php`
5. Grab a coffee while the script is fetching the new translations
6. Commit and push new and changed language files
