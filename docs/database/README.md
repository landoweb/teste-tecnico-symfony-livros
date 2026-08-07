# Banco de Dados

Toda a estrutura do banco é criada automaticamente através das **Doctrine Migrations**.

Isso inclui:

- tabelas;
- relacionamentos;
- índices;
- constraints;
- a VIEW utilizada pelo relatório.

---

# Arquivos

Este diretório contém os seguintes arquivos:

## `catalogo_livros.sql`

Banco de dados já populado com alguns autores, assuntos e livros.

Este arquivo é disponibilizado apenas para facilitar a demonstração da aplicação durante a avaliação do projeto.

Sua utilização é **opcional**, uma vez que toda a estrutura pode ser criada através das migrations.

---

## `view_relatorio_livros.sql`

Script SQL utilizado na criação da VIEW:

```
vw_relatorio_livros
```

A VIEW retorna as seguintes informações:

- Autor
- Livro
- Editora
- Edição
- Ano de publicação
- Valor
- Assuntos

Este arquivo foi mantido para fins de documentação e consulta.

Durante a instalação da aplicação, a VIEW é criada automaticamente pela migration correspondente.

---

# Instalação Recomendada

A forma recomendada de instalação do projeto é:

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

Dessa forma, toda a estrutura do banco de dados será criada automaticamente.

O arquivo `catalogo_livros.sql` deve ser utilizado apenas quando se desejar iniciar a aplicação com dados de exemplo.