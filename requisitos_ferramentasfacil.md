🔧

**FerramentasFácil**

Sistema de Locação de Ferramentas de Construção

**PLANO DE REQUISITOS COMPLETO**

Tecnologias: PHP • MySQL (phpMyAdmin) • Google OAuth

Versão 1.0 • 2025

**1. Visão Geral do Projeto**

O FerramentasFácil é uma plataforma web completa para locação de ferramentas de construção civil, desenvolvida em PHP com banco de dados MySQL gerenciado via phpMyAdmin. O sistema contempla dois perfis principais: o Administrador (dono do negócio) e o Cliente (usuário que aluga ferramentas), com autenticação via e-mail/senha e via Google OAuth 2.0.

**1.1 Objetivos**

- Digitalizar e automatizar o processo de locação de ferramentas de construção.

- Oferecer uma Landing Page profissional para divulgação do catálogo.

- Permitir cadastro, login (normal + Google) e autoatendimento para clientes.

- Fornecer ao administrador painel completo de CRUD e monitoramento em tempo real.

- Controlar estoque, disponibilidade, histórico de locações e devolução de ferramentas.

- Garantir separação de permissões entre perfis (Roles).

**1.2 Escopo Tecnológico**

|  |  |
|----|----|
| **Camada** | **Tecnologia** |
| **Back-end** | PHP 8.x (puro ou framework Laravel/CodeIgniter) |
| **Banco de Dados** | MySQL 8.x --- gerenciado via phpMyAdmin |
| **Front-end** | HTML5, CSS3, Bootstrap 5, JavaScript (Vanilla/jQuery) |
| **Autenticação** | Sessions PHP + Google OAuth 2.0 (Google Identity Services) |
| **Servidor** | Apache/Nginx + PHP-FPM (XAMPP local / VPS/Shared Hosting) |
| **Controle de Versão** | Git + GitHub |

**2. Perfis de Usuário e Controle de Acesso (Roles)**

O sistema adota um modelo de Roles (funções/papéis) para segmentar o acesso de cada tipo de usuário. Cada usuário possui um único Role associado à sua conta.

**2.1 Roles do Sistema**

|  |  |  |
|----|----|----|
| **Role** | **Nome** | **Descrição e Permissões** |
| **admin** | Administrador | Dono do negócio. Acesso total: CRUD de ferramentas, categorias, usuários, locações. Visualiza relatórios, dashboard e logs. |
| **staff** | Funcionário | Colaborador interno. Pode registrar locações, devoluções e gerenciar ferramentas, mas sem acesso a gestão de usuários e financeiro. |
| **client** | Cliente | Usuário cadastrado. Pode navegar no catálogo, realizar locações, visualizar histórico próprio e gerenciar seu perfil. |

**2.2 Matriz de Permissões**

|                                  |           |           |            |
|----------------------------------|-----------|-----------|------------|
| **Funcionalidade**               | **Admin** | **Staff** | **Client** |
| Dashboard administrativo         | ✅ Sim    | ✅ Sim    | ❌ Não     |
| CRUD de Ferramentas              | ✅ Sim    | ✅ Sim    | ❌ Não     |
| CRUD de Categorias               | ✅ Sim    | ✅ Sim    | ❌ Não     |
| CRUD de Usuários                 | ✅ Sim    | ❌ Não    | ❌ Não     |
| Registrar locação (para cliente) | ✅ Sim    | ✅ Sim    | ✅ Própria |
| Monitorar todas as locações      | ✅ Sim    | ✅ Sim    | ❌ Não     |
| Ver histórico próprio            | ✅ Sim    | ✅ Sim    | ✅ Sim     |
| Alterar Roles de usuários        | ✅ Sim    | ❌ Não    | ❌ Não     |
| Relatórios e Financeiro          | ✅ Sim    | ❌ Não    | ❌ Não     |
| Navegar catálogo (Landing Page)  | ✅ Sim    | ✅ Sim    | ✅ Sim     |
| Gerenciar próprio perfil         | ✅ Sim    | ✅ Sim    | ✅ Sim     |

