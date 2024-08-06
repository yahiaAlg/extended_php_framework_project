we are currently building a custom  php MVC framework and here is the project structure

```console
.
├── App
│   ├── Controllers
│   │   └── PagesController.php
│   ├── Models
│   │   └── UserModel.php
│   ├── Routes.php
│   └── Views
│       ├── about.view.php
│       ├── contact.view.php
│       └── home.view.php
├── composer.json
├── config
│   ├── config.php
│   ├── database.php
│   ├── media.php
│   └── paths.php
├── examples
│   ├── bootstrap_forms_example.php
│   ├── database_example.php
│   ├── file_uploader_example.php
│   ├── forms_example.php
│   ├── logger_example.php
│   ├── mailing_helper_example.php
│   └── validation_example.php
├── framework_structre_creator.sh
├── logs
│   └── app.log
├── public
│   ├── css
│   ├── images
│   ├── index.php
│   ├── js
│   └── uploads
│       ├── documents
│       ├── images
│       │   └── ab86053ece4c0372.png
│       └── videos
├── README.md
├── src
│   ├── Core
│   │   ├── Controllers
│   │   │   └── Controller.php
│   │   ├── Models
│   │   │   └── Model.php
│   │   ├── Router
│   │   │   └── Router.php
│   │   └── Views
│   │       └── View.php
│   ├── Database
│   │   ├── DatabaseConnection.php
│   │   └── QueryBuilder.php
│   ├── FileUploader
│   │   ├── FileUploader.php
│   │   └── FileUploadException.php
│   ├── Forms
│   │   ├── AudioField.php
│   │   ├── BooleanField.php
│   │   ├── Bootstrap
│   │   │   ├── AudioField.php
│   │   │   ├── BooleanField.php
│   │   │   ├── CharField.php
│   │   │   ├── ChoiceField.php
│   │   │   ├── DateField.php
│   │   │   ├── DateTimeField.php
│   │   │   ├── EmailField.php
│   │   │   ├── Field.php
│   │   │   ├── FileField.php
│   │   │   ├── FloatField.php
│   │   │   ├── Form.php
│   │   │   ├── ImageField.php
│   │   │   ├── IntegerField.php
│   │   │   ├── PasswordField.php
│   │   │   ├── RadioField.php
│   │   │   ├── TextAreaField.php
│   │   │   ├── TimeField.php
│   │   │   └── VideoField.php
│   │   ├── CharField.php
│   │   ├── ChoiceField.php
│   │   ├── DateField.php
│   │   ├── DateTimeField.php
│   │   ├── EmailField.php
│   │   ├── Field.php
│   │   ├── FileField.php
│   │   ├── FloatField.php
│   │   ├── Form.php
│   │   ├── ImageField.php
│   │   ├── IntegerField.php
│   │   ├── PasswordField.php
│   │   ├── RadioField.php
│   │   ├── TextAreaField.php
│   │   ├── TimeField.php
│   │   └── VideoField.php
│   ├── Helpers
│   │   ├── AuthenticationHelper.php
│   │   ├── DebugHelpers.php
│   │   ├── ErrorHelpers.php
│   │   ├── FlashMessageHelper.php
│   │   ├── MailingHelper.php
│   │   ├── MiddlewareHelper.php
│   │   └── SessionHelper.php
│   ├── Logger
│   │   ├── DatabaseLogger.php
│   │   ├── FileLogger.php
│   │   └── Logger.php
│   ├── Middleware
│   │   └── CSRFMiddleware.php
│   └── Validation
│       ├── Rules
│       │   ├── Email.php
│       │   ├── MaxLength.php
│       │   ├── MinLength.php
│       │   ├── Numeric.php
│       │   ├── Required.php
│       │   └── Rule.php
│       └── Validator.php
├── tests
│   ├── BootstrapFormsTest.php
│   ├── DatabaseTest.php
│   ├── FileUploaderTest.php
│   ├── FormsTest.php
│   ├── LoggerTest.php
│   ├── QueryBuilderTest.php
│   └── ValidationTest.php
└── vendor
    └── autoload.php
```

in this examples folder we are creating the different driver codes for testing and  different use cases of different components of this framework,
├── examples
│   ├── bootstrap_forms_example.php
│   ├── database_example.php
│   ├── file_uploader_example.php
│   ├── forms_example.php
│   ├── logger_example.php
│   ├── mailing_helper_example.php
│   └── validation_example.php

currently we are going to do the helpers components examples:
│   ├── Helpers <===== we are in this package here
│   │   ├── AuthenticationHelper.php   
│   │   ├── DebugHelpers.php
│   │   ├── ErrorHelpers.php
│   │   ├── FlashMessageHelper.php   <===== starting with this flash message utility here
│   │   ├── MailingHelper.php
│   │   ├── MiddlewareHelper.php
│   │   └── SessionHelper.php

FlashMessageHelper.php

```php
<?php

namespace Helpers;

class FlashMessageHelper
{
    private static $flashKey = 'flash_messages';

    public static function set($message, $type = 'info')
    {
        SessionHelper::start();
        $flash = SessionHelper::get(self::$flashKey, []);
        $flash[] = ['message' => $message, 'type' => $type];
        SessionHelper::set(self::$flashKey, $flash);
    }

    public static function get()
    {
        SessionHelper::start();
        $flash = SessionHelper::get(self::$flashKey, []);
        SessionHelper::remove(self::$flashKey);
        return $flash;
    }

    public static function hasMessages()
    {
        SessionHelper::start();
        return SessionHelper::has(self::$flashKey) && !empty(SessionHelper::get(self::$flashKey));
    }

    public static function display()
    {
        $messages = self::get();
        $output = '';
        foreach ($messages as $message) {
            $output .= "<div class='alert alert-{$message['type']}'>{$message['message']}</div>";
        }
        return $output;
    }
}
```

SessionHelper.php
```php
<?php

namespace Helpers;

class SessionHelper
{
    public static function start()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function has($key)
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function remove($key)
    {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function clear()
    {
        self::start();
        session_unset();
        session_destroy();
    }
}
```

now create me the flash_message_example.php and the session_example.php
to be added to the examples with thier diff use cases and with custom forms and submission flows for testing



  