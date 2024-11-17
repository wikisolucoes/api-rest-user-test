# API Rest User + Front-end User (CRUD)
API Rest base para gestão de usuários + Front-end criado para testar a API

- Para instalar a __API__ leia as [instruções de instalação](api/README.md) incluida no repositório.
- Para instalar o __Fron End__ leia as [instruções de instalação](app/README.md) incluida no repositório.

## Segurança
- Para evitar SQL Injection, é essencial usar prepared statements ou query builders que protejam automaticamente contra inserções maliciosas. Na API foi utilizado o framework Laravel, que oferece Query Builder e Eloquent ORM, que automaticamente escapam parâmetros e evitam a injeção de SQL.
- Para prevenir XSS, é importante garantir que qualquer dado vindo do usuário (seja em URL, corpo da requisição ou cabeçalhos) seja escapado antes de ser exibido no navegador. Isso garante que scripts maliciosos não sejam executados. Na API foi utilizado o framework Laravel, que já faz o escape automático de dados em suas respostas
- Para proteger contra CSRF, é necessário usar tokens CSRF para todas as requisições que modificam o estado da aplicação (POST, PUT, DELETE). Na API foi utilizado o framework Laravel, que já gera automaticamente um token CSRF para todas as sessões de usuários autenticados. O Laravel tem um middleware CSRF que verifica automaticamente a presença e validade do token CSRF em todas as requisições.

## Design Pattern
- O design pattern utilizado para gerenciar as dependências da API criada foi o Dependency Injection (DI), nativamente suportado pelo Laravel através do Service Container. Esse padrão foi escolhido porque promove um código mais desacoplado, facilitando testes unitários, manutenção e substituição de implementações, além de permitir que as dependências sejam resolvidas automaticamente pelo framework, simplificando o desenvolvimento.

## Arquitetura
Para escalar a API para milhões de usuários, adotaria uma arquitetura de micro serviços com foco em separação de responsabilidades e alta disponibilidade:
- Micro Serviços: Dividir a aplicação em serviços independentes (ex.: autenticação, pagamentos), cada um com banco de dados próprio e escalabilidade individual.
- Orquestração com Kubernetes: Gerenciar os containers Docker com escalabilidade automática e tolerância a falhas.
- API Gateway: Centralizar autenticação e roteamento, implementando rate limiting para proteção.
- Banco de Dados e Cache: Usar replicação e particionamento em MySQL, com Redis para cache e filas assíncronas.
- Alta Disponibilidade: Load balancer para distribuir tráfego, infraestrutura multirregional para redundância e backups automatizados.
- Monitoramento: Ferramentas como Prometheus e Grafana para métricas e alertas.

Essa arquitetura garante escalabilidade e desempenho, suportando o crescimento da aplicação.

## License

Todos os direitos reservados a Felipe Alves da Silva (_Wiki Soluções Inteligentes_).
