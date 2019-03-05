<?php

/**
 * @author Edney Mesquita
 */
namespace Odin;

use Odin\http\server\Request;
use Odin\http\server\Response;
use Odin\routes\{Render, Container, Router};
use Odin\utils\Serial;

class App
{

    /**
     * Objeto \Odin\routes\Router
     * @var $router Router
     */
    protected $router;

    /**
     * Objeto \Odin\http\server\Request
     * @var $request Request
     */
    protected $request;

    /**
     * Objeto \Odin\http\server\Response
     * @var $response Response
     */
    protected $response;

    /**
     * Objeto Odin\routes\Container
     * @var $container Container
     */
    protected $container;

    /**
     * Objeto \Odin\routes\Render
     * @var $render Render
     */
    public $render;

    /**
     * Pagina notFound modificada
     * @var $notFoundModified false|callable
     */
    protected $notFoundModified = false;

    /**
     * Exceptions adicionais da App a serem lançadas!
     * @var $addedExceptions array
     */
    protected $addedExceptions = array();

    public function __construct($paramsContainer = array())
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->render = new Render();
        $this->router = new Router($this->request);

        $content = [
            'request' => $this->request,
            'response' => $this->response,
            'render' => $this->render,
            'router' => $this->router
        ];
        $params = array_merge($paramsContainer, $content);
        $this->container = new Container($params);
    }

    /**
     * @return \Odin\routes\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Recebe um callable e retorna sua referencia de acordo
     * @return callable
     */
    private function validCallable($callable)
    {
        if (is_callable($callable)) {
            if ($callable instanceof \Closure) {
                $callable = $callable->bindTo($this->container);
            }

            return $callable;
        } elseif (is_string($callable) && count(explode(':', $callable)) == 2) {
            return $callable;
        }

        $this->addedExceptions['\InvalidArgumentException'] = 'Callable inválido';
        return false;
    }

    /**
     * Emula metodos get, post, put, delete e group do objeto Router
     */
    public function __call($method, $args)
    {
        $methodUpper = strtoupper($method);
        $accepted = $this->router->getRequestAccepted();
        if (in_array($methodUpper, $accepted)) {
            if (count($args) == 3) {
                $conditions = $args[2];
            } elseif (count($args) == 2) {
                $conditions = array();
            }
            if ($args[0] == '/[:options]') {
                $this->addedExceptions['\InvalidArgumentException'] = 'Route pattern /[:options] inválido';
            } else {
                $callable = $this->validCallable($args[1]);
                return $this->router->route($methodUpper, $args[0], $callable, $conditions);
            }
        } elseif ($method == 'group' && count($args) == 2) {
            $callable = $this->validCallable($args[1]);
            return $this->router->group($args[0], $callable);
        } else {
            $this->addedExceptions['\Exception'] = 'O metodo '.$method.' não existe';
        }
    }

    /**
     * Define uma pagina notfoud
     * @param callable $fnc
     */
    public function notFound($fnc)
    {
        if (is_callable($fnc)) {
            if ($fnc instanceof \Closure) {
                $this->notFoundModified = $fnc;
            } else {
                $this->addedExceptions['\InvalidArgumentException'] = 'O callable do metodo notFound deve ser um closure!';
            }
        } else {
            $this->addedExceptions['\InvalidArgumentException'] = 'App::notFound, callable invalido';
        }
    }

    /**
     * Retorna o path root via request
     * @return string
     */
    public function root()
    {
        return $this->request->getRoot();
    }

    /**
     * Lança exceções adicionais do objeto App.
     */
    private function runAddedExceptions()
    {
        if (!empty($this->addedExceptions)) {
            \Odin\utils\Errors::throwErrors($this->addedExceptions);
        }
    }

    public function add(array $routeNames, array $middewares)
    {
        $this->router->add($routeNames, $middewares);
    }

    /**
     * Da inicio a App. Executando as rotas criadas, renderizando uma pagina 404
     * ou exibindo a mensagem de uma exceção que tenha sido lançada
     */
    public function run()
    {
        if(Serial::validate()){
            try
            {
                $this->runAddedExceptions();

                if ($this->router->dispatch()) {
                    $this->router->execute($this->container);
                } else {
                    if ($this->notFoundModified) {
                        $fnc = $this->notFoundModified->bindTo($this->container);
                        $fnc();
                    } else {
                        $this->render->renderNotFoundPage();
                    }
                }
            }
            catch (\Exception $e)
            {
                \Odin\utils\Errors::throwError('Exception!', $e->getMessage(), 'bug');
            }
        }else{
            \Odin\utils\Errors::throwError('Serial Inválida!', "Sua serial é inválida ou não está definida. Entre em contato para mais informações!", 'bug');
            die();
        }
    }

}
