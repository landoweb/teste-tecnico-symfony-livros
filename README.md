# Catálogo de Livros

Projeto desenvolvido em **Symfony 7** como solução para o teste técnico.

O sistema permite o gerenciamento de **Livros**, **Autores** e **Assuntos**, incluindo relacionamento muitos-para-muitos entre as entidades e geração de relatório baseado em uma **VIEW** do banco de dados.

---

# Funcionalidades

- Cadastro de Livros
- Cadastro de Autores
- Cadastro de Assuntos
- Relacionamento N:N entre Livros e Autores
- Relacionamento N:N entre Livros e Assuntos
- Relatório agrupado por Autor (baseado em VIEW do banco de dados)
- Interface desenvolvida utilizando Bootstrap 5

---

# Tecnologias Utilizadas

- PHP 8.4
- Symfony 7.4
- Doctrine ORM
- Doctrine Migrations
- Twig
- Bootstrap 5
- MySQL 8
- PHPUnit

---

# Requisitos

- PHP 8.4 ou superior
- Composer
- MySQL 8+
- Git

---

# Instalação

## Clonar o projeto

```bash
git clone https://github.com/landoweb/teste-tecnico-symfony-livros.git
```

Entre na pasta do projeto.

```bash
cd teste-tecnico-symfony-livros
```

---

## Instalar as dependências

```bash
composer install
```

---

## Configurar o banco

Editar o arquivo:

```
.env
```

Alterando principalmente:

```
DATABASE_URL=
```

---

## Criar o banco

```bash
php bin/console doctrine:database:create
```

---

## Executar as migrations

```bash
php bin/console doctrine:migrations:migrate
```

Este comando cria automaticamente:

- tabelas
- relacionamentos
- VIEW utilizada pelo relatório

---

## Executar o projeto

Symfony CLI

```bash
symfony server:start
```

ou utilizando o servidor embutido do PHP

```bash
php -S localhost:8000 -t public
```

---

# Estrutura do Projeto

```
assets/
bin/
config/
docs/
migrations/
public/
src/
templates/
tests/
var/
vendor/
```

---

# Estrutura da Aplicação

```
src/

Controller/
Entity/
Form/
Repository/
```

### Controllers

Responsáveis por receber as requisições HTTP e renderizar as páginas.

### Entities

Representam o modelo de domínio da aplicação e são mapeadas pelo Doctrine ORM.

### Repository

Responsáveis pelas consultas ao banco de dados.

### Form

Definem os formulários utilizados para cadastro e edição das entidades.

### Templates

Views desenvolvidas utilizando Twig.

---

# Banco de Dados

O banco de dados é criado integralmente através das migrations do Doctrine.

As principais entidades são:

- Livro
- Autor
- Assunto

Relacionamentos:

```
Livro
    |
    | N:N
    |
Autor

Livro
    |
    | N:N
    |
Assunto
```

---

# Relatório

O relatório utiliza uma VIEW do banco de dados:

```
vw_relatorio_livros
```

A VIEW retorna:

- Autor
- Livro
- Editora
- Edição
- Ano de publicação
- Valor
- Assuntos

permitindo o agrupamento das informações por autor.

O script SQL encontra-se em:

```
docs/database/view_relatorio_livros.sql
```

---

# Testes

Para executar os testes:

```bash
php bin/phpunit
```

---

# Diferenciais Implementados

- Doctrine ORM
- Doctrine Migrations
- Bootstrap 5
- Relacionamentos Many-to-Many
- View SQL para relatório
- Organização em camadas (Controller, Entity, Repository e Form)
- PHPUnit

---

# Melhorias Futuras

- Exportação do relatório em PDF
- Pesquisa por título
- Paginação
- Ordenação dinâmica
- Autenticação de usuários

---

# Autor

Orlando Stefanin Epifânio

Desenvolvido como solução para o teste técnico utilizando Symfony 7.