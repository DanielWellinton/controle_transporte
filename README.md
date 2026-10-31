# Controle de Transporte - Gestão Municipal

Este é o sistema de **Controle de Transporte Escolar**, uma plataforma digital segura e organizada para consultar rotas, selecionar pontos de embarque/desembarque e validar acessos via QR Code.

Este documento contém as instruções para rodar o projeto em um ambiente de desenvolvimento. Você pode escolher entre duas formas de subir a aplicação: usando **Docker (Recomendado)** ou configurando o **Ambiente Local Manualmente**.

## 🐳 Opção 1: Rodando com Docker (Recomendado)

O uso do Docker evita a necessidade de instalar PHP, Composer, Node e MySQL diretamente na sua máquina.

### Pré-requisitos

* **Docker Desktop** instalado e rodando.
* **Git** (opcional, para clonar o repositório).

### Passo a Passo (Docker)

1. **Preparar as variáveis de ambiente:**
   No terminal, faça uma cópia do arquivo de configuração:

   ```bash
   cp .env.example .env
   ```
   *(No Windows, use: `copy .env.example .env`)*

2. **Instalar Dependências Iniciais (Composer):**
   Execute o comando abaixo para baixar a pasta `vendor` usando um container temporário:

   ```bash
   docker run --rm -v ${PWD}:/app -e COMPOSER_PROCESS_TIMEOUT=3600 composer install --ignore-platform-reqs
   ```

3. **Subir a Infraestrutura:**
   Inicie os containers em segundo plano:

   ```bash
   docker compose up -d --build
   ```

4. **Configurar o Laravel e o Banco de Dados:**
   Gere a chave da aplicação e rode as migrações/seeders:

   ```bash
   docker compose exec laravel.test php artisan key:generate
   docker compose exec laravel.test php artisan migrate --seed
   ```

5. **Compilar o Front-end (Vite):**
   Instale os pacotes do Node.js e inicie o servidor front-end (deixe este comando rodando no terminal):

   ```bash
   docker compose exec laravel.test npm install
   docker compose exec laravel.test npm run dev -- --host
   ```

6. **Acessar a Aplicação:**
   * **Pelo Computador:** Acesse `http://127.0.0.1` no seu navegador.
   * **Pelo Celular (na mesma rede Wi-Fi):** Descubra o IP do seu PC (comando `ipconfig` no Windows) e acesse `http://192.168.X.X`.

---

## 💻 Opção 2: Instalação Manual (Ambiente Local)

Caso prefira rodar sem o Docker, você precisará configurar o ambiente diretamente no seu sistema operacional.

### Pré-requisitos

* **PHP** (versão 8.2 ou superior).
* **Composer**.
* **Node.js** e **NPM**.
* **MySQL**.

### Passo a Passo (Manual)

1. **Configurar o PHP:**
   Vá até a pasta onde o PHP foi instalado, encontre o arquivo `php.ini` e abra-o em um editor de texto.
   Procure pelas seguintes linhas e remova o ponto e vírgula (`;`) do início para ativá-las. Em seguida, salve o arquivo:

   ```ini
   extension_dir = "ext"
   extension=pdo_mysql
   extension=mysqli
   extension=fileinfo
   extension=gd
   ```

2. **Instalar Dependências (Composer):**
   No terminal, na raiz do projeto, execute:

   ```bash
   composer install
   ```

3. **Criar o Banco de Dados:**
   Abra seu gerenciador do MySQL ou o terminal MySQL e execute o comando:

   ```sql
   CREATE DATABASE controle_transporte;
   ```

4. **Preparar as variáveis de ambiente:**
   No terminal, copie o arquivo de configuração:

   ```bash
   cp .env.example .env
   ```
   *(No Windows, use: `copy .env.example .env`)*

5. **Gerar a Chave da Aplicação:**

   ```bash
   php artisan key:generate
   ```

6. **Configurar o Banco de Dados no `.env`:**
   Abra o arquivo `.env` e atualize a seção de banco de dados com as suas credenciais locais:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=controle_transporte
   DB_USERNAME=root
   DB_PASSWORD=coloque_sua_senha_aqui
   ```

7. **Rodar Migrations e Seeders:**
   Construa as tabelas e povoe o banco de dados:

   ```bash
   php artisan migrate --seed
   ```

8. **Compilar o Front-end:**
   Instale os pacotes e construa os arquivos finais:

   ```bash
   npm install
   npm run build
   ```

9. **Rodar o Servidor Local:**
   Inicie o servidor embutido do PHP:

   ```bash
   php artisan serve
   ```
   Acesse a aplicação no navegador através do link gerado no terminal (geralmente `http://localhost:8000`).