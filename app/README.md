# Front-end User (CRUD)

Front-end criado para testar a API Rest User

## Instalação

A aplicação requer que o [Docker](https://www.docker.com/) esteja instalado para que seja possível levantar o ambiente.

Faça o clone do repositório, e entre na raiz da pasta:

```sh
git clone https://github.com/wikisolucoes/api-rest-user.git
cd api-rest-user/app/
```

Criar o arquivo _.env_ a partir do arquivo _.env.example_:

```sh
cp .env.example .env
```

_Obs: Edite o arquivo .env para definir a URL da API Rest User._

Levantar o ambiente utilizando o Docker:

```sh
docker-compose up -d 
```

Verificar se todos os containers subiram corretamente:

```sh
docker ps
```

Deverá listar todos os containers conforme o exemplo abaixo:

```sh
CONTAINER ID   IMAGE          COMMAND                  CREATED         STATUS         PORTS                         
65d78e58ffea   app-vite-app   "docker-entrypoint.s…"   7 seconds ago   Up 6 seconds    0.0.0.0:5173->5173/tcp  
```

Pronto!
A configuração inicial para executar o projeto está finalizada!

> Acesse pelo navegador: `http://localhost:5173`

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