**3. Mapeamento Completo de Páginas**

**3.1 Área Pública --- Landing Page e Catálogo**

|  |  |  |
|----|----|----|
| **Página / Rota** | **Descrição** | **Funcionalidades** |
| **/ (Home / Landing Page)** | Página principal de divulgação. Apresenta a empresa, diferenciais e chamadas para ação. | Hero Banner, seção \"Como Funciona\", destaques de ferramentas, depoimentos, FAQ, CTA de cadastro |
| **/catalogo** | Listagem pública de todas as ferramentas disponíveis por categoria, com filtros e busca. | Filtro por categoria, busca por nome, cards com foto/preço/disponibilidade, paginação |
| **/ferramenta/{id}** | Página de detalhe de uma ferramenta específica. | Fotos, descrição completa, preço/dia, disponibilidade, botão \"Alugar Agora\" |
| **/sobre** | Página institucional com informações sobre o negócio. | Texto institucional, localização, horários, mapa embed |
| **/contato** | Formulário de contato e informações da empresa. | Formulário de e-mail, WhatsApp link, telefone, endereço |

**3.2 Área de Autenticação**

|  |  |  |
|----|----|----|
| **Página / Rota** | **Descrição** | **Funcionalidades** |
| **/login** | Tela de login para todos os usuários. | Login e-mail/senha, botão \"Entrar com Google\" (OAuth 2.0), link \"Esqueci a senha\" |
| **/cadastro** | Cadastro de novo cliente. | Formulário completo, validação em tempo real, opção de cadastro via Google, aceite de termos |
| **/esqueci-senha** | Solicitar redefinição de senha via e-mail. | Campo de e-mail, envio de link de recuperação, feedback de sucesso |
| **/redefinir-senha/{token}** | Página com formulário para nova senha via token. | Validação de token, campo de nova senha + confirmação |
| **/logout** | Encerrar sessão e redirecionar para home. | Destruição de session/cookie, redirect seguro |

**3.3 Área do Cliente (Logado)**

|  |  |  |
|----|----|----|
| **Página / Rota** | **Descrição** | **Funcionalidades** |
| **/cliente/dashboard** | Painel inicial do cliente com resumo da conta. | Locações ativas, histórico recente, ferramentas favoritas, notificações de devolução |
| **/cliente/alugar/{id}** | Tela para finalizar a solicitação de locação de uma ferramenta. | Seleção de datas (início/fim), cálculo automático de valor, confirmação, upload de documento se necessário |
| **/cliente/locacoes** | Histórico completo de todas as locações do cliente. | Lista paginada, filtros por status (ativa/devolvida/atrasada), detalhes por locação |
| **/cliente/locacoes/{id}** | Detalhes de uma locação específica do cliente. | Status, datas, ferramenta, valor total, botão \"Solicitar devolução\" |
| **/cliente/perfil** | Gerenciamento do perfil do cliente. | Editar dados pessoais, trocar senha, foto de perfil, vincular/desvincular Google |

**3.4 Área Administrativa (Admin/Staff)**

|  |  |  |
|----|----|----|
| **Página / Rota** | **Descrição** | **Funcionalidades** |
| **/admin/dashboard** | Painel central do administrador. | KPIs (locações ativas, receita do mês, ferramentas disponíveis, clientes), gráficos, alertas de atraso |
| **/admin/ferramentas** | Listagem de todas as ferramentas. | Tabela com filtros, busca, status, ações (editar/excluir/ver), botão \"Nova Ferramenta\" |
| **/admin/ferramentas/criar** | Formulário para cadastrar nova ferramenta. | Nome, categoria, descrição, preço/dia, quantidade em estoque, upload de foto, status |
| **/admin/ferramentas/{id}/editar** | Formulário para editar ferramenta existente. | Mesmos campos do cadastro, pré-carregados, histórico de alterações |
| **/admin/categorias** | Gerenciar categorias de ferramentas. | Listar, criar, editar, excluir categorias com ícone e descrição |
| **/admin/locacoes** | Monitoramento de TODAS as locações. | Filtros por status/data/cliente/ferramenta, exportar CSV, registrar devolução, marcar atraso |
| **/admin/locacoes/{id}** | Detalhes de uma locação específica. | Dados completos: cliente, ferramenta, datas, valor, histórico de status, anotações |
| **/admin/locacoes/criar** | Registrar locação manualmente (balcão). | Buscar cliente, selecionar ferramenta, definir período, gerar locação |
| **/admin/usuarios** | Gerenciar todos os usuários cadastrados. | Listar, buscar, ver perfil, alterar role, ativar/desativar, excluir conta |
| **/admin/usuarios/{id}** | Perfil completo de um usuário. | Dados pessoais, histórico de locações desse usuário, status da conta |
| **/admin/relatorios** | Relatórios gerenciais do negócio. | Receita por período, ferramentas mais alugadas, clientes mais ativos, relatório de atrasos, export PDF/CSV |
| **/admin/configuracoes** | Configurações gerais do sistema. | Dados da empresa, logo, políticas de locação (dias mínimos/máximos, multas), configuração e-mail SMTP |

