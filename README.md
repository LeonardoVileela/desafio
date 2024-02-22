
# Sistema de Assinatura Eletrônica

Este projeto é um sistema de assinatura eletrônica desenvolvido em PHP 8.2, utilizando a extensão PDO para conexão com o banco de dados MySQL. Destina-se a facilitar o gerenciamento de assinaturas eletrônicas de uma maneira segura e eficiente.

## Requisitos

- PHP 8.2+
- MySQL
- Extensão PDO para PHP
- Servidor web Apache com suporte a `.htaccess` para reescritas de URL

## Configuração do Banco de Dados

O esquema do banco de dados necessário para o funcionamento do projeto está disponível no arquivo `db.sql` na raiz do projeto. Você pode importar este arquivo para sua instância do MySQL para criar a estrutura necessária.

### Conexão com o Banco de Dados

As configurações de conexão com o banco de dados são gerenciadas através de variáveis de ambiente. Você pode encontrar o arquivo de configuração em `src/Infrastructure/Persistence/DatabaseConnection.php`. As variáveis de ambiente necessárias são:

- `HOST`: Endereço do servidor do banco de dados
- `DB`: Nome do banco de dados
- `USER_NAME`: Nome do usuário do banco de dados
- `PASS_NAME`: Senha do usuário do banco de dados
- `charset`: Conjunto de caracteres, recomendado `utf8mb4`

Para ambientes de desenvolvimento, você pode substituir as variáveis de ambiente por valores fixos diretamente no arquivo, conforme o exemplo abaixo:

```php
// Exemplo de configuração direta (não recomendado para produção)
/* $host = '127.0.0.1';
   $db = 'sicredi';
   $user = 'root';
   $pass = '';
   $charset = 'utf8mb4'; */
```

## Configuração da API

O projeto utiliza variáveis de ambiente para configurar o token da API e as URLs base. Estas configurações podem ser encontradas e ajustadas no arquivo `config.php` na raiz do projeto.

```php
const BASE_URL = 'https://nome_dominio.com.br';
const BASE_URL_PUBLIC = 'https://nome_dominio.com.br/src/Infrastructure/Web/Public/';
define("API_TOKEN", getenv("API_TOKEN"));
```

Para desenvolvimento local, modifique as URLs para apontar para `localhost` e ajuste o `API_TOKEN` conforme necessário.

## .htaccess e Rotas

Se você estiver executando o projeto em uma subpasta do servidor web local, será necessário ajustar o arquivo `.htaccess` e as rotas definidas no arquivo `index.php`.

### Ajuste do .htaccess

Modifique a linha `RewriteBase` no arquivo `.htaccess` para corresponder à pasta do seu projeto. Um exemplo está comentado no arquivo:

```apache
# Exemplo para executar o projeto em uma subpasta chamada 'projeto'
# RewriteBase /projeto/
RewriteBase /
```

### Ajuste das Rotas

No arquivo `index.php`, ajuste o caminho das rotas conforme o exemplo abaixo, caso esteja executando o projeto em uma subpasta:

```php
// Ajuste o caminho da rota para a subpasta do projeto
$path = str_replace("/projeto", "", $path);
```

## Instalação

1. Clone o repositório para o seu servidor ou ambiente de desenvolvimento.
2. Importe o arquivo `db.sql` para o seu banco de dados MySQL.
3. Configure as variáveis de ambiente ou ajuste diretamente os arquivos de configuração conforme descrito acima.
4. Se necessário, ajuste o arquivo `.htaccess` e as rotas no `index.php` para refletir sua estrutura de diretórios.
5. Acesse o projeto através do navegador para verificar se a instalação foi bem-sucedida.

## Contribuindo

Contribuições para o projeto são bem-vindas. Por favor, crie um fork do repositório, faça suas alterações e envie um pull request para a avaliação.
