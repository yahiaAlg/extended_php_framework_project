# extended_php_framework_project
<!-- 
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

 -->
 
now moving on to the next example test



.
├── App
│   ├── Controllers
.....
│   ├── Models
.....
│   ├── Routes.php
│   └── Views
.....
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
│   ├── logger_example.php <======= we are going to do this one here
│   ├── mailing_helper_example.php
│   └── validation_example.php
├── src
│   ├── Core
......
│   ├── Database
......
│   ├── Logger  <======= in this package here
│   │   ├── DatabaseLogger.php
│   │   ├── FileLogger.php
│   │   └── Logger.php

```php

```

by buidling a custom user form for test and usage examples