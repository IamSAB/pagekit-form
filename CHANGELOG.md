# Changelog

## 1.0.0

### Added
- send tag (vue component) having a 'to' attribute is the only markup required create a sendable form
- integration with Captcha module form pagekit
- define own php template for email content with 'tmpl' attribute

### Changed
- use configuration attributes on send tag instead on form tag
- requires Pagekit ^1.0.15
- requires PHP ^7.1
- switch from [sunra/php-simple-html-dom-parser](https://github.com/sunra/php-simple-html-dom-parser) to  [thesoftwarefanatics/php-html-parser](https://github.com/thesoftwarefanatics/php-html-parser)

### Removed
- wrapping of form tag
- own Captcha configuration