**4. Modelagem do Banco de Dados**

Todas as tabelas utilizam nomenclatura camelCase. O banco de dados é MySQL 8.x, gerenciado via phpMyAdmin. As chaves primárias seguem o padrão AUTO_INCREMENT com prefixo descritivo nos relacionamentos.

**4.1 Tabela: users**

Armazena todos os usuários do sistema (admin, staff e clients).

|  |  |  |  |
|----|----|----|----|
| **Campo** | **Tipo** | **Restrição** | **Descrição** |
| **userId** | INT UNSIGNED | PK, AI | Identificador único do usuário |
| name | VARCHAR(150) | NOT NULL | Nome completo do usuário |
| email | VARCHAR(255) | UNIQUE, NOT NULL | E-mail (usado para login) |
| passwordHash | VARCHAR(255) | NULL | Hash bcrypt da senha (NULL se login Google) |
| googleId | VARCHAR(255) | UNIQUE, NULL | ID do Google para usuários OAuth |
| avatarUrl | VARCHAR(500) | NULL | URL da foto de perfil |
| phone | VARCHAR(20) | NULL | Telefone / WhatsApp do usuário |
| cpf | VARCHAR(14) | UNIQUE, NULL | CPF (obrigatório para clientes após primeiro login) |
| address | TEXT | NULL | Endereço completo do usuário |
| roleId | INT UNSIGNED | FK → roles | Role/perfil do usuário |
| isActive | TINYINT(1) | DEFAULT 1 | 1 = ativo, 0 = desativado |
| emailVerifiedAt | DATETIME | NULL | Data de verificação do e-mail |
| lastLoginAt | DATETIME | NULL | Timestamp do último login |
| createdAt | DATETIME | NOT NULL | Data de criação do registro |
| updatedAt | DATETIME | NOT NULL | Última atualização do registro |
| deletedAt | DATETIME | NULL | Soft delete --- NULL = registro ativo |

**4.2 Tabela: roles**

Define os papéis/perfis de acesso do sistema.

|  |  |  |  |
|----|----|----|----|
| **Campo** | **Tipo** | **Restrição** | **Descrição** |
| **roleId** | INT UNSIGNED | PK, AI | Identificador único do role |
| roleName | VARCHAR(50) | UNIQUE, NOT NULL | Nome do role (admin, staff, client) |
| displayName | VARCHAR(100) | NOT NULL | Nome de exibição (Administrador, Funcionário, Cliente) |
| description | TEXT | NULL | Descrição das permissões do role |
| createdAt | DATETIME | NOT NULL | Data de criação |

**4.3 Tabela: toolCategories**

Categorias que agrupam as ferramentas (ex: Perfuração, Corte, Medição, Elétrica etc.).

