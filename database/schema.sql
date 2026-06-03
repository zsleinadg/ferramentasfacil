CREATE DATABASE ferramentasfacil;

\c ferramentasfacil;

-- ENUM types
CREATE TYPE tool_status AS ENUM ('available', 'rented', 'maintenance', 'inactive');
CREATE TYPE rental_status AS ENUM ('pending', 'active', 'returned', 'overdue', 'cancelled');
CREATE TYPE payment_status AS ENUM ('pending', 'paid', 'refunded');

-- 4.2 Tabela: roles
CREATE TABLE roles (
    roleId SERIAL PRIMARY KEY,
    roleName VARCHAR(50) UNIQUE NOT NULL,
    displayName VARCHAR(100) NOT NULL,
    description TEXT,
    createdAt TIMESTAMP NOT NULL DEFAULT NOW()
);

-- 4.3 Tabela: toolCategories
CREATE TABLE toolCategories (
    categoryId SERIAL PRIMARY KEY,
    categoryName VARCHAR(100) UNIQUE NOT NULL,
    slug VARCHAR(120) UNIQUE NOT NULL,
    description TEXT,
    iconClass VARCHAR(100),
    imageUrl VARCHAR(500),
    isActive BOOLEAN DEFAULT TRUE,
    sortOrder INT DEFAULT 0,
    createdAt TIMESTAMP NOT NULL DEFAULT NOW(),
    updatedAt TIMESTAMP NOT NULL DEFAULT NOW()
);

-- 4.4 Tabela: tools
CREATE TABLE tools (
    toolId SERIAL PRIMARY KEY,
    categoryId INT NOT NULL REFERENCES toolCategories(categoryId),
    toolName VARCHAR(200) NOT NULL,
    slug VARCHAR(220) UNIQUE NOT NULL,
    brand VARCHAR(100),
    model VARCHAR(100),
    description TEXT NOT NULL,
    dailyPrice DECIMAL(10,2) NOT NULL,
    depositAmount DECIMAL(10,2) DEFAULT 0,
    totalStock INT NOT NULL,
    availableStock INT NOT NULL,
    minRentalDays INT DEFAULT 1,
    maxRentalDays INT DEFAULT 30,
    coverImageUrl VARCHAR(500),
    status tool_status NOT NULL DEFAULT 'available',
    isFeatured BOOLEAN DEFAULT FALSE,
    viewCount INT DEFAULT 0,
    createdAt TIMESTAMP NOT NULL DEFAULT NOW(),
    updatedAt TIMESTAMP NOT NULL DEFAULT NOW(),
    deletedAt TIMESTAMP
);

-- 4.5 Tabela: toolImages
CREATE TABLE toolImages (
    imageId SERIAL PRIMARY KEY,
    toolId INT NOT NULL REFERENCES tools(toolId),
    imageUrl VARCHAR(500) NOT NULL,
    altText VARCHAR(255),
    sortOrder INT DEFAULT 0,
    createdAt TIMESTAMP NOT NULL DEFAULT NOW()
);

-- 4.1 Tabela: users
CREATE TABLE users (
    userId SERIAL PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    passwordHash VARCHAR(255),
    googleId VARCHAR(255) UNIQUE,
    avatarUrl VARCHAR(500),
    phone VARCHAR(50),
    cpf VARCHAR(14) UNIQUE,
    address TEXT,
    roleId INT NOT NULL REFERENCES roles(roleId),
    isActive BOOLEAN DEFAULT TRUE,
    emailVerifiedAt TIMESTAMP,
    lastLoginAt TIMESTAMP,
    createdAt TIMESTAMP NOT NULL DEFAULT NOW(),
    updatedAt TIMESTAMP NOT NULL DEFAULT NOW(),
    deletedAt TIMESTAMP
);

-- 4.6 Tabela: rentals
CREATE TABLE rentals (
    rentalId SERIAL PRIMARY KEY,
    rentalCode VARCHAR(20) UNIQUE NOT NULL,
    userId INT NOT NULL REFERENCES users(userId),
    toolId INT NOT NULL REFERENCES tools(toolId),
    startDate DATE NOT NULL,
    expectedEndDate DATE NOT NULL,
    actualEndDate DATE,
    rentalDays INT NOT NULL,
    dailyPrice DECIMAL(10,2) NOT NULL,
    depositAmount DECIMAL(10,2) DEFAULT 0,
    totalAmount DECIMAL(10,2) NOT NULL,
    fineAmount DECIMAL(10,2) DEFAULT 0,
    status rental_status NOT NULL DEFAULT 'pending',
    paymentStatus payment_status NOT NULL DEFAULT 'pending',
    notes TEXT,
    registeredBy INT REFERENCES users(userId),
    createdAt TIMESTAMP NOT NULL DEFAULT NOW(),
    updatedAt TIMESTAMP NOT NULL DEFAULT NOW()
);

-- 4.7 Tabela: rentalStatusHistory
CREATE TABLE rentalStatusHistory (
    historyId SERIAL PRIMARY KEY,
    rentalId INT NOT NULL REFERENCES rentals(rentalId),
    previousStatus VARCHAR(30),
    newStatus VARCHAR(30) NOT NULL,
    changedBy INT NOT NULL REFERENCES users(userId),
    changeReason TEXT,
    createdAt TIMESTAMP NOT NULL DEFAULT NOW()
);

-- 4.8 Tabela: passwordResetTokens
CREATE TABLE passwordResetTokens (
    tokenId SERIAL PRIMARY KEY,
    userId INT NOT NULL REFERENCES users(userId),
    token VARCHAR(255) UNIQUE NOT NULL,
    expiresAt TIMESTAMP NOT NULL,
    usedAt TIMESTAMP,
    createdAt TIMESTAMP NOT NULL DEFAULT NOW()
);

-- 4.9 Tabela: systemSettings
CREATE TABLE systemSettings (
    settingId SERIAL PRIMARY KEY,
    settingKey VARCHAR(100) UNIQUE NOT NULL,
    settingValue TEXT,
    settingGroup VARCHAR(50) NOT NULL,
    description TEXT,
    updatedAt TIMESTAMP NOT NULL DEFAULT NOW()
);

-- Indexes
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_googleId ON users(googleId);
CREATE INDEX idx_users_roleId ON users(roleId);
CREATE INDEX idx_tools_categoryId ON tools(categoryId);
CREATE INDEX idx_tools_status ON tools(status);
CREATE INDEX idx_rentals_userId ON rentals(userId);
CREATE INDEX idx_rentals_toolId ON rentals(toolId);
CREATE INDEX idx_rentals_status ON rentals(status);
CREATE INDEX idx_rentalStatusHistory_rentalId ON rentalStatusHistory(rentalId);
