# Implantação

Este documento descreve os passos necessários para executar a aplicação localmente.

---

# Requisitos

- PHP 8.4 ou superior
- Composer
- MySQL 8+
- Git

---

# Instalação

## 1. Clonar o repositório

```bash
git clone https://github.com/landoweb/teste-tecnico-symfony-livros.git
```

Entrar na pasta do projeto.

```bash
cd teste-tecnico-symfony-livros
```

---

## 2. Instalar as dependências

```bash
composer install
```

---

## 3. Configurar o ambiente

Editar o arquivo:

```
.env
```

Configurando principalmente a variável:

```
DATABASE_URL=
```

---

## 4. Criar o banco de dados

```bash
php bin/console doctrine:database:create
```

---

## 5. Executar as migrations

```bash
php bin/console doctrine:migrations:migrate
```

Este comando criará automaticamente:

- tabelas
- relacionamentos
- índices
- constraints
- VIEW utilizada pelo relatório

---

## 6. Executar a aplicação

Utilizando o Symfony CLI:

```bash
symfony server:start
```

ou utilizando o servidor embutido do PHP:

```bash
php -S localhost:8000 -t public
```

---

# Banco de Demonstração (Opcional)

Caso deseje apenas testar rapidamente a aplicação, o diretório:

```
docs/database/
```

contém o arquivo:

```
catalogo_livros.sql
```

com alguns registros de exemplo.

A instalação recomendada continua sendo através das migrations do Doctrine.

---

# Validação

Após a implantação, recomenda-se validar se o banco está sincronizado com o mapeamento das entidades:

```bash
php bin/console doctrine:schema:validate
```

Resultado esperado:

```
## Mapping

[OK] The mapping files are correct.

## Database

[OK] The database schema is in sync with the mapping files.
```

---

# Observações

O projeto foi desenvolvido utilizando:

- Symfony 7.4
- Doctrine ORM
- Twig
- Bootstrap 5
- MySQL 8

Caso todas as etapas acima sejam executadas com sucesso, a aplicação estará pronta para uso.