|  |  |  |  |
|----|----|----|----|
| **Campo** | **Tipo** | **Restrição** | **Descrição** |
| **categoryId** | INT UNSIGNED | PK, AI | Identificador único da categoria |
| categoryName | VARCHAR(100) | UNIQUE, NOT NULL | Nome da categoria |
| slug | VARCHAR(120) | UNIQUE, NOT NULL | URL amigável (ex: perfuracao, corte) |
| description | TEXT | NULL | Descrição da categoria |
| iconClass | VARCHAR(100) | NULL | Classe do ícone (Font Awesome ou Bootstrap Icons) |
| imageUrl | VARCHAR(500) | NULL | Imagem representativa da categoria |
| isActive | TINYINT(1) | DEFAULT 1 | Categoria ativa (visível no catálogo) |
| sortOrder | INT | DEFAULT 0 | Ordem de exibição |
| createdAt | DATETIME | NOT NULL | Data de criação |
| updatedAt | DATETIME | NOT NULL | Última atualização |

**4.4 Tabela: tools**

Armazena todas as ferramentas disponíveis para locação no sistema.

|  |  |  |  |
|----|----|----|----|
| **Campo** | **Tipo** | **Restrição** | **Descrição** |
| **toolId** | INT UNSIGNED | PK, AI | Identificador único da ferramenta |
| categoryId | INT UNSIGNED | FK → toolCategories | Categoria a que pertence |
| toolName | VARCHAR(200) | NOT NULL | Nome da ferramenta |
| slug | VARCHAR(220) | UNIQUE, NOT NULL | URL amigável para a página de detalhe |
| brand | VARCHAR(100) | NULL | Marca da ferramenta (Bosch, Makita, DeWalt etc.) |
| model | VARCHAR(100) | NULL | Modelo específico da ferramenta |
| description | TEXT | NOT NULL | Descrição detalhada, usos e especificações |
| dailyPrice | DECIMAL(10,2) | NOT NULL | Preço por dia de locação (R\$) |
| depositAmount | DECIMAL(10,2) | DEFAULT 0 | Valor de caução/depósito (R\$) |
| totalStock | INT | NOT NULL | Quantidade total em estoque |
| availableStock | INT | NOT NULL | Quantidade disponível no momento |
| minRentalDays | INT | DEFAULT 1 | Mínimo de dias para locação |
| maxRentalDays | INT | DEFAULT 30 | Máximo de dias para locação |
| coverImageUrl | VARCHAR(500) | NULL | URL da imagem principal |
| status | ENUM | NOT NULL | \'available\',\'rented\',\'maintenance\',\'inactive\' |
| isFeatured | TINYINT(1) | DEFAULT 0 | Exibir em destaque na Landing Page |
| viewCount | INT | DEFAULT 0 | Contador de visualizações da página |
| createdAt | DATETIME | NOT NULL | Data de cadastro |
| updatedAt | DATETIME | NOT NULL | Última atualização |
| deletedAt | DATETIME | NULL | Soft delete --- NULL = ativo |

**4.5 Tabela: toolImages**

Galeria de imagens adicionais de cada ferramenta (além da imagem principal).

|  |  |  |  |
|----|----|----|----|
| **Campo** | **Tipo** | **Restrição** | **Descrição** |
| **imageId** | INT UNSIGNED | PK, AI | Identificador único da imagem |
| toolId | INT UNSIGNED | FK → tools | Ferramenta associada |
| imageUrl | VARCHAR(500) | NOT NULL | Caminho/URL da imagem |
| altText | VARCHAR(255) | NULL | Texto alternativo para acessibilidade |
| sortOrder | INT | DEFAULT 0 | Ordem na galeria |
| createdAt | DATETIME | NOT NULL | Data de upload |

**4.6 Tabela: rentals**

Registro de todas as locações realizadas no sistema --- tabela central do negócio.

