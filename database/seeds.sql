-- Seeds: Roles
INSERT INTO roles (roleName, displayName, description) VALUES
('admin', 'Administrador', 'Acesso total ao sistema: CRUD de ferramentas, categorias, usuários, locações, relatórios e configurações.'),
('staff', 'Funcionário', 'Colaborador interno: pode registrar locações, devoluções e gerenciar ferramentas, sem acesso a gestão de usuários e financeiro.'),
('client', 'Cliente', 'Usuário cadastrado: pode navegar no catálogo, realizar locações, visualizar histórico próprio e gerenciar seu perfil.');

-- Seeds: Admin user (password: admin123)
-- Hash gerado com password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12])
-- Em produção, troque o e-mail e senha imediatamente
INSERT INTO users (name, email, passwordHash, roleId, isActive, emailVerifiedAt)
VALUES (
    'Administrador',
    'admin@ferramentasfacil.com.br',
    '$2y$12$HMW3ed7Rq0tb1jVLacNTPeCGIePjoWkzB8metraOORrSyCe7sRAP.',
    (SELECT roleId FROM roles WHERE roleName = 'admin'),
    TRUE,
    NOW()
);

-- Seeds: System Settings
INSERT INTO systemSettings (settingKey, settingValue, settingGroup, description) VALUES
('company_name', 'FerramentasFácil', 'company', 'Nome da empresa'),
('company_email', 'contato@ferramentasfacil.com.br', 'company', 'E-mail de contato'),
('company_phone', '(11) 99999-8888', 'company', 'Telefone/WhatsApp'),
('company_address', 'Rua Exemplo, 123 - Centro', 'company', 'Endereço da empresa'),
('min_rental_days', '1', 'rental', 'Mínimo de dias para locação'),
('max_rental_days', '30', 'rental', 'Máximo de dias para locação'),
('late_fine_per_day', '10.00', 'rental', 'Multa por dia de atraso (R$)'),
('session_lifetime', '7200', 'system', 'Tempo máximo de sessão em segundos (2 horas)');
