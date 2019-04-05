# Bem-vindo à Ødin!

Ødin é uma Framework do PHP simples, prática e eficiente. Desenvolvida para se fazer mais e escrever menos. Se ainda não tem uma licença, contate-nos em `contato@atomsi.com.br`.


# Documentação

## Instalação
Instale a Ødin através do Composer.
> composer require atomdev/odinfmk

## Iniciando um projeto

Primeiramente você deve definir as constantes `ODIN_SERIAL` e `ODIN_ROOT` no arquivo de autoload do Composer (`vendor/autoload.php`).

    define("ODIN_SERIAL", "SUA_SERIAL");
    define("ODIN_ROOT", "/diretorio/raiz/do/projeto");
Em seguida, na raiz do seu projeto, você deve definir a estrutura base do projeto.

    config/
       |-- .env
    http/
        controllers/
           |-- Aqui ficarão seus Controllers
        middlewares/
           |-- Aqui ficarão seus Middlewares
    database/
        models/
           |-- Aqui ficarão seus Models
    views/
       |-- Aqui ficarão suas views
    utils/
       |-- Aqui ficarão suas classes de utilidades e seus Helpers
    .htaccess
Vale ressaltar que a estrutura apresentada acima é apenas o modelo base padrão. Você pode criar suas pastas personalizadas além das mostradas acima.

### Definindo as configurações do projeto
Na pasta de `config`, você deverá criar um arquivo chamado `.env`. Este será o arquivo de configurações do projeto. Nele será definido o ambiente, o SGBD, os diretórios e url de acesso do projeto.
Veja o seguinte modelo:

    ENVIRONMENT     = dev
    DRIVER          = mysql
    SOURCE_DIR      = src/
    HTTP_ROOT	= http://localhost:8080
    HTTP_ROOT_FILES = http://localhost:8080/assets/ 
    LOGS_PATH	= src/logs
    