|  |  |  |  |
|----|----|----|----|
| **Campo** | **Tipo** | **Restrição** | **Descrição** |
| **rentalId** | INT UNSIGNED | PK, AI | Identificador único da locação |
| rentalCode | VARCHAR(20) | UNIQUE, NOT NULL | Código legível da locação (ex: LOC-20251201-0042) |
| userId | INT UNSIGNED | FK → users | Cliente que realizou a locação |
| toolId | INT UNSIGNED | FK → tools | Ferramenta locada |
| startDate | DATE | NOT NULL | Data de início da locação |
| expectedEndDate | DATE | NOT NULL | Data prevista de devolução |
| actualEndDate | DATE | NULL | Data real de devolução (NULL = ainda alugado) |
| rentalDays | INT | NOT NULL | Total de dias contratados |
| dailyPrice | DECIMAL(10,2) | NOT NULL | Preço por dia no momento da locação (histórico) |
| depositAmount | DECIMAL(10,2) | DEFAULT 0 | Caução cobrado |
| totalAmount | DECIMAL(10,2) | NOT NULL | Valor total da locação (dias × preço) |
| fineAmount | DECIMAL(10,2) | DEFAULT 0 | Multa por atraso na devolução |
| status | ENUM | NOT NULL | \'pending\',\'active\',\'returned\',\'overdue\',\'cancelled\' |
| paymentStatus | ENUM | NOT NULL | \'pending\',\'paid\',\'refunded\' |
| notes | TEXT | NULL | Observações internas do admin/staff |
| registeredBy | INT UNSIGNED | FK → users | Admin/staff que registrou (se for balcão) |
| createdAt | DATETIME | NOT NULL | Data de criação do registro |
| updatedAt | DATETIME | NOT NULL | Última atualização |

**4.7 Tabela: rentalStatusHistory**

Auditoria de todas as mudanças de status de cada locação (rastreabilidade completa).

|  |  |  |  |
|----|----|----|----|
| **Campo** | **Tipo** | **Restrição** | **Descrição** |
| **historyId** | INT UNSIGNED | PK, AI | Identificador do registro de histórico |
| rentalId | INT UNSIGNED | FK → rentals | Locação referenciada |
| previousStatus | VARCHAR(30) | NULL | Status anterior |
| newStatus | VARCHAR(30) | NOT NULL | Novo status aplicado |
| changedBy | INT UNSIGNED | FK → users | Usuário que realizou a mudança |
| changeReason | TEXT | NULL | Motivo da mudança (campo livre) |
| createdAt | DATETIME | NOT NULL | Data/hora da mudança |

**4.8 Tabela: passwordResetTokens**

Gerencia os tokens de redefinição de senha por e-mail.

|  |  |  |  |
|----|----|----|----|
| **Campo** | **Tipo** | **Restrição** | **Descrição** |
| **tokenId** | INT UNSIGNED | PK, AI | Identificador do token |
| userId | INT UNSIGNED | FK → users | Usuário solicitante |
| token | VARCHAR(255) | UNIQUE, NOT NULL | Token gerado (hash SHA-256) |
| expiresAt | DATETIME | NOT NULL | Validade do token (default: 1 hora) |
| usedAt | DATETIME | NULL | Quando foi utilizado (NULL = disponível) |
| createdAt | DATETIME | NOT NULL | Data de criação do token |

**4.9 Tabela: systemSettings**

Configurações dinâmicas do sistema (dados da empresa, políticas, SMTP etc.).

|  |  |  |  |
|----|----|----|----|
| **Campo** | **Tipo** | **Restrição** | **Descrição** |
| **settingId** | INT UNSIGNED | PK, AI | Identificador |
| settingKey | VARCHAR(100) | UNIQUE, NOT NULL | Chave única da configuração (ex: company_name) |
| settingValue | TEXT | NULL | Valor da configuração |
| settingGroup | VARCHAR(50) | NOT NULL | Grupo (company, rental, email, payment) |
| description | TEXT | NULL | Descrição do que a configuração controla |
| updatedAt | DATETIME | NOT NULL | Última atualização |

**5. Diagrama de Relacionamentos (ERD Simplificado)**

As setas indicam a direção da chave estrangeira (FK). Relacionamentos principais do sistema:

