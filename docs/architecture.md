# Arquitetura da Aplicação

## Visão Geral

O projeto foi desenvolvido utilizando o framework **Symfony 7.4**, seguindo a arquitetura MVC (Model-View-Controller) e utilizando o Doctrine ORM para persistência dos dados.

A aplicação é composta por três entidades principais:

- Livro
- Autor
- Assunto

Além disso, existe um módulo de relatório baseado em uma VIEW SQL do banco de dados.

---

# Organização do Projeto

```
src/

Controller/
Entity/
Form/
Repository/
```

### Controller

Responsável por receber as requisições HTTP, coordenar o fluxo da aplicação e retornar as respostas ao usuário.

Exemplos:

- LivroController
- AutorController
- AssuntoController

---

### Entity

Representa o modelo de domínio da aplicação.

As entidades são mapeadas através do Doctrine ORM utilizando PHP Attributes.

Exemplos:

- Livro
- Autor
- Assunto

---

### Repository

Centraliza o acesso aos dados.

Neste projeto os repositories utilizam Doctrine QueryBuilder para consultas mais elaboradas quando necessário.

Foram documentados exemplos de:

- paginação;
- filtros por título;
- filtros por autor;
- filtros por assunto.

---

### Form

Define os formulários utilizados pela aplicação.

Os formulários são construídos utilizando o componente Symfony Forms, desacoplando a definição dos campos das Views.

Exemplos:

- LivroType
- AutorType
- AssuntoType

---

### Templates

As Views foram desenvolvidas utilizando Twig.

Foi utilizado Bootstrap 5 para construção da interface.

Os templates seguem um layout base compartilhado (`base.html.twig`) e utilizam componentes reutilizáveis (_form e _delete_form).

---

# Banco de Dados

O banco de dados é criado através das Migrations do Doctrine.

Relacionamentos principais:

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

Também foi criada uma VIEW SQL (`vw_relatorio_livros`) responsável pelo relatório agrupado por autor.

---

# Tratamento de Exceções

Os Controllers implementam tratamento específico para:

- registros duplicados (UniqueConstraintViolationException);
- erros de banco de dados (DBALException);
- erros inesperados (Throwable).

Além disso, eventos importantes são registrados através do componente PSR-3 Logger do Symfony.

---

# Interface

A interface foi desenvolvida utilizando:

- Bootstrap 5;
- Bootstrap Icons;
- Inputmask para formatação monetária;
- Modais Bootstrap para confirmação de exclusão.

---

# Decisões de Projeto

Durante o desenvolvimento foram adotadas algumas decisões visando simplicidade e organização:

- utilização do Doctrine ORM para persistência;
- relacionamento Many-to-Many entre Livros, Autores e Assuntos;
- utilização de ON DELETE CASCADE apenas nas tabelas intermediárias;
- utilização de Flash Messages para feedback ao usuário;
- documentação de exemplos de paginação e filtros nos Repositories, sem implementação efetiva por se tratar de um teste técnico.

---

# Estrutura em Camadas

```
Usuário
    │
    ▼
Controller
    │
    ▼
Form
    │
    ▼
Entity
    │
    ▼
Repository
    │
    ▼
Doctrine ORM
    │
    ▼
MySQL
```

Essa separação de responsabilidades facilita a manutenção, evolução e testes da aplicação.