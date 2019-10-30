<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceProviderInterface;
use Authorization\AuthorizationService;
use Authorization\AuthorizationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Authorization\Middleware\AuthorizationMiddleware;
use Authorization\Policy\OrmResolver;
use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication implements AuthenticationServiceProviderInterface, AuthorizationServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function bootstrap()
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        $this->addPlugin('Authentication');
        //$this->addPlugin('Authorization');

        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        }

        if (Configure::read('debug')) {
            $this->addPlugin(\DebugKit\Plugin::class);
        }
    }

    /**
    * {@inheritDoc}
    */
    public function getAuthenticationService(ServerRequestInterface $request, ResponseInterface $response) {
      $service = new AuthenticationService();

      $fields = [
        'fields' => [
          'username' => 'username',
          'password' => 'password'
        ]
      ];

      $service->loadIdentifier('Authentication.Password', $fields);

      $service->loadAuthenticator('Authentication.Session');
      $service->loadAuthenticator('Authentication.Form', [
        'fields' => $fields['fields'],
        'loginUrl' => '/login/login'
      ]);

      return $service;
    }

    public function getAuthorizationService(ServerRequestInterface $request, ResponseInterface $response) {
      $resolver = new OrmResolver();
      return new AuthorizationService($resolver);
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware($middlewareQueue)
    {
        $middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(null, Configure::read('Error')))

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime')
            ]))

            // Add routing middleware.
            // If you have a large number of routes connected, turning on routes
            // caching in production could improve performance. For that when
            // creating the middleware instance specify the cache config name by
            // using it's second constructor argument:
            // `new RoutingMiddleware($this, '_cake_routes_')`
            ->add(new RoutingMiddleware($this))

            ->add(new AuthenticationMiddleware($this));
            //->add(new AuthorizationMiddleware($this));

        return $middlewareQueue;
    }

    /**
     * @return void
     */
    protected function bootstrapCli()
    {
        try {
            $this->addPlugin('Bake');
        } catch (MissingPluginException $e) {
            // Do not halt if the plugin is missing
        }

        $this->addPlugin('Migrations');

        // Load more plugins here
    }
}