|  |  |  |  |
|----|----|----|----|
| **Tabela Origem** |  | **Tabela Destino** | **Descrição do Relacionamento** |
| **users** | **→ FK** | **roles** | Cada usuário possui um único role |
| **tools** | **→ FK** | **toolCategories** | Cada ferramenta pertence a uma categoria |
| **toolImages** | **→ FK** | **tools** | Uma ferramenta pode ter várias imagens |
| **rentals** | **→ FK** | **users** | Cada locação pertence a um cliente |
| **rentals** | **→ FK** | **tools** | Cada locação referencia uma ferramenta |
| **rentals** | **→ FK** | **users (registeredBy)** | Admin/staff que registrou a locação |
| **rentalStatusHistory** | **→ FK** | **rentals** | Histórico de mudanças de uma locação |
| **rentalStatusHistory** | **→ FK** | **users (changedBy)** | Usuário que fez a mudança de status |
| **passwordResetTokens** | **→ FK** | **users** | Token de reset associado ao usuário |

**6. Requisitos Funcionais**

**RF-01 --- Autenticação e Sessões**

- O sistema deve permitir login com e-mail e senha (hash bcrypt).

- O sistema deve oferecer login com Google via OAuth 2.0 (Google Identity Services).

- Usuários que se cadastram via Google têm passwordHash = NULL.

- Ao fazer login com Google, se o e-mail já existir no banco, a conta é vinculada ao googleId.

- Sessões PHP devem expirar após 2 horas de inatividade.

- Toda rota protegida deve verificar a sessão ativa e o role do usuário.

- O sistema deve implementar proteção contra CSRF em todos os formulários POST.

**RF-02 --- Cadastro de Usuários**

- Clientes podem se cadastrar pela Landing Page ou pela tela de login.

- O cadastro requer: nome completo, e-mail, senha (mínimo 8 caracteres), telefone.

- CPF é solicitado ao completar o perfil (antes da primeira locação).

- E-mail de verificação deve ser enviado após o cadastro.

- O admin pode cadastrar usuários com qualquer role diretamente pelo painel.

**RF-03 --- CRUD de Ferramentas**

- Admin e Staff podem criar, editar, excluir (soft delete) e listar ferramentas.

- O cadastro de ferramenta exige: nome, categoria, descrição, preço/dia, estoque total.

- É possível fazer upload de múltiplas imagens por ferramenta.

- A exclusão não apaga o registro; apenas seta deletedAt (soft delete).

- A ferramenta deve ter controle automático de availableStock ao criar/devolver locações.

- Ferramentas em locação ativa não podem ser excluídas.

**RF-04 --- CRUD de Categorias**

- Admin e Staff podem criar, editar, ativar/desativar e excluir categorias.

- Categorias com ferramentas vinculadas não podem ser excluídas (apenas desativadas).

- A ordem de exibição (sortOrder) pode ser ajustada via drag-and-drop ou campo numérico.

**RF-05 --- CRUD de Usuários (Admin)**

- Admin pode listar, pesquisar, visualizar, editar e desativar qualquer usuário.

- Admin pode alterar o role de qualquer usuário.

- A exclusão de usuário é lógica (soft delete via deletedAt) para preservar histórico.

- Admin não pode alterar o próprio role para evitar auto-rebaixamento acidental.

**RF-06 --- Processo de Locação**

- O cliente seleciona uma ferramenta disponível no catálogo.

- O cliente define as datas de início e devolução prevista.

- O sistema calcula automaticamente o totalAmount = rentalDays × dailyPrice.

- A locação é criada com status \"pending\" aguardando confirmação do admin, ou \"active\" se aprovação automática estiver habilitada.

- Ao confirmar a locação, o availableStock da ferramenta é decrementado.

- Admin/Staff podem criar locações manualmente (atendimento de balcão).

**RF-07 --- Monitoramento de Locações**

- O painel admin exibe todas as locações ativas com: cliente, ferramenta, data início, data prevista, dias restantes.

- Locações com data prevista no passado e status \"active\" são automaticamente marcadas como \"overdue\".

- O admin pode registrar a devolução de uma ferramenta, settando actualEndDate e status \"returned\".

- Ao devolver, o availableStock é incrementado e multas são calculadas se houver atraso.

- Todo o histórico de mudança de status é registrado em rentalStatusHistory.

**RF-08 --- Landing Page**

