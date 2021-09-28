## 1.Charming dev
* Controller, console, flex recipes, twig
* Using assets, json api, and webpack
- Sample: Blog pages and commentary

## 2.Sym5 fundamentals: Service, config, env
* Twig markdown filter, Caching system
* - Services, parameters, autowire, interface
* Using Sentry (and maybe New Relic)
* Secrets vault, Maker Bundle, create Command

## 3.HttpKernel Request-Response
- Kernel (config, globals) & HttpKernel->handleRaw()
* Events and Subscribers
* RouteListener and parameters
* ControllerResolver and callable
* ArgumentResolver (--tag=controller.argument_value_resolver)
    ServiceValueResolver
* ViewEvent->setResponse(), ResponseEvent
* ErrorController, ErrorListener & Sub-requests "render(controller)"
* TwigRenderer, HtmlRenderer and SerializerErrorRenderer
    - vendor/symfony/error-handler/Resources/views/exception_full.html.php
* ArgumentResolver, @sensio/ParamConvertListener, DoctrineParamConverter

## 4.HttpKernel Request-Response
- Kernel (config, globals) & HttpKernel->handleRaw()
* Events and Subscribers
* RouteListener and parameters
* ControllerResolver and callable
* ArgumentResolver (--tag=controller.argument_value_resolver)
    ServiceValueResolver
* ViewEvent->setResponse(), ResponseEvent
* ErrorController, ErrorListener & Sub-requests "render(controller)"
* TwigRenderer, HtmlRenderer and SerializerErrorRenderer
    - vendor/symfony/error-handler/Resources/views/exception_full.html.php
* ArgumentResolver, @sensio/ParamConvertListener, DoctrineParamConverter

## 5.Doctrine and database
* Dotenv, Doctrine console & Migrations
* Doctrine Queries, Repository, SensioFrameworkExtraBundle
* KnpLabs (Markdown, Menu, Paginator...)
* Orm fixtures, zenstruck/foundry, Doctrine Extensions
    -
- ORM is what does all the mapping of data onto objects.
- DBAL is a wrapper around PDO, can be used independent of the ORM.
* DoctrineFilter (config and enable)

## 6.Security & Authentication
- make:user, make:auth, make:voter, security.yaml
- AbstractFormLoginAuthenticator, Voters, ApiTokens
- @IsGranted, role_hierarchy, GuardAuthenticatorHandler

## 7.What's new
extra.symfony.require (flex) / config.plateform.php   
composer recipes:install symfony/console --force -v   
monolog.handlers.deprecation(_filter)   
* Secret management system and vault: console secrets:set SECRET --env=prod --local  
     php -r 'echo base64_encode(require "config/secrets/prod/prod.decrypt.private.php");'
* Validation Auto-Mapping: Entity class @Assert\EnableAutoMapping()  
     <button type="submit" formnovalidate> / composer show symfony/property-info
* Migrate password hashing: security.encoders.<legacy_bcrypt>  
     security.encoders.<entity>.migrate_from.<- legacy_bcrypt>   
     PasswordAuthenticatedInterface -> rehash legacy passwords when login  PasswordUpgraderInterface
* PHP 7.4 "preloading": opcache.preload: App_KernelProdContainer.preload.php
* Container Running: console lint:container / composer why <package>

## 8.Forms
composer req validator, 
    "sanity validation": (string into a number field...), Constraints inside form Type
    "business rules validation" (check length, characters...), @Assert on entity
mapped value, form_widget, UniqueEntity, form_theme, block_prefix
DataTransformer, FormTypeExtension, DTO, make:validator
FormEvents