#!/bin/bash

# Create main directories
mkdir -p src/{FileUploader,Logger,Database,Validation,Forms/Bootstrap} examples tests

# Create FileUploader files
touch src/FileUploader/{FileUploader.php,FileUploadException.php}

# Create Logger files
touch src/Logger/{Logger.php,FileLogger.php,DatabaseLogger.php}

# Create Database files
touch src/Database/{DatabaseConnection.php,QueryBuilder.php}

# Create Validation files
mkdir -p src/Validation/Rules
touch src/Validation/Validator.php
touch src/Validation/Rules/{Rule.php,Required.php,MinLength.php,MaxLength.php,Email.php,Numeric.php}

# Create Forms files
touch src/Forms/{Form.php,Field.php,CharField.php,EmailField.php,PasswordField.php,IntegerField.php,FloatField,BooleanField.php,ChoiceField.php,DateField.php,FileField.php,ImageField,DateTimeField,TimeField,VideoField,AudioField,RadioField.php,TextAreaField.php}

# Create Bootstrap Forms files
touch src/Forms/Bootstrap/{Form.php,Field.php,CharField.php,EmailField.php,PasswordField.php,IntegerField.php,FloatField,BooleanField.php,ChoiceField.php,DateField.php,FileField.php,ImageField,DateTimeField,TimeField,VideoField,AudioField,RadioField.php,TextAreaField.php}

# Create example files
touch examples/{file_uploader_example.php,logger_example.php,database_example.php,validation_example.php,forms_example.php,bootstrap_forms_example.php}
# Create autoloader.php and README.md
touch autoloader.php 
# Create test files
touch tests/{FileUploaderTest.php,LoggerTest.php,DatabaseTest.php,QueryBuilderTest.php,ValidationTest.php,FormsTest.php,BootstrapFormsTest.php}

# Create Router and MVC structure
mkdir -p src/Router src/Controllers src/Models src/Views
touch src/Router/{Router.php,Route.php}
touch src/Controllers/Controller.php
touch src/Models/Model.php
touch src/Views/View.php

# Helpers
mkdir -p src/Helpers
touch src/Helpers/{SessionHelper.php,FlashMessageHelper.php,MiddlewareHelper.php,AuthenticationHelper.php,MailingHelper.php,ErrorHelpers.php,DebugHelpers.php}

# Middlewares
mkdir -p src/Middleware
touch src/Middleware/CSRFMiddleware.php

#autoloader
mkdir -p vendor
touch vendor/autoload.php

#configs
mkdir -p config
touch config/config.php
touch config/database.php
touch config/paths.php
touch config/media.php

# Create composer.json and README.md
touch composer.json README.md

echo "Project structure created successfully!"
