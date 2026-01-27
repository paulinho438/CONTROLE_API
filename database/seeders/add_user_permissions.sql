-- Script SQL para adicionar permissões de usuários
-- Execute este script no banco de dados se preferir inserir diretamente

-- Verificar e inserir permissões de usuários (apenas se não existirem)
IF NOT EXISTS (SELECT 1 FROM permitems WHERE slug = 'usuarios.view')
BEGIN
    INSERT INTO permitems (name, slug, [group], created_at, updated_at)
    VALUES ('Visualizar Usuários', 'usuarios.view', 'Administração', GETDATE(), GETDATE());
END

IF NOT EXISTS (SELECT 1 FROM permitems WHERE slug = 'usuarios.create')
BEGIN
    INSERT INTO permitems (name, slug, [group], created_at, updated_at)
    VALUES ('Criar Usuários', 'usuarios.create', 'Administração', GETDATE(), GETDATE());
END

IF NOT EXISTS (SELECT 1 FROM permitems WHERE slug = 'usuarios.edit')
BEGIN
    INSERT INTO permitems (name, slug, [group], created_at, updated_at)
    VALUES ('Editar Usuários', 'usuarios.edit', 'Administração', GETDATE(), GETDATE());
END

IF NOT EXISTS (SELECT 1 FROM permitems WHERE slug = 'usuarios.delete')
BEGIN
    INSERT INTO permitems (name, slug, [group], created_at, updated_at)
    VALUES ('Excluir Usuários', 'usuarios.delete', 'Administração', GETDATE(), GETDATE());
END

-- Associar as novas permissões ao grupo Administrador (se existir)
DECLARE @AdminGroupId INT;
SELECT @AdminGroupId = id FROM permgroups WHERE name = 'Administrador';

IF @AdminGroupId IS NOT NULL
BEGIN
    -- Associar usuarios.view
    IF NOT EXISTS (SELECT 1 FROM permgroup_permitem WHERE permgroup_id = @AdminGroupId AND permitem_id = (SELECT id FROM permitems WHERE slug = 'usuarios.view'))
    BEGIN
        INSERT INTO permgroup_permitem (permgroup_id, permitem_id)
        SELECT @AdminGroupId, id FROM permitems WHERE slug = 'usuarios.view';
    END

    -- Associar usuarios.create
    IF NOT EXISTS (SELECT 1 FROM permgroup_permitem WHERE permgroup_id = @AdminGroupId AND permitem_id = (SELECT id FROM permitems WHERE slug = 'usuarios.create'))
    BEGIN
        INSERT INTO permgroup_permitem (permgroup_id, permitem_id)
        SELECT @AdminGroupId, id FROM permitems WHERE slug = 'usuarios.create';
    END

    -- Associar usuarios.edit
    IF NOT EXISTS (SELECT 1 FROM permgroup_permitem WHERE permgroup_id = @AdminGroupId AND permitem_id = (SELECT id FROM permitems WHERE slug = 'usuarios.edit'))
    BEGIN
        INSERT INTO permgroup_permitem (permgroup_id, permitem_id)
        SELECT @AdminGroupId, id FROM permitems WHERE slug = 'usuarios.edit';
    END

    -- Associar usuarios.delete
    IF NOT EXISTS (SELECT 1 FROM permgroup_permitem WHERE permgroup_id = @AdminGroupId AND permitem_id = (SELECT id FROM permitems WHERE slug = 'usuarios.delete'))
    BEGIN
        INSERT INTO permgroup_permitem (permgroup_id, permitem_id)
        SELECT @AdminGroupId, id FROM permitems WHERE slug = 'usuarios.delete';
    END
END