- A Landing Page deve exibir: hero banner com CTA, seção de ferramentas em destaque (isFeatured = 1), como funciona (3 passos), categorias disponíveis, depoimentos, FAQ e rodapé.

- As ferramentas em destaque são gerenciadas pelo admin (campo isFeatured).

- O catálogo público não exige login; apenas para alugar o usuário deve estar autenticado.

- A página deve ser responsiva (mobile-first) e otimizada para SEO.

**7. Requisitos Não Funcionais**

**RNF-01 --- Segurança**

- Todas as senhas armazenadas com password_hash() do PHP (bcrypt, custo 12).

- Queries ao banco EXCLUSIVAMENTE via PDO com prepared statements (prevenção de SQL Injection).

- Proteção CSRF com tokens em todos os formulários que modificam dados (POST/PUT/DELETE).

- Headers de segurança: X-Content-Type-Options, X-Frame-Options, CSP configurados.

- Uploads de imagem validados por MIME type real (não apenas extensão). Aceito: jpg, png, webp.

- Variáveis de ambiente para credenciais sensíveis (DB, Google Client ID/Secret, SMTP).

- Rate limiting em endpoints de login (máximo 5 tentativas por IP por minuto).

**RNF-02 --- Performance**

- Uso de índices no banco para: email, googleId, categoryId, toolId, userId, status.

- Paginação em todas as listagens (máximo 20 registros por página por padrão).

- Imagens armazenadas em diretório separado e servidas com caching de browser (1 semana).

- Lazy loading de imagens na Landing Page e catálogo.

**RNF-03 --- Usabilidade**

- Interface responsiva com Bootstrap 5 (suporte a mobile, tablet e desktop).

- Feedback visual imediato em todas as ações (toast/alert de sucesso ou erro).

- Validação de formulários no front-end (JS) e no back-end (PHP) --- sempre os dois.

- Mensagens de erro claras e orientadas ao usuário (sem expor stack trace em produção).

- Sistema de notificações para: devolução próxima (D-1), locação atrasada.

**RNF-04 --- Manutenibilidade**

- Código organizado em padrão MVC (Model, View, Controller) mesmo sem framework obrigatório.

- Arquivos de configuração centralizados em /config/ (separados por ambiente: dev/prod).

- Migrations versionadas ou script SQL único para criação do banco.

- Funções e classes comentadas em padrão PHPDoc.

**8. Fluxo de Autenticação com Google OAuth 2.0**

O sistema implementa o fluxo Authorization Code Flow do Google para garantir segurança.

1.  Usuário clica em \"Entrar com Google\" na tela de login ou cadastro.

2.  O sistema redireciona para o endpoint de autorização do Google com: client_id, redirect_uri, scope (email, profile) e state (token anti-CSRF).

3.  O usuário autoriza e o Google redireciona de volta para /auth/google/callback com um code.

4.  O back-end (PHP) troca o code por um access_token via POST para https://oauth2.googleapis.com/token.

5.  Com o access_token, busca os dados do usuário: id, email, name, picture via https://www.googleapis.com/oauth2/v3/userinfo.

6.  O sistema verifica: se o googleId já existe em users → faz login direto. Se o e-mail existe mas sem googleId → vincula o googleId e faz login. Se nenhum existe → cria novo usuário com role = \"client\" e faz login.

7.  Sessão PHP é criada com userId, roleId, name, email.

8.  Usuário é redirecionado para o dashboard ou para completar o perfil (CPF) se necessário.

**Configuração necessária no Google Cloud Console:**

- Criar projeto no Google Cloud Console.

- Habilitar Google Identity API / OAuth 2.0.

- Criar credenciais OAuth (Web Application), adicionar Authorized redirect URIs.

- Armazenar GOOGLE_CLIENT_ID e GOOGLE_CLIENT_SECRET no .env do projeto.

**9. Estrutura de Diretórios do Projeto**

Organização recomendada para o projeto PHP com padrão MVC:

