# Manual do Usuário - FerramentasFácil

**Sistema de Locação de Ferramentas de Construção**

Versão 1.0 — 2026

---

## Sumário

1. [Introdução](#1-introdução)
2. [Acesso ao Sistema](#2-acesso-ao-sistema)
3. [Autenticação](#3-autenticação)
   - 3.1 Cadastro
   - 3.2 Login (E-mail/Senha)
   - 3.3 Login com Google
   - 3.4 Recuperação de Senha
4. [Área Pública](#4-área-pública)
   - 4.1 Landing Page
   - 4.2 Catálogo de Ferramentas
   - 4.3 Detalhe da Ferramenta
   - 4.4 Página Sobre
   - 4.5 Contato
5. [Área do Cliente](#5-área-do-cliente)
   - 5.1 Dashboard
   - 5.2 Alugar uma Ferramenta
   - 5.3 Histórico de Locações
   - 5.4 Perfil
6. [Área Administrativa](#6-área-administrativa)
   - 6.1 Dashboard (KPIs)
   - 6.2 Gerenciar Ferramentas
   - 6.3 Gerenciar Categorias
   - 6.4 Gerenciar Locações
   - 6.5 Gerenciar Usuários
   - 6.6 Relatórios
   - 6.7 Mensagens de Contato
   - 6.8 Configurações
7. [Perguntas Frequentes](#7-perguntas-frequentes)
8. [Suporte](#8-suporte)

---

## 1. Introdução

O **FerramentasFácil** é uma plataforma web completa para locação de ferramentas de construção civil. O sistema permite que clientes encontrem, aluguem e devolvam ferramentas de forma digital, e que administradores gerenciem todo o negócio em um único painel.

### Principais funcionalidades:

- **Catálogo público** com busca e filtros por categoria
- **Autenticação segura** via e-mail/senha ou conta Google
- **Autoatendimento** para clientes realizarem locações online
- **Painel administrativo** com KPIs, gráficos e relatórios
- **Controle de estoque** automatizado
- **Histórico completo** de todas as movimentações

---

## 2. Acesso ao Sistema

### Requisitos técnicos

- Navegador moderno (Google Chrome, Firefox, Edge, Safari — versões recentes)
- Conexão com internet
- JavaScript habilitado

### URL de acesso

```
https://ferramentasfacil.com
```

---

## 3. Autenticação

### 3.1 Cadastro

1. Acesse `/cadastro` ou clique em "Criar Conta" na Landing Page.
2. Preencha os campos obrigatórios:
   - **Nome completo**
   - **E-mail** (será usado para login)
   - **Senha** (mínimo 8 caracteres)
   - **Telefone** para contato
3. Aceite os Termos de Uso.
4. Clique em "Cadastrar".

Após o cadastro, você será redirecionado ao dashboard do cliente.

### 3.2 Login (E-mail/Senha)

1. Acesse `/login` ou clique em "Entrar" no menu.
2. Informe seu e-mail e senha cadastrados.
3. Clique em "Entrar".

### 3.3 Login com Google

1. Na tela de login, clique em "Entrar com Google".
2. Autorize o aplicativo com sua conta Google.
3. O sistema fará login automaticamente.

> Se o e-mail do Google já existir no sistema, as contas serão vinculadas automaticamente.

### 3.4 Recuperação de Senha

1. Na tela de login, clique em "Esqueceu a senha?".
2. Informe o e-mail cadastrado.
3. Verifique sua caixa de entrada — você receberá um link para redefinir a senha.
4. Clique no link e defina uma nova senha.

---

## 4. Área Pública

### 4.1 Landing Page

A página inicial (`/`) apresenta:

- **Hero banner** com chamada para ação
- **Seção "Como Funciona"** com os 3 passos: Escolher, Agendar, Retirar
- **Ferramentas em Destaque** — cards com as ferramentas mais relevantes
- **Categorias** — grid de categorias disponíveis
- **FAQ** — perguntas frequentes sobre o serviço

### 4.2 Catálogo de Ferramentas

Acesse `/catalogo` para ver todas as ferramentas disponíveis.

**Funcionalidades:**
- **Filtro por categoria** — selecione uma categoria no menu lateral
- **Busca por nome** — digite o nome da ferramenta desejada
- **Paginação** — navegue entre páginas de resultados
- **Badges de disponibilidade** — verde para disponível, vermelho para indisponível
- **Preço/dia** exibido em cada card

### 4.3 Detalhe da Ferramenta

Clique em uma ferramenta para ver seus detalhes completos (`/ferramenta/{slug}`):

- Imagem principal e galeria de imagens
- Marca e modelo
- Descrição completa
- Preço por dia e valor de caução
- Quantidade disponível em estoque
- Período mínimo e máximo de locação
- Botão "Alugar Agora" (exige login)

### 4.4 Página Sobre

A página `/sobre` contém informações institucionais:

- História da empresa
- Estatísticas (ferramentas, clientes, locações)
- Missão, Visão e Valores
- Localização com mapa interativo
- Horário de funcionamento e contatos

### 4.5 Contato

A página `/contato` oferece:

- **Formulário de contato** — envie sua mensagem diretamente pelo site
- **Informações de contato** — WhatsApp, e-mail, endereço e horários
- As mensagens enviadas ficam registradas para consulta do administrador

---

## 5. Área do Cliente

### 5.1 Dashboard

Após fazer login como cliente, acesse `/cliente/dashboard` para ver:

- Resumo do seu perfil
- Últimas locações (até 5)
- Status das locações ativas
- Ações rápidas (Alugar, Ver locações, Editar perfil)

### 5.2 Alugar uma Ferramenta

1. Navegue até o catálogo e encontre a ferramenta desejada.
2. Clique em "Alugar Agora" na página de detalhe.
3. Na tela de locação (`/cliente/alugar/{id}`):
   - Selecione a **data de início** e **data de devolução**
   - O sistema calcula automaticamente o **valor total** (dias × preço/dia)
   - Confira os valores e clique em "Confirmar Locação"
4. A locação será criada com status **"Pendente"** aguardando confirmação.

### 5.3 Histórico de Locações

Acesse `/cliente/locacoes` para visualizar:

- Lista completa de todas as suas locações
- Status de cada locação (Pendente, Ativa, Devolvida, Atrasada, Cancelada)
- Clique em uma locação para ver detalhes completos

Na página de detalhe (`/cliente/locacoes/{id}`) você pode:
- Ver todas as informações da locação
- Acompanhar o histórico de mudanças de status

### 5.4 Perfil

Acesse `/cliente/perfil` para gerenciar seus dados:

- Editar nome, telefone, CPF e endereço
- Alterar senha
- Vincular/desvincular conta Google

---

## 6. Área Administrativa

> Acesso restrito a usuários com perfil **Administrador** ou **Funcionário**.

### 6.1 Dashboard (KPIs)

O painel principal (`/admin/dashboard`) exibe:

- **Clientes cadastrados** — total de clientes no sistema
- **Ferramentas** — total de ferramentas no catálogo
- **Locações Ativas** — locações em andamento
- **Receita do Mês** — faturamento do período
- **Gráfico de Receita Mensal** — evolução nos últimos 12 meses
- **Ferramentas Mais Alugadas** — top 5 ferramentas por número de locações
- **Alertas** — notificações de locações pendentes e em atraso

### 6.2 Gerenciar Ferramentas

**Listagem** (`/admin/ferramentas`):
- Tabela com todas as ferramentas cadastradas
- Busca por nome
- Filtro por status (disponível, alugada, manutenção, inativa)
- Ações: Editar, Excluir

**Criar ferramenta** (`/admin/ferramentas/criar`):
- Nome, marca, modelo
- Categoria
- Descrição detalhada
- Preço por dia e valor de caução
- Estoque total
- Período mínimo/máximo de locação
- Imagem principal
- Status e destaque

**Editar ferramenta** (`/admin/ferramentas/{id}/editar`):
- Mesmos campos do cadastro, pré-preenchidos
- Upload de imagens adicionais para galeria

> A exclusão é lógica (soft delete). Ferramentas com locações ativas não podem ser excluídas.

### 6.3 Gerenciar Categorias

**Listagem** (`/admin/categorias`):
- Todas as categorias cadastradas
- Indicador visual de ativa/inativa

**Criar/Editar categoria**:
- Nome, slug (URL amigável)
- Descrição
- ícone (classe Bootstrap Icons)
- Ordem de exibição
- Ativar/desativar

> Categorias com ferramentas vinculadas não podem ser excluídas, apenas desativadas.

### 6.4 Gerenciar Locações

**Listagem** (`/admin/locacoes`):
- Todas as locações do sistema
- Filtros por status (Pendente, Ativa, Devolvida, Atrasada, Cancelada)
- Busca por cliente ou ferramenta

**Detalhes** (`/admin/locacoes/{id}`):
- Informações completas da locação
- Histórico de mudanças de status
- Ações disponíveis:
  - **Confirmar** — aprova locação pendente e baixa o estoque
  - **Registrar Devolução** — finaliza a locação, calcula multa se houver atraso e devolve ao estoque
  - **Cancelar** — cancela a locação

**Criar locação manual** (`/admin/locacoes/criar`):
- Atendimento de balcão
- Buscar cliente e ferramenta
- Definir período de locação

### 6.5 Gerenciar Usuários

**Listagem** (`/admin/usuarios`):
- Todos os usuários cadastrados
- Busca por nome ou e-mail
- Status (ativo/inativo)

**Detalhes do usuário** (`/admin/usuarios/{id}`):
- Dados pessoais completos
- Histórico de locações do usuário
- Alterar perfil (role): Admin, Funcionário ou Cliente
- Ativar/Desativar conta

> O administrador não pode alterar seu próprio role para evitar auto-rebaixamento acidental.

### 6.6 Relatórios

Acesse `/admin/relatorios` para:

- **Filtrar por período**: Semana, Mês, Trimestre, Ano ou Personalizado
- **Indicadores**:
  - Receita total do período
  - Total de multas recebidas
  - Locações por status (ativas, devolvidas, atrasadas, canceladas)
- **Ferramentas mais alugadas** — tabela com nome, quantidade de locações e receita gerada
- Botão **Imprimir** para gerar versão para impressão

### 6.7 Mensagens de Contato

Acesse `/admin/mensagens` para visualizar as mensagens enviadas pelo formulário de contato do site:

- Lista com nome, e-mail, assunto e data
- Indicador de mensagens não lidas
- Modal para leitura completa da mensagem
- Botão "Marcar como Lida"
- Paginação automática

### 6.8 Configurações

Acesse `/admin/configuracoes` para definir:

**Dados da Empresa:**
- Nome, e-mail de contato, telefone/WhatsApp, endereço

**Políticas de Locação:**
- Dias mínimo e máximo para locação
- Valor da multa por dia de atraso

**Configurações do Sistema:**
- Tempo de sessão (em segundos)

---

## 7. Perguntas Frequentes

### Preciso de garantia para alugar?

Sim, é necessário deixar um depósito caução que será devolvido na devolução da ferramenta em boas condições.

### Qual o prazo mínimo de locação?

O prazo mínimo é de 1 dia, podendo variar conforme a ferramenta.

### O que acontece se devolver atrasado?

Será cobrada uma multa por dia de atraso, conforme a política definida nas configurações do sistema.

### Como faço para alugar uma ferramenta?

Navegue pelo catálogo, escolha a ferramenta desejada e clique em "Alugar Agora". Defina as datas e confirme a locação.

### Posso cancelar uma locação?

Sim, locações com status "Pendente" podem ser canceladas. Entre em contato com o administrador para cancelar locações já ativas.

### Como recuperar minha senha?

Na tela de login, clique em "Esqueceu a senha?" e informe seu e-mail. Você receberá um link para redefinir a senha.

### Preciso estar logado para ver o catálogo?

Não. O catálogo é público e pode ser acessado sem login. Apenas para realizar a locação é necessário estar autenticado.

---

## 8. Suporte

### Canais de Atendimento

| Canal | Informação |
|-------|-----------|
| **WhatsApp** | (11) 99999-8888 |
| **E-mail** | contato@ferramentasfacil.com.br |
| **Endereço** | Rua Exemplo, 123 - Centro, São Paulo - SP |
| **Horário** | Seg-Sex: 07h-18h / Sáb: 08h-12h |

### Reportar Problemas

Em caso de problemas técnicos com o sistema, entre em contato pelo e-mail de suporte informando:
- Descrição detalhada do problema
- Passos para reproduzir
- Screenshot (se aplicável)
- Navegador e sistema operacional utilizados

---

© 2026 FerramentasFácil. Todos os direitos reservados.
