
[![Build status — Travis-CI][travis-icon]][travis]

# openEssayist-slim

Web frontend to the essay analyser and summariser — [pyEssayAnalyser][py].

To check:

* https://github.com/silentworks/sharemyideas
* https://github.com/Tieno/SlimPackage
* https://github.com/codeguy/Slim-Extras
* http://silentworks.co.uk/blog/development/using-phpactiverecord-with-slim-framework.html

## Redhat preparation

```sh
composer redhat-check
composer redhat-install
```

## Install .. Test

```sh
composer install
composer patch       # Apply Diff to 'vendor/slim/extras'
composer copy-conf   # app/config.php
composer copy-nginx  # Only on Redhat
composer mkdir
composer chown       # Only on Redhat 7.

composer test
```

 * GitHub: [SAFeSEA/openEssayist-slim][gh]
 * GitHub: [IET-OU/openEssayist-slim][gh-iet]
 * GitHub: [SAFeSEA/pyEssayAnalyser][gh-py]


---
© 2013-2018 [The Open University][ou]. ([Institute of Educational Technology][iet])

[ou]: http://www.open.ac.uk/
[iet]: https://iet.open.ac.uk/

[py]: https://github.com/SAFeSEA/pyEssayAnalyser
[gh]: https://github.com/SAFeSEA/openEssayist-slim "Original"
[gh-iet]: https://github.com/IET-OU/openEssayist-slim "Fork"
[gh-py]: https://github.com/SAFeSEA/pyEssayAnalyser "Python"
[travis]:  https://travis-ci.org/SAFeSEA/openEssayist-slim
[travis-icon]: https://api.travis-ci.org/SAFeSEA/openEssayist-slim.svg
    "Build status – Travis-CI (PHP)"

[End]: //.
