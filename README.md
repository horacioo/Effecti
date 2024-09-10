# README

## Introdução

Este projeto consiste no desenvolvimento de um CRUD (Create, Read, Update, Delete) para gerenciar usuários. Você pode optar por criar um sistema monolítico ou separar o front-end e o back-end; ambas as abordagens são válidas para este teste.

## Objetivos

O principal objetivo deste teste é avaliar suas habilidades e sua forma de trabalho. Para isso, utilize as seguintes tecnologias e ferramentas:

- **Front-end**: HTML, CSS, JS
- **Back-end**: PHP
- **Gerenciador de dependências**: Composer
- **Framework**: Escolha um framework PHP (Laravel, CodeIgniter, Zend, Yii, CakePHP, Phalcon, Adianti Framework)
- **Front-end adicional**: Caso implemente front-end, utilize qualquer framework, preferencialmente ExtJS
- **Banco de Dados**: MySQL ou PostgreSQL

## Requisitos

Sua aplicação deve atender aos seguintes requisitos:

### Estrutura da Tabela

- **Cadastro de Usuário**
  - Nome
  - CPF
  - Data de Nascimento
  - Email
  - Telefone
  - CEP
  - Estado
  - Cidade
  - Bairro
  - Endereço
  - Status (Ativo/Inativo)

### Funcionalidades

1. **Cadastro de Usuário**: Permitir cadastrar, alterar, visualizar e excluir usuários.
   - Ao excluir um usuário, apenas altere seu status para "Inativo", mantendo o registro no banco de dados.

2. **Validações**:
   - Validação do CPF
   - Validação do Email

3. **Preenchimento Automático**:
   - Ao informar o CEP, consuma a API Via CEP para preencher automaticamente os campos Estado, Cidade, Bairro e Endereço.

4. **Exportação de Dados**:
   - Permitir a exportação dos dados de usuários nos formatos: PDF, XLS e CSV.

## Contato

Para mais informações, você pode me contatar pelo WhatsApp: [13-991159522](https://wa.me/5513991159522)  
Visite meu site: [Planet1](http://www.planet1.com.br)