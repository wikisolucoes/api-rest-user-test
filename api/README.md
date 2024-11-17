# API Rest User

API Rest base para gestão de usuários

## Instalação

A aplicação requer que [PHP](https://www.php.net) e o [Docker](https://www.docker.com/) estejam instalados para que seja possível levantar o ambiente.

Faça o clone do repositório, e entre na raiz da pasta:

```sh
git clone https://github.com/wikisolucoes/api-rest-user.git
cd api-rest-user/api/
```

Criar o arquivo _.env_ a partir do arquivo _.env.example_:

```sh
cp .env.example .env
```

_Obs: Edite o arquivo .env para definir os dados de acesso ao banco de dados e usuário administrador._

Instalar dependências do PHP:

```sh
composer install
```

Levantar o ambiente utilizando o Laravel Sail (Docker):

```sh
./vendor/bin/sail up -d 
```

Verificar se todos os containers subiram corretamente:

```sh
docker ps
```

Deverá listar todos os containers conforme o exemplo abaixo:

```sh
CONTAINER ID   IMAGE                       COMMAND                  CREATED         STATUS         PORTS                         
8db51fc61e92   sail-8.3/app                "start-container"        7 seconds ago   Up 6 seconds   0.0.0.0:8000->80/tcp  
998cf9c2f914   mysql/mysql-server:8.0.20   "/entrypoint.sh --de…"   7 seconds ago   Up 6 seconds   0.0.0.0:3306->3306/tcp, 33060/tcp
```

Criar banco de dados e tabelas (Migrates):

```sh
./vendor/bin/sail artisan migrate
```

Inserir dados iniciais (Seeds):

```sh
./vendor/bin/sail artisan db:seed
```


Pronto!
A configuração inicial para executar o projeto está finalizada!

> Acesse pelo navegador: `http://localhost:8000`

## Comandos Docker

Segue abaixo alguns comandos de exemplo do Docker que podem ser úteis:

```sh
## LISTAR TODOS OS CONTAINERS
docker ps

## LISTAR TODOS OS CONTAINERS DE FORMA RESUMIDA
docker ps -aq

## LISTAR TODAS AS IMAGENS
docker image ls

## LISTAR TODAS AS IMAGENS DE FORMA RESUMIDA
docker image ls -q

## REMOVER TODOS OS CONTAINERS DA LISTA
docker rm $(docker ps -aq)

## REMOVER TODAS AS IMAGENS DA LISTA
docker rmi -f $(docker image ls -q) 

## SUBIR O DOCKER
docker-compose up --build -d 

## DERRUBAR O DOCKER
docker-compose down --remove

```

## License

Todos os direitos reservados a Felipe Alves da Silva (_Wiki Soluções Inteligentes_).
