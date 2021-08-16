# Charming dev
* Controller, console, flex recipes, twig
* Using assets, json api, and webpack
- Sample: Blog pages and commentary

# Sym5 fundamentals: Service, config, env
* Twig markdown filter, Caching system
* - Services, parameters, autowire, interface
* Using Sentry (and maybe New Relic)
* Secrets vault, Maker Bundle, create Command

# Doctrine and database
* Dotenv, Doctrine console & Migrations
* Doctrine Queries, Repositoryn, SensioFrameworkExtraBundle
* KnpLabs (Markdown, Menu, Paginator...)
* Orm fixtures, zenstruck/foundry, Doctrine Extensions

# HttpKernel Request-Response
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

# Creating Bundle
* Services.xml, Configuration, DependencyInjection, Extensions
* Functional tests, Microkernel and Events
    - registerContainerConfiguration()
* Tags: A bundle can finds all services with tag and dynamically add them to the collection argument
* CompilerPass (Operate on the entire container, after configuration)
* Readme: 1.What the bundle does, 2.composer require installation command
    3.simple usage example, 4.show the configuration.
