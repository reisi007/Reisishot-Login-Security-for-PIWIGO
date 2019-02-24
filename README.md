# Setup

You need the folllowing config variables set:

- $conf['password_verify'] = 'reisishot_password_verify';
- $conf['recaptcha_public'] = '`<PUBLIC KEY>`';
- $conf['recaptcha_secret'] = '`<PRIVATE KEY>`';

The keys can be obtained at: https://www.google.com/recaptcha/admin#list. **reCaptcha v2** is used in this plugin!

# PHP configuration

This software needs url fopen ( http://php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen ). Please set this configuration to true.