### Definindo as rotas da aplicação
Antes de definir suas rotas você precisa configurar o `.htaccess` localizado na raiz do projeto. Ao abrir você encontrará a seguinte estrutura:

    RewriteEngine On
    RewriteCond %{SCRIPT_FILENAME} !-f
    RewriteCond %{SCRIPT_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [QSA,L]

    ErrorDocument 403 http://localhost/pagina/de/erro/403
    Options All -Indexes
Você pode definir uma página para tratamento dos erros 403, que são lançados quando o usuário tenta acessar pastas proibidas. Por padrão, todas as pastas da aplicação têm o acesso proibido a abertura via HTTP.

Após configurar o `.htaccess` você poderá definir suas rotas no arquivo `index.php`, como no exemplo a seguir.

    <?php
    //Incluindo o autoload do Composer
    require_once(dirname(dirname(__FILE__)) . "/vendor/autoload.php");

	//Utilizando as classes Config e Routes da Framework
	use Odin\utils\Config;
	use Odin\routes\Routes;
	
	Config::init("projeto"); //nome da pasta do seu projeto
	Routes::init();

	//Rota GET
	Routes::get("/", function(){
	    echo "Primeiro projeto com a Ødin Framework!";
	})->name("home");
	//Rota POST
	Routes::post("/", "Projeto\http\controllers\Classe:metodo");
	//Rota PUT
	Routes::put("/", "Projeto\http\controllers\Classe:metodo");
	//Rota DELETE
	Routes::delete("/", "Projeto\http\controllers\Classe:metodo");

	//Definindo um grupo de rotas
	$instance = Routes::getInstance();
	Routes::group("/grupo", function() use ($instance){
	    Routes::get("/", "Projeto\http\controller\sClasse:metodo");
	});
	
	Routes::run();
A Ødin suporta 4 tipos de Requisições HTTP, sendo elas GET, POST, PUT e DELETE. Isso dá a você total facilidade e praticidade na hora de criar uma RESTful API.
## Controllers

Os controllers são Callables que são passados para as rotas para que alguma ação seja feita caso acessada. Você pode criar um controller para um rota de duas formas

Forma funcional:

    Routes::get("/teste", function(){
        echo "Olá, Mundo!";
    })
Forma Orientada a Objetos:

    Routes::get("/teste", "App\http\controllers\Teste:olaMundo");
Para a forma OO, você precisa ter criado uma classe dentro da pasta `controllers` de acordo com o exemplo abaixo.

    <?php
    namespace App\http\controllers; //O namespace master é o de sua preferencia
    
    use Odin\http\controller\Controller;

    class Teste extends Controller
    {
        public function olaMundo()
        {
            echo "Olá, Mundo";
        }
    }
### Recuperando valores em rotas dinâmicas no Controller
A Ødin também suporta rotas dinâmicas, ou seja, rotas que contém valores variáveis na estrutura, como no exemplo a seguir.

    Routes::get("/filme/:categoria", "App\http\controllers\Filmes:listarPelaCategoria");
Para definir uma parte dinâmica em uma rota você deve utilizar `/:nomeDaVariavel`.
Alguns exemplos de requisição para este tipo de rota seriam:

    GET http://localhost/projeto/filme/terror
    GET http://localhost/projeto/filme/acao
    GET http://localhost/projeto/filme/romance
    
Para recuperar esse valor passado na rota e utilizá-lo em sua aplicação basta adicionar um parâmetro ao método do Controller.

    class Filmes extends Controller
        {
            public function listarPelaCategoria($categoria)
            {
                echo $categoria;
            }
        }
Você também pode definir um padrão a ser passado às variáveis através de um regex, como no exemplo abaixo.

    Routes::get("/filme/:id", "App\http\controllers\Filmes:selecionarPeloId", ["id" => "[\d]{1,8}"]);

O regex irá forçar a valor a seguir o padrão, caso contrário, um erro será lançado.

### Redirecionamento de Rotas

Você também pode fazer redirecionamento utilizando a nomenclatura de rotas. Como no exemplo a seguir.

    Routes::get("/login", "App\http\controllers\Login:view")->name("loginPage");

Para fazer o redirecionamento basta usar a propriedade `router` no contexto.

    $this->router->redirectTo("loginPage");

### Utilizando Views
Como qualquer aplicação MVC, você poderá usar suas views para renderizar elementos visuais, como formulários, listas, etc.
Na Ødin, as views são renderizadas a partir do Controller, mais especificamente dentro do método acionado ao acessar a rota.

    Config::init("projeto");
    Routes::init();
    
    Routes::get("/login", "App\http\controllers\Login:view");

O Controller buscará pelas Views na pasta `projeto/source/views`.

Para renderizar a view, basta seguir o modelo a baixo.

    use Odin\view\View;
    
    class Login extends Controller
    {
        use View;
    
        public function view()
        {
            View::render($this, "login_page.php");
        }
     }


### Parâmetros de `View::render()`

A tabela abaixo mostra os parâmetros que podem ser passado para o método render. Os parâmetros cujo Valor Padrão está vazio significa que deve conter um valor obrigatório.

|NOME|TIPO|VALOR PADRÃO|DESCRIÇÃO|
|----|----|-----|---------|
|`$controller`|`IController`||Instância do Controller|
|`$page`      |`string`||Nome do arquivo a carregar|
|`$params`    |`array`|`[]`|Valores a serem passados para a View|
|`$hf`        |`bool`|`true`|Informa se a View deve ou não renderizar o arquivo de Cabeçalho e Rodapé definidos como padrão|

### Definindo Cabeçalho e Rodapé padrão

Você pode definir um arquivo como header e outro como footer para armazenar informações de contexto global, esses arquivos devem estar na pasta `views`. Como por exemplo o `<head>` e todas as chamadas de CSS de uma página HTML ou `<script>` no final da página. Como mostra o exemplo a seguir.

> `views/header.php`

    <!DOCTYPE html>
    <html>
        <head>
            <title>Projeto</title>
            <meta charset="utf-8"/>
            <link rel="stylesheet" type="text/css" href="./css/bootstrap.css"/>
            <link rel="stylesheet" type="text/css" href="./css/style.css"/>
        </head>
        <body>

  

> `views/footer.php`

            <script src="./js/jquery.js"></script>
            <script src="./js/bootstrap.js"></script>
        </body>
    </html>

> `index.php`

    Config::init("projeto");
    Routes::init();
    
    Routes::setHF("header.php", "footer.php");
    
    Routes::get("/login", "App\http\controllers\Login:view");

Caso você tenha alguma página que deve carregar um header e um footer específico, você poderá bloquear o carregamento dos arquivos padrão no Controller, como no exemplo abaixo.

    public function view()
    {
        View::render($this, "login_page.php", [], false);
    }

### Passando valores do Controller para a View

Você pode compartilhar dados do Controller para a suas Views, por exemplo, os dados parar fazer uma listagem de filmes.

    public function listarFilmes()
    {
        View::render($this, "listar_filmes.php", [
	        "itens" => ["Filme 1", "Filme 2", "Filme 3"]
	    ]);
    }
   
Na sua view:

    <div class="container">
        <table class="table table-hover">
	        <thead>
	            <tr>
	                <th>Chave</th>
	                <th>Filme</th>
	            </tr>
	        </thead>
	        <tbody>
	            <?php
	                foreach($itens as $i => $item)
	                {
		                echo "<tr>";
		                echo "<td>{$i}</td>";
		                echo "<td>{$item}</td>";
		                echo "</tr>";
	                }
	            ?>
	        </tbody>
        </table>
    </div>
    
Utilizando desta mesma propriedade, você pode recuperar o URL de qualquer rota que você tenha definido em suas Views, facilitando a navegação entre diversas páginas. Mas para isso você precisa passar o objeto `router` como uma variável para a View.

    public function view()
    {
        View::render($this, "login_page.php", [
            "router" => $this->router
        ]);
    }
Na view:

    <a href="<?= $router->pathFor("loginPage") ?>">Login Page</a>

Caso sua rota seja dinâmica e seja necessário passar um valor, você pode passar as referências no segundo parâmetro do método `pathFor()`.

    <a href="<?= $router->pathFor("listarFilmes", ["categoria" => "terror"]) ?>">Login Page</a>

### Objetos de escopo Global (Dependencias)

Caso sua view necessite acessar algum objeto de alguma classe utils ou algum helper, você pode definir objetos de escopo global no arquivo `index.php` como mostra o exemplo a seguir.

    use Odin\Globals;

    Globals::set([
	    new Helper(),
	    new Dependencia()
	]);
Na view, você pode acessar esses objetos passados como dependencias da seguinte forma:

    <div>
        <?= $helper->someMethod() ?>
        <?= $dependencia->algumaCoisa() ?>
    </div>

## Middlewares

A Ødin também oferece suporte a Middlewares, de maneira bem prática e simples, como no exemplo a seguir.

    namespace App\http\middlewares;

    use Odin\http\middleware\IMiddleware;
    use Odin\utils\superglobals\Session;

    class Auth implements IMiddleware
    {
        public function handle($request, $response, $next)
        {
            if(Session::exists("_token"))
            {
	            return $next($request, $response);
            }
            else
            {
	            die("Você precisa fazer login para acessar esta página");
            }
        }
    }

No `index.php` você define a lista de Middlewares através do método `add()`.

    Routes::add(["home"], [
	    new Auth()
    ]);
    
    Routes::get("/home", "App\http\controllers\Home:landing")->name("home");

Você pode adicionar Middlewares a rotas, names e groups. Para adicionar uma Middleware a todas as rotas basta usar `["*"]`.

## Models

### CRUD

Antes de começar a usar os Models em seu projeto, certifique-se de que está definida a diretiva `DRIVER` em `config/.env` , e, se há um arquivo com os dados da conexão na mesma pasta.
Ex: `config/mysql.env` ou `config/pgsql.env`

Para utilizar Models em seu projeto, basta criar uma classe na pasta `database/models` e atribuir a ela a seguinte estrutura.

    namespace App\database\models;
    
    use Odin\database\orm\ORMMapper;
    
    class SimpleModel extends ORMMapper
    {
	    private $tableName = "teste";
	    
	    public function __construct()
	    {
		parent::__construct();
		parent::setTableName($this->tableName);
	    }
    }

#### Select
Recuperando todos os registros existentes na tabela

    use App\database\models\SimpleModel;
    
    $model = new SimpleModel();
    
    var_dump($model->findAll());

Recuperando um registro pelo `id`

    $model->findById(10);


Recuperendo registros com a claúsula `WHERE`

    $model->where(["id" => 10], ">")->get(); // id > 10
    
    $model->where(["id" => [10, 20]], "BETWEEN")->get(); // id BETWEEN 10 AND 20
    $model->where(["id" => [10, 20]], "NOT BETWEEN")->get(); // id NOT BETWEEN 10 AND 20 
    
    $model->where(["name" => "User"], "LIKE")->get(); // name LIKE '%User%'
    $model->where(["name" => "user"], "LIKE_L")->get(); // name LIKE '%User'
    $model->where(["name" => "user"], "LIKE_R")->get(); // name LIKE 'User%'
    
    $model->where(["id" => [1, 2, 3, 4, 5]], "IN")->get(); // id IN (1,2,3,4,5)
    $model->where(["id" => [1, 2, 3, 4, 5]], "NOT IN")->get(); // id NOT IN (1,2,3,4,5)
    
    $model->where(["id" => "NULL"], "IS")->get(); // id IS NULL

Recuperando campos específicos com `get()`

    $model->where(["id" => 10], "<=")->get("id, name, age");

Ordenando registros com `ORDER BY`

    $model->where(...)->orderBy("id", "ASC")->get();
Agrupando registros

    $model->where(...)->groupBy("id")->get();
    
    $model->where(...)->groupBy("id")->having(...)->get();

#### Insert
Para inserir registros, basta você adicionar novas propriedades ao objeto do Model e usar o método `save()`.

    $model->name = "New User";
    $model->email = "user@email.com";
    $model->age = 20;
    
    $model->save();
    
O método save deve retornar um `PDOStatement` em caso de sucesso, ou `false` em caso de falha ao inserir o novo registro.

#### Update
Para realizar um update, você vai utilizar a mesma estrutura do insert, contudo, o `id` deverá ser informado. Ele é a chave para a diferenciação de comando INSERT de um UPDATE.

    $model->id = 25;
    $model->name = "Name Updated";
    
    $model->save();

#### Delete
Para realizar um DELETE você deverá primeiro recuperar o registro que deseja excluir e usar o método `remove()`. Como no exemplo abaixo.

    $model->findById(10)->remove();

### Joins
Você pode adicionar Alias às tabelas para facilitar seu uso, como no exemplo a seguir.

    class SimpleModel extends ORMMapper
    {
       private $tableName = "teste";
       private $tableAlias = "t";
    	    
       public function __construct()
       {
    	parent::__construct();
    	parent::setTableName($this->tableName, $this->tableAlias);
       }
    }

E usá-lo da seguinte forma

    $model->innerJoin("another a")->on("a.id = t.fk")->get();
    $model->leftJoin("another a")->on("a.id = t.fk")->get();
    $model->rightJoin("another a")->on("a.id = t.fk")->get();

    $model->innerJoin("another a")
		->on("a.id = t.fk")
		->where(["id" => 10], ">=")
		->get();

## Utilidades

### Flash Messages

A classe `Odin\utils\FlashMessages` permite a você definir mensagens que se autodestroem após as visualização, esse recurso pode ser utilizado para a implementação de notificação em tempo de aplicação, veja o seguinte exemplo.

    public function simpleMethod()
    {
	    //Define uma nova mensagem
	    FlashMessages::add("Teste", "Testando mensagens flash");
	    
	    //Recupera o valor da mensagem
	    echo FlashMessages::get("Teste");
	    
	    //Verifica se há uma mensagem na chave informada
	    var_dump(FlashMessages::has("Teste"));
    }

### Superglobais

O namespace `Odin\utils\superglobals` contém classes para manuseamento das superglobais nativas do PHP (`$_GET, $_POST, $_SERVER, $_SESSION, $_COOKIE e $_FILES`).

Veja o exemplo abaixo.

    use Odin\utils\superglobals\Post;
    use Odin\utils\FlashMessages;
    
    class Teste extends Controller
    {
        public function autenticar()
        {
            $usuario = Post::get("usuario");
            $senha = Post::get("senha");

	        if($usuario === "user" && $senha === "pass")
	        {
	            FlashMessages::add("Sucesso", "Autenticação realizada com sucesso!");
	            $this->router->redirectTo("home");
	        }
	        else
	        {
	            FlashMessages::add("Erro", "Não foi possível realizar a autenticação");
	            $this->router->redirectTo("login");
	        }
        }
    }

### Filtros de Dados

A classe `Odin\utils\Filter` fornece uma série de métodos para a realização da filtragem de dados, sejam senhas, emails ou textos comuns.

    use Odin\utils\Filter;
    ...
    public function filtrarDados()
    {
        $email = Filter::email(Post::get("email"));
        $senha = Filter::clear(Post::get("senha"));

	    var_dump(Filter::isValidEmail($email));
    }

### Headers

Você também pode gerenciar os Headers das suas requisições com a classe `Odin\http\server\Header`.

    use Odin\http\server\Header;
	...
    public function getDataAsJSON()
    {
        Header::contentType("application/json");

	    return [
	        ["name" => "John", "age" => 20],
	        ["name" => "Doe", "age" => 22]
	    ];
    }

### Enviando Emails

Você pode enviar emails nativamente pela função `mail` de forma mais simples utilizando a classe `Odin\utils\Mail`, como mostra o exemplo.

    public function enviarEmail()
    {
        Mail::from("sender@email.com.br");
        Mail::write("to@email.com.br", "Teste de Envio de Email");
        Mail::headers();
        Mail::message("<b>Hey! Testando envio de email pela Ødin!</b>");

	    echo (Mail::send() ? "Email enviado." : "Email não enviado.");
    }

