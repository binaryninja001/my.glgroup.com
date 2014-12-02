#Symplified-SSO

A custom single sign-on (SSO) portal for [MyGLG](https://customsso.glgroup.com/public/portal.php) powered by [Symplified](http://www.symplified.com/features/single-sign-on/) + custom PHP, JavaScript, HTML, and CSS


<i><b><u>IMPORTANT:</i></b></u> ***The production portal page makes API calls hosted in [Operations/admttu] (https://github.com/glg/admttu)***


## Local Development

Local development has only been tested on OS X.

1.  Clone this repository and enter the project directory

  ```
  $ git clone git@github.glgroup.com:Operations/Symplified-SSO.git
  $ cd Symplified-SSO
  ```

2.  Run the PHP server

  ```
  $ php -c conf/php5/fpm/php.ini -S 127.0.0.1:8080
  ```

3.  Confirm you can access the site by navigating to `http://localhost:8080/public/portal.php` with a web browser

Much of the code in this repository relies on properties made avaialble by the `$_SERVER` variable, properties which are only available in a production environment.  When running locally, the command line interface (CLI) output may be helpful in determining what (if any) temporary code changes are needed to support local development.


## How To

### Add, Edit, or Remove a Link on the MyGLG Portal

The configuration for the [MyGLG Portal](https://customsso.glgroup.com/public/portal.php) can be found at `public/portal_content.json`, and commits to this file pushed to the `prod` branch will be directly reflected in the portal.  The `text`, `icon`, and `url` properties in the `portal_content.json` are required to add an entry to the portal:

```
{
    "text": "Vega",
    "url": "https://vega.glgroup.com/vega/index.aspx",
    "icon": "vega.jpg"
},
```

Some entries in `portal_content.json` have only a `text` property.  These entries pull additional data from [Symplified](http://www.symplified.com/features/single-sign-on/) and are managed by [David Wolff](mailto:DWolff@glgroup.com).  The icon for these special entries should be located at `public/img/<text>.jpg`, where `<text>` is the value of the `text` property in `portal_content.json`.
