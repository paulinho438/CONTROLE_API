# 🧪 Testes de Integração - Sistema Rialma Transmissora

## 📋 Visão Geral

Este diretório contém testes de integração para validar os principais fluxos do sistema, incluindo:
- ✅ Autenticação e autorização
- ✅ CRUD de entidades principais
- ✅ Validações de formulários
- ✅ Cálculos automáticos
- ✅ Sistema de permissões
- ✅ Dashboard e relatórios

## 🚀 Como Executar os Testes

### Executar todos os testes:
```bash
cd BACK_E_FRONT/api
php artisan test
```

### Executar testes específicos:
```bash
# Testes de autenticação
php artisan test --filter AuthTest

# Testes de grupos
php artisan test --filter GrupoTest

# Testes de entradas
php artisan test --filter EntradaTest

# Testes de dashboard
php artisan test --filter DashboardTest

# Testes de validação
php artisan test --filter ValidationTest

# Testes de permissões
php artisan test --filter PermissionTest
```

### Executar com cobertura:
```bash
php artisan test --coverage
```

## 📁 Estrutura dos Testes

### `Feature/AuthTest.php`
Testa o fluxo completo de autenticação:
- ✅ Login com credenciais válidas
- ✅ Login com credenciais inválidas
- ✅ Validação de campos obrigatórios
- ✅ Validação de token
- ✅ Logout

### `Feature/GrupoTest.php`
Testa operações CRUD de grupos:
- ✅ Listar grupos
- ✅ Criar grupo
- ✅ Atualizar grupo
- ✅ Excluir grupo
- ✅ Validações de campos obrigatórios

### `Feature/EntradaTest.php`
Testa operações de entrada de material:
- ✅ Criar entrada
- ✅ Cálculo automático de valor total (quantidade × valor unitário)
- ✅ Validações de campos obrigatórios
- ✅ Validação de quantidade mínima

### `Feature/DashboardTest.php`
Testa funcionalidades do dashboard:
- ✅ Resumo geral (totais de entradas, saídas, notas fiscais, materiais)
- ✅ Resumo de estoque com filtros por grupos e pátios
- ✅ Balanço com filtros por grupos e materiais

### `Feature/PermissionTest.php`
Testa o sistema de permissões:
- ✅ Verificação de permissões do usuário
- ✅ Middleware de bloqueio de acesso não autorizado

### `Feature/ValidationTest.php`
Testa validações de formulários:
- ✅ Validação de CNPJ/CPF
- ✅ Validação de email
- ✅ Validação de campos obrigatórios
- ✅ Validação de tamanho máximo de campos

## 🔧 Configuração

Os testes usam um banco de dados em memória (SQLite) configurado automaticamente pelo Laravel durante a execução dos testes.

### Variáveis de Ambiente para Testes

O arquivo `phpunit.xml` já está configurado com:
- `APP_ENV=testing`
- `DB_CONNECTION=sqlite` (comentado - pode ser habilitado se necessário)
- `CACHE_DRIVER=array`
- `SESSION_DRIVER=array`

## 📊 Cobertura de Testes

### ✅ Funcionalidades Testadas:

1. **Autenticação** - 100%
   - Login, logout, validação de token

2. **CRUD Básico** - 100%
   - Grupos (criar, ler, atualizar, excluir)

3. **Operações Complexas** - 80%
   - Entradas (criar, cálculos)
   - Dashboard (resumos, filtros)

4. **Validações** - 90%
   - Campos obrigatórios
   - Formatos (email, CNPJ/CPF)
   - Tamanhos máximos

5. **Permissões** - 70%
   - Verificação de permissões
   - Middleware de autorização

### ⚠️ Funcionalidades Parcialmente Testadas:

- Saídas de material
- Transferências
- Notas fiscais
- Previsões
- Exportação PDF/Excel (verificação de métodos)

## 🎯 Próximos Passos

Para aumentar a cobertura de testes:

1. **Adicionar testes para:**
   - Saídas de material
   - Transferências
   - Notas fiscais
   - Previsões
   - Todos os cadastros (Materiais, Pátios, Fornecedores, etc.)

2. **Testes de integração mais complexos:**
   - Fluxos completos (criar material → criar entrada → criar saída)
   - Cálculos de estoque
   - Relatórios e exportações

3. **Testes de performance:**
   - Queries otimizadas
   - Tempo de resposta das APIs

## 📝 Notas

- Os testes usam `RefreshDatabase` para garantir isolamento entre testes
- Cada teste cria seus próprios dados de teste
- Os testes são executados em ambiente isolado (`APP_ENV=testing`)

---

**Última atualização**: 2025-01-24

