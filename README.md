# Controle de Estoque - Uma Aplicação Web Robusta com Laravel

![Status](https://img.shields.io/badge/status-Projeto%20em%20constru%C3%A7%C3%A3o-orange?style=for-the-badge)

![Capa do Projeto - Visualização das Telas em Breve]()

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2%2B-%23777BB4?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/Laravel-11.x-%23FF2D20?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/Alpine.js-3.x-%2377C1D2?style=for-the-badge&logo=almalinux" alt="Alpine.js">
  <img src="https://img.shields.io/badge/PostgreSQL-14%2B-blue?style=for-the-badge&logo=postgresql" alt="PostgreSQL">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-%2306B6D4?style=for-the-badge&logo=tailwindcss" alt="Tailwind CSS">
</p>

## 🎯 Sobre o Projeto

Este projeto é uma aplicação web completa e profissional para **Gestão de Estoque**, desenvolvida do zero com o ecossistema Laravel. O objetivo foi criar uma solução que não apenas realizasse as operações básicas de um CRUD, mas que também incorporasse as melhores práticas de desenvolvimento, uma arquitetura escalável e funcionalidades de inteligência de negócio (BI) para auxiliar na tomada de decisões.

A jornada de criação deste sistema serviu como um profundo campo de estudo e prática, solidificando conceitos de backend, frontend moderno, arquitetura de software e gestão de banco de dados.

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

### 📦 Gestão de Produtos e Variações
Cadastro de produtos com um sistema robusto de variações (SKUs), permitindo que cada combinação de atributos (ex: Cor, Tamanho) tenha seu próprio preço, stock mínimo e histórico.

![Print da Tela de Gestão de Produto - Visualização das Telas em Breve]()

### 📈 Controle de Lotes e Validade
Toda a entrada de stock é gerida por lotes com data de validade. As saídas seguem a lógica **FEFO (First-Expire, First-Out)**, garantindo a rotação inteligente do stock e minimizando perdas.

### 🚚 Movimentação e Rastreabilidade
Interface otimizada para registo de entradas e saídas, com suporte a **leitores de código de barras** e associação a **Clientes** e **Fornecedores**, garantindo um histórico de movimentações 100% auditável.

![Print da Tela de Movimentação - Visualização das Telas em Breve]()

### 📊 Dashboards e Relatórios
- **Dashboard de BI:** Uma visão centralizada com KPIs, tendências, gráficos clicáveis (drill-down) e listas de ação (alertas de stock baixo, produtos parados).
- **Dashboard de Vendas Interativo:** Ferramenta de análise de vendas com busca dinâmica de produtos e atualização de gráficos em tempo real via AJAX.
- **Relatório de Histórico:** Consulta detalhada de todas as movimentações, com filtros avançados.

### 🤖 Automação e Alertas
Sistema proativo que monitoriza o stock e envia **notificações automáticas por e-mail** para os administradores quando um item atinge o seu nível mínimo.

---

## 🛠️ Ferramentas e Tecnologias

- **Backend:** PHP v8.4.12, Laravel v12.28.1
- **Frontend:** Tailwind CSS, Alpine.js, Chart.js
- **Banco de Dados:** PostgreSQL
- **Ferramentas de Desenvolvimento:**
  - **VSCode:** Editor de código principal.
  - **DBeaver:** Ferramenta de gestão de banco de dados.
  - **Postman:** Para testes e depuração da API.
  - **Git & GitHub:** Para controlo de versão e alojamento do código.
- **Servidor de Desenvolvimento:** Vite
- **Autenticação de API:** Laravel Sanctum
- **Testes de E-mail:** Mailtrap.io

---

## 🚀 Instalação e Configuração

Este guia detalhado irá ajudá-lo a configurar o ambiente e a executar o projeto localmente.

### Pré-requisitos
Antes de começar, garanta que tem as seguintes ferramentas instaladas e a funcionar:
- **PHP 8.2+:** Verifique a sua versão com `php -v`.
- **Composer:** Gestor de dependências para o PHP. Verifique com `composer --version`.
- **Node.js e NPM:** Para compilação de assets de frontend. Verifique com `node -v` e `npm -v`.
- **PostgreSQL:** O nosso banco de dados.
- **Git:** Para clonar o projeto.
- **Um cliente de banco de dados (Recomendado):** **DBeaver** ou similar.

### **Passo 1: Preparar o Banco de Dados**

1. Abra o seu **cliente de banco de dados** (ex.: **DBeaver**, PgAdmin ou outro de sua preferência).
2. Crie uma **nova base de dados** para o projeto com as seguintes configurações recomendadas:

   * **Nome:** `controle_estoque_db` *(ou outro de sua preferência)*
   * **Codificação (Encoding):** `UTF8`
   * **Collation/Ordenação:** `pt_BR.UTF-8` *(ou utilize o padrão do seu sistema caso não esteja disponível)*

> 💡 **Dica:** No DBeaver, clique com o botão direito sobre a conexão ➜ **Criar ➜ Banco de Dados**, informe os parâmetros acima e confirme.

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
    DB_USERNAME=seu_usuario_db      # O seu utilizador do PostgreSQL
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

### Criando o Primeiro Utilizador (Administrador)

O sistema foi projetado para ser auto-configurável na primeira utilização.

1.  **Aceda à aplicação** no seu navegador (`http://127.0.0.1:8000`). Você será redirecionado para a página de login.
2.  Clique no link **"Registar"**.
3.  Preencha os seus dados para criar a sua conta.
4.  **Parabéns!** Como você é o primeiro utilizador a ser registado, o sistema atribuiu-lhe automaticamente a função de **Administrador (`admin`)**. Isto dá-lhe acesso a todas as áreas, incluindo a "Gestão de Utilizadores".

Qualquer outra pessoa que se registar a partir de agora será um "Operador" por padrão. Você pode promover outros utilizadores a `admin` na página "Utilizadores".

### Populando o Banco com Dados de Teste (Opcional, mas Recomendado)
Se desejar testar a aplicação com um grande volume de dados (produtos, vendas, etc.), você pode executar o "Seeder".

> **Atenção:** Execute este comando **depois** de já ter criado o seu utilizador admin.
```bash
php artisan db:seed
```

> **Atenção:** Isto irá popular o banco de dados com dezenas de produtos, categorias, clientes, fornecedores e movimentações de teste, permitindo-lhe explorar os dashboards e relatórios em condições realistas.

### Testando a API com o Postman
A API é a porta de entrada para futuras integrações. Para a testar, siga este fluxo:

1.  **Obtenha um Token de Acesso:**
    * Crie uma requisição `POST` para a URL: `http://127.0.0.1:8000/api/login`.
    * Na aba "Body", selecione `x-www-form-urlencoded`.
    * Adicione as chaves `email` e `password` com os dados do seu utilizador admin.
    * Envie a requisição. A resposta será um JSON. Copie o valor da chave `access_token`.

2.  **Aceda a um Endpoint Protegido:**
    * Crie uma nova requisição, por exemplo, `GET` para `http://127.0.0.1:8000/api/produtos`.
    * Vá para a aba "Authorization".
    * Selecione o tipo "Bearer Token".
    * No campo "Token", cole a chave que você copiou.
    * Vá para a aba "Headers".
    * Adicione um novo cabeçalho: `Key: Accept`, `Value: application/json`.
    * Envie a requisição. Você deverá receber a lista de produtos em formato JSON.

---

## 🗺️ Roadmap (Próximos Passos)

- [ ] **Completar a API** com endpoints de escrita para Produtos, Variações, Clientes, etc.
- [ ] Iniciar o desenvolvimento do **Aplicativo Mobile**.
- [ ] Implementar **Faturamento & Emissão Fiscal**.

---

## 📄 Licença

Distribuído sob a Licença MIT.

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