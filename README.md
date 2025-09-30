# 📦 Controle de Estoque - Uma Aplicação Web Robusta com Laravel


![Status](https://img.shields.io/badge/status-Projeto%20em%20constru%C3%A7%C3%A3o-orange?style=for-the-badge)

<p align="center">
  <img src="resources/img/tela-dashboard-principal.png" alt="Tela Dashboard Inicial" width="800"/>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-%23777BB4?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/Laravel-12.x-%23FF2D20?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/Alpine.js-3.x-%2377C1D2?style=for-the-badge&logo=almalinux" alt="Alpine.js">
  <img src="https://img.shields.io/badge/PostgreSQL-14%2B-blue?style=for-the-badge&logo=postgresql" alt="PostgreSQL">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-%2306B6D4?style=for-the-badge&logo=tailwindcss" alt="Tailwind CSS">
</p>

## 🎯 Sobre o Projeto

Este projeto é uma aplicação web completa e profissional para **Gestão de Estoque**, desenvolvida do zero com **PHP 8**, **Laravel**, **PostgreSQL** e **TailwindCSS**, aplicando arquitetura **MVC** e boas práticas de mercado. O objetivo foi criar uma solução que não apenas realizasse as operações básicas de um CRUD, mas que também incorporasse as melhores práticas de desenvolvimento, uma arquitetura escalável e funcionalidades de inteligência de negócio para auxiliar na tomada de decisões.

A jornada de criação deste sistema serviu como um profundo campo de estudo e prática, solidificando conceitos de **backend**, **frontend moderno**, **arquitetura de software** e **gestão de banco de dados**.

---

## 🚀 Minha Jornada e Aprendizados

Eu decidi criar este projeto para ir além dos tutoriais básicos e enfrentar os desafios de construir uma aplicação do mundo real. O meu objetivo era claro: dominar o fluxo de trabalho profissional com Laravel e criar um sistema robusto, digno de um portfólio, que demonstrasse competências procuradas pelo mercado.

Durante o desenvolvimento, pratiquei e aprofundei os meus conhecimentos em:
- **Arquitetura MVC:** Organização de código em Models, Views e Controllers.
- **Eloquent ORM:** Relacionamentos complexos (One-to-Many, Many-to-Many), Soft Deletes e Accessors para manipulação de dados.
- **Refatoração de Código:** Migração de uma lógica simples de stock para um sistema avançado de **Lotes e Variações**, demonstrando a capacidade de evoluir a arquitetura do sistema para requisitos de negócio mais complexos.
- **Desenvolvimento de API:** Construção de uma API RESTful segura com **Laravel Sanctum**, pronta para integrações futuras com aplicativos mobile e e-commerce.
- **Frontend Interativo:** Utilização de **JavaScript puro (AJAX/Fetch)** e **Alpine.js** para criar dashboards e formulários dinâmicos que se comunicam com o backend sem recarregar a página.
- **Segurança:** Implementação de permissões de acesso baseadas em papéis (Admin/Operador) com os **Gates** do Laravel, protegendo rotas e elementos da interface.
- **Tarefas Agendadas e Notificações:** Criação de comandos Artisan e automação de alertas de stock baixo por e-mail, utilizando o **Laravel Scheduler**.

Um dos maiores **desafios** foi a depuração de bugs de continuidade que surgiram após grandes refatorações. A persistência em analisar logs, inspecionar o banco de dados e entender a fundo a interação entre o backend e o frontend foi um aprendizado imenso e fundamental.

---

## ✨ Funcionalidades Implementadas

O sistema conta com um ecossistema completo de funcionalidades para uma gestão de stock de nível profissional.

### 🏛 Arquitetura do Sistema

```mermaid
flowchart TD
    subgraph "Interface Web (Navegador)"
        U[Usuário] --> W[Rotas Web]
        W --> C[Controllers Web]
        C --> V[Views Blade<br/>Tailwind + Alpine.js]
        C <--> L[Lógica de Negócio / Models]
    end
    
    subgraph "Integrações Externas"
        EXT[App Mobile / E-commerce] --> API[Rotas API<br/>/api/*]
        API --> AC[API Controllers]
        AC <--> L
    end

    subgraph "Backend (Servidor)"
        L <--> DB[(PostgreSQL)]
        L --> J[Jobs & Scheduler<br/>Alertas por E-mail]
    end
```

### 📦 Gestão de Produtos e Variações
Cadastro de produtos com um sistema robusto de variações (SKUs), permitindo que cada combinação de atributos (ex: Cor, Tamanho), tenha seu próprio preço, stock mínimo e histórico, mais impressão de etiquetas dos produtos.

### 📈 Controle de Lotes e Validade
Toda a entrada de stock é gerida por lotes com data de validade. As saídas seguem a lógica **FEFO (First-Expire, First-Out)**, garantindo a rotação inteligente do stock e minimizando perdas.

### 🚚 Movimentação e Rastreabilidade
Interface otimizada para registo de entradas e saídas, com suporte a **leitores de código de barras** e associação a **Clientes** e **Fornecedores**, garantindo um histórico de movimentações 100% auditável.

### 📊 Dashboards e Relatórios
- **Dashboard de BI:** Uma visão centralizada com KPIs, tendências, gráficos clicáveis (drill-down) e listas de ação (alertas de stock baixo, produtos parados).
- **Dashboard de Vendas Interativo:** Ferramenta de análise de vendas com busca dinâmica de produtos e atualização de gráficos em tempo real via AJAX.
- **Relatório de Histórico:** Consulta detalhada de todas as movimentações, com filtros avançados.

### 🤖 Automação e Alertas
Sistema proativo que monitoriza o stock e envia **notificações automáticas por e-mail** para os administradores quando um item atinge o seu nível mínimo.

### 🔐 Segurança
- **CSRF e XSS Protection** nativos do Laravel.  
- **Autenticação Sanctum** para API.  
- **Controle de acesso baseado em papéis** (Admin/Operador).  
- **Logs de auditoria** para cada movimentação de estoque.  
- **Rate limiting** em endpoints sensíveis.  

### 🔑 Sessões e Autenticação
- **Login/Logout seguro** com hashing de senhas via **bcrypt**.  
- **Sessões criptografadas** em PostgreSQL/Redis.  
- **Expiração automática de sessão** configurável. 

---

## 🎥 Demonstração das Telas do Projeto

### 🔐 Autenticação
Fluxo de login, registro e atribuição automática de papel de usuário.  
![Login](resources/gifs/login.gif) ---**EM BREVE**---

### 📊 Dashboard
Visão geral do estoque, KPIs e gráficos interativos.  
![Dashboard](resources/gifs/dashboard.gif) ---**EM BREVE**---

### 📦 Gestão de Produtos e Variações
Cadastro, edição, exclusão e impressão de etiquetas.  
![Produtos](resources/gifs/produtos.gif) ---**EM BREVE**---

### 📥 Movimentações de Estoque
Entradas e saídas com lógica FEFO e rastreabilidade por lotes.  
![Estoque](resources/gifs/estoque.gif) ---**EM BREVE**---

### 🏢 Gestão de Fornecedores
Gerenciamento completo da base de fornecedores.  
![Fornecedores](resources/gifs/fornecedores.gif) ---**EM BREVE**---

### 👥 Gestão de Clientes
Gerenciamento completo da base de clientes.  
![Clientes](resources/gifs/clientes.gif) ---**EM BREVE**---

### 🔑 Gestão de Usuários e Permissões
Alteração de papéis entre **Administrador** e **Operador**.  
![Usuários](resources/gifs/usuarios.gif) ---**EM BREVE**---

### ⚠️ Cadastros gerais (Categorias, Marcas, Atributos(Gerenciar Valores))
Cadastro, edição e exclusão de dados.  
![Cadastros](resources/gifs/alertas.gif) ---**EM BREVE**---

---

## 🛠️ Ferramentas e Tecnologias

| Camada | Tecnologia |
|-------|------------|
| Backend | PHP 8.x, Laravel 12.x |
| Banco de Dados | PostgreSQL 14+ |
| Frontend | Blade, TailwindCSS, Alpine.js, Chart.js |
| API | Laravel Sanctum |
| Desenvolvimento | VSCode, DBeaver, Postman |
| Testes de E-mail | Mailtrap.io |

---

## 🚀 Instalação e Configuração

Este guia detalhado irá ajudá-lo a configurar o ambiente e a executar o projeto localmente.

### Pré-requisitos
Antes de começar, garanta que tem as seguintes ferramentas instaladas e a funcionar:
- **Git:** ([Download](https://git-scm.com/downloads))
- **PHP 8.2+:** ([Download para Windows](https://windows.php.net/download/))
- **Composer:** ([Download](https://getcomposer.org/download/))
- **Node.js e NPM:** ([Download](https://nodejs.org/))
- **PostgreSQL:** ([Download](https://www.postgresql.org/download/))
- **Um cliente de banco de dados:** (ex: **DBeaver** ou similar).

> **⚠️ Importante: Configuração do PHP**
> Após instalar o PHP, você precisa de editar o arquivo de configuração `php.ini`. Garanta que as seguintes extensões estão ativadas (removendo o `;` do início da linha):
> ```ini
> extension=gd
> extension=pgsql
> extension=pdo_pgsql
> ```

### **Passo 1: Preparar o Banco de Dados**

1. Abra o seu **cliente de banco de dados** (ex.: **DBeaver**, PgAdmin ou outro de sua preferência).
2. Crie uma **nova base de dados** para o projeto com as seguintes configurações recomendadas:

   * **Nome:** `controle_estoque_db` *(ou outro de sua preferência)*
   * **Codificação (Encoding):** `UTF8`
   * **Collation/Ordenação:** `pt_BR.UTF-8` *(ou utilize o padrão do seu sistema caso não esteja disponível)*

> 💡 **Dica:** No DBeaver, clique com o botão direito sobre a conexão ➜ **Create ➜ Connection**, informe os parâmetros acima e confirme. Dependendo da versão o caminho pode mudar..

### Passo 2: Obter o Código
1.  Navegue no seu terminal para a pasta onde deseja guardar o projeto.
2.  Clone o repositório do GitHub:
    ```bash
    git clone [https://github.com/seu-usuario/seu-repositorio.git](https://github.com/seu-usuario/seu-repositorio.git)
    ```
3.  Entre na pasta do projeto:
    ```bash
    cd seu-repositorio
    ```

### Passo 3: Instalar as Dependências
1.  Instale as dependências do PHP (Laravel, etc.):
    ```bash
    composer install
    ```
2.  Instale as dependências do JavaScript (Tailwind, Alpine, etc.):
    ```bash
    npm install
    ```

### Passo 4: Configurar o Ambiente (`.env`)
1.  Copie o arquivo de ambiente de exemplo. No terminal, execute:
    ```bash
    cp .env.example .env
    ```
2.  Gere a chave de encriptação única para a sua aplicação:
    ```bash
    php artisan key:generate
    ```
3.  **Abra o arquivo `.env`** no seu editor de código (VSCode) e edite as seguintes secções:

    **Configuração do Banco de Dados:**
    ```env
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=controle_estoque_db # O nome que você criou no Passo 1
    DB_USERNAME=seu_usuario_db      # O seu usuário do PostgreSQL
    DB_PASSWORD=sua_senha_db        # A sua senha do PostgreSQL
    ```

    **Configuração do Servidor de E-mail (Mailtrap):**
    - Crie uma conta gratuita em [Mailtrap.io](https://mailtrap.io) para capturar os e-mails de teste.
    - Copie as suas credenciais SMTP e cole-as aqui:
    ```env
    MAIL_MAILER=smtp
    MAIL_HOST=sandbox.smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=seu_username_mailtrap
    MAIL_PASSWORD=sua_password_mailtrap
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS="alertas@meusistema.com"
    MAIL_FROM_NAME="${APP_NAME}"
    ```

### Passo 5: Construir o Banco de Dados e o Link de Armazenamento
1.  **Execute as Migrations:** Este comando irá ler todos os arquivos de migration e construir a estrutura de tabelas no seu banco de dados.
    ```bash
    php artisan migrate
    ```
2.  **Crie o Link Simbólico:** Este comando torna a pasta de uploads (onde as fotos dos produtos são guardadas) publicamente acessível.
    ```bash
    php artisan storage:link
    ```

### Passo 6: Iniciar a Aplicação
Finalmente, vamos iniciar os servidores de desenvolvimento. Você precisará de **dois terminais abertos**.

* **No Terminal 1**, inicie o Vite para compilar os assets de frontend:
    ```bash
    npm run dev
    ```
* **No Terminal 2**, inicie o servidor principal do Laravel:
    ```bash
    php artisan serve
    ```
A aplicação estará agora disponível em `http://127.0.0.1:8000`.

---

## 👨‍💻 Como Utilizar

### Criando o Primeiro Usuário (Administrador)

O sistema foi projetado para ser auto-configurável na primeira utilização.

1.  **Acesse à aplicação** no seu navegador (`http://127.0.0.1:8000`). Você será redirecionado para a página de login.
2.  Clique no link **"Cadastrar"**.
3.  Preencha os seus dados para criar a sua conta.
4.  **Parabéns!** Como você é o primeiro usuário a ser registado, o sistema atribuiu-lhe automaticamente a função de **Administrador (`admin`)**. Isto dá-lhe acesso a todas as áreas, incluindo a "Gestão de Usuários".

Qualquer outra pessoa que se cadastrar a partir de agora será um "Operador" por padrão. Você pode promover outros usuários a `admin` na página "Usuários".

### Populando o Banco com Dados de Teste (Opcional, mas Recomendado)
Se desejar testar a aplicação com um grande volume de dados (produtos, vendas, etc.), você pode executar o "Seeder".

> **Atenção:** Execute este comando **depois** de já ter criado o seu usuário admin.
```bash
php artisan db:seed
```

> **Atenção:** Isto irá popular o banco de dados com dezenas de produtos, categorias, clientes, fornecedores e movimentações de teste, permitindo-lhe explorar os dashboards e relatórios em condições realistas.

### Documentação dos Endpoints da API

A API foi projetada para ser a ponte entre o núcleo de negócio e o mundo exterior, como um futuro aplicativo mobile ou uma integração com e-commerce. Ela permite que sistemas externos interajam com os dados de forma segura e programática.

Todos os endpoints protegidos requerem autenticação via **Bearer Token** e o envio do cabeçalho **`Accept: application/json`**.

---
#### **Autenticação**

##### Obter Token de Acesso
* **Endpoint:** `POST /api/login`
* **Descrição:** Autentica um usuários com email e senha e retorna um token de acesso Sanctum. Este token deve ser guardado pela aplicação cliente e enviado em todas as requisições subsequentes.
* **Corpo da Requisição (`x-www-form-urlencoded`):**
    * `email` (string, obrigatório): O email do usuários.
    * `password` (string, obrigatório): A senha do usuários.
* **Resposta de Sucesso (200 OK):**
    ```json
    {
        "access_token": "4|aBcDeFgHiJKLmnoPqRsTuvWxyz...",
        "token_type": "Bearer"
    }
    ```

---
#### **Produtos**

##### Listar Produtos
* **Endpoint:** `GET /api/produtos`
* **Descrição:** Retorna uma lista paginada de todos os produtos base.

##### Criar um Novo Produto
* **Endpoint:** `POST /api/produtos`
* **Descrição:** Cria um novo registo de produto base.
* **Corpo da Requisição (`x-www-form-urlencoded`):**
    * `nome` (string, obrigatório)
    * `categoria_id` (integer, obrigatório)
    * `marca_id` (integer, obrigatório)
    * `descricao` (string, opcional)
    * `fornecedor_id` (integer, opcional)
    * `codigo_barras` (string, opcional, único)

##### Ver Detalhes de um Produto
* **Endpoint:** `GET /api/produtos/{id}`
* **Descrição:** Retorna os detalhes completos de um único produto, incluindo as suas variações.

##### Atualizar um Produto
* **Endpoint:** `PUT /api/produtos/{id}`
* **Descrição:** Atualiza os dados de um produto base existente.

##### Apagar um Produto
* **Endpoint:** `DELETE /api/produtos/{id}`
* **Descrição:** Realiza um "Soft Delete" no produto, arquivando-o sem perder o histórico.

---
#### **Variações de Produto**

##### Criar uma Nova Variação
* **Endpoint:** `POST /api/produtos/{id}/variations`
* **Descrição:** Cria uma nova variação (SKU) associada a um produto existente.
* **Corpo da Requisição (`x-www-form-urlencoded`):**
    * `sku` (string, obrigatório, único)
    * `preco_venda` (numeric, obrigatório)
    * `preco_custo` (numeric, opcional)
    * `estoque_minimo` (integer, opcional)
    * `attribute_values[]` (array, obrigatório): Um array de IDs dos `valor_atributos` (ex: `attribute_values[]=1&attribute_values[]=6`).

##### Atualizar uma Variação
* **Endpoint:** `PUT /api/variations/{id}`
* **Descrição:** Atualiza os detalhes (SKU, preço, etc.) de uma variação existente.

##### Apagar uma Variação
* **Endpoint:** `DELETE /api/variations/{id}`
* **Descrição:** Realiza um "Soft Delete" na variação.

---
#### **Movimentações de Estoque**

##### Registrar uma Nova Movimentação
* **Endpoint:** `POST /api/movimentacoes`
* **Descrição:** O endpoint mais importante para integrações. Regista uma nova entrada ou saída, aplicando automaticamente a lógica de negócio (ex: FEFO para saídas).
* **Corpo da Requisição (`x-www-form-urlencoded`):**
    * `product_variation_id` (integer, obrigatório)
    * `tipo` (string, obrigatório): `entrada` ou `saida`.
    * `quantidade` (integer, obrigatório)
    * `motivo` (string, opcional)
    * `fornecedor_id` (integer, opcional, usado para `entrada`)
    * `cliente_id` (integer, opcional, usado para `saida`)
    * `lote` (string, opcional, usado para `entrada`)
    * `data_validade` (date, opcional, formato `YYYY-MM-DD`, usado para `entrada`)

---
#### **Busca**

##### Buscar Produto por Código
* **Endpoint:** `GET /api/search-by-code/{code}`
* **Descrição:** Endpoint otimizado para aplicações mobile. Procura uma variação pelo seu `SKU` ou pelo `código_barras` do produto principal.
* **Resposta de Sucesso (200 OK):** Um recurso completo da variação encontrada, incluindo detalhes dos lotes.
* **Resposta de Falha (404 Not Found):**
    ```json
    {
        "message": "Nenhum produto ou variação encontrado com este código."
    }
    ```

---
#### **Dados Mestres (Apenas Leitura)**

* `GET /api/categorias`
* `GET /api/marcas`
* `GET /api/clientes`
* `GET /api/fornecedores`

---

## 👩‍🎓 Autoria

<img src="https://github.com/angelluzk.png" width="100px;" alt="Foto de Angel Luz"/>

> Desenvolvido com 💛 por **Angel Luz**.

Se quiser conversar, colaborar ou oferecer uma oportunidade:

📬 E-mail: [contatoangelluz@gmail.com](mailto:contatoangelluz@gmail.com)  
🐙 GitHub: [@angelluzk](https://github.com/angelluzk)  
💼 LinkedIn: [linkedin.com/in/angelitaluz](https://www.linkedin.com/in/angelitaluz/)  
🗂️Website / Portfólio: [meu_portfolio/](https://angelluzk.github.io/meu_portfolio/) 

---