|  |  |
|----|----|
| **Diretório / Arquivo** | **Responsabilidade** |
| **/app/Controllers/** | Controllers PHP para cada módulo (ToolController, RentalController etc.) |
| **/app/Models/** | Classes de modelo com acesso ao banco via PDO |
| **/app/Views/** | Templates PHP/HTML organizados por módulo |
| **/app/Views/layouts/** | Layout base para área pública e área admin |
| **/app/Middleware/** | AuthMiddleware, RoleMiddleware para proteção de rotas |
| **/config/** | database.php, app.php, mail.php --- configurações por ambiente |
| **/public/** | Document root do servidor. Contém index.php (front controller) |
| **/public/assets/css/** | CSS customizado, Bootstrap compilado |
| **/public/assets/js/** | Scripts JavaScript da aplicação |
| **/public/uploads/** | Imagens enviadas pelos usuários (ferramentas, avatares) |
| **/routes/** | web.php --- definição de todas as rotas da aplicação |
| **/database/** | schema.sql (script de criação), seeds/ (dados iniciais) |
| **/.env.example** | Modelo de variáveis de ambiente (sem dados reais) |
| **/.htaccess** | Rewrite rules para URL amigável (Apache) |

**10. Cronograma de Desenvolvimento Sugerido**

Estimativa de desenvolvimento para uma pessoa ou equipe pequena. Cada sprint dura 1 semana.

|  |  |  |  |
|----|----|----|----|
| **Sprint** | **Fase** | **Entregas** | **Duração** |
| **1** | Setup e Banco de Dados | Configuração do ambiente, criação do banco (schema.sql), seeds de roles e admin, .env | 1 semana |
| **2** | Autenticação | Login/cadastro normal, sessões PHP, middleware de auth, recuperação de senha por e-mail | 1 semana |
| **3** | Google OAuth | Integração Google OAuth 2.0, fluxo de vinculação de conta, testes | 1 semana |
| **4** | CRUD Categorias e Ferramentas | Admin: listar/criar/editar/excluir categorias e ferramentas, upload de imagens | 1--2 semanas |
| **5** | Landing Page e Catálogo | Design da Landing Page, catálogo público, página de detalhe, responsividade | 1--2 semanas |
| **6** | Sistema de Locação | Fluxo completo de alugar, cálculo de valor, controle de estoque, status, histórico | 1--2 semanas |
| **7** | Dashboard e Monitoramento | KPIs, gráficos, alertas de atraso, painel de locações ativas, registrar devolução | 1 semana |
| **8** | CRUD Usuários e Roles | Admin: gerenciar usuários, alterar roles, ativar/desativar, visualizar perfil | 1 semana |
| **9** | Relatórios e Configurações | Relatórios gerenciais, exportação CSV/PDF, página de configurações do sistema | 1 semana |
| **10** | Testes, SEO e Deploy | Testes funcionais, correções de bugs, otimizações de performance, deploy em produção | 1--2 semanas |

**11. Glossário**

|  |  |
|----|----|
| **Termo** | **Definição** |
| **Role** | Papel/função de um usuário no sistema que define suas permissões de acesso. |
| **Soft Delete** | Técnica de exclusão lógica: o registro não é apagado fisicamente, apenas marcado com deletedAt. |
| **OAuth 2.0** | Protocolo de autorização que permite login seguro via conta Google sem compartilhar senhas. |
| **PDO** | PHP Data Objects --- interface do PHP para acesso ao banco com suporte a prepared statements. |
| **CSRF** | Cross-Site Request Forgery --- ataque prevenido por tokens únicos em formulários. |
| **bcrypt** | Algoritmo de hashing para senhas, com fator de custo configurável. |
| **availableStock** | Quantidade de unidades de uma ferramenta disponíveis para nova locação no momento. |
| **rentalCode** | Código único e legível de cada locação, ex: LOC-20251201-0042, para referência humana. |
| **isFeatured** | Flag booleana que define se a ferramenta aparece em destaque na Landing Page. |
| **camelCase** | Convenção de nomenclatura onde palavras compostas iniciam com maiúscula a partir da segunda: rentalId. |

**FerramentasFácil --- Plano de Requisitos v1.0**

Documento confidencial --- gerado para uso interno da equipe de desenvolvimento
