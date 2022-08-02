INSERT INTO companies(id, name) VALUES
    (1, 'Ronas IT'),
    (2, 'test company');

INSERT INTO users(id, first_name, last_name, email, password, remember_token, role_id, created_at, updated_at, date_of_birth, phone, position, starts_on, hr_id, manager_id, lead_id, avatar_id, company_id) VALUES
    (1, 'Billy', 'Coleman', 'billy.coleman@example.com', '$2a$12$b.zlOt1gxlQBy5c8LBV2B.r6x70he0721wBnegmAdKwP9pdZLkcCe', null, 1, null, null, '1986-05-20', '+79535482530', 'admin', '2022-04-16 00:00:00', null, null, null, null, 1),
    (2, 'Charlotte', 'Lyons', 'flavell@example.com', '$2a$12$h.zlOt1gxlQBy5c8LBV2F.r6x70he0721wBnegmAdKwP9pdZLkcCe', null, 2, null, null, '1992-12-04', '89255892221', 'manager', '2022-04-21 00:00:00', null, null, null, null, 1),
    (3, 'Andrew',  'Montgomery', 'retoh@example.com', '$2a$12$b.zlOt1gxlQBy5c8LBV2B.r6x70he0789wBnegmAdKwP9pdZLkmCe', null, 3, null, null, '2001-06-30', '89162002943', 'intern', '2022-04-26 00:00:00', null, null, null, null, 1),
    (4, 'David',  'Lindsey', 'dlindsey@example.com', '$2a$12$b.zlOt1gxlQBy5c8LBV2B.r6x70he0789wBnignAdFwP9plZLkmCu', null, 4, null, null, '1995-09-23', '89272002843', 'super admin', null, null, null, null, null, null);

INSERT INTO setting_groups(name) VALUES ('company'), ('chat bot');

INSERT INTO settings(name, type, data, sorting_order, setting_group_id, company_id) VALUES
    ('company logo', 'photo', null, 1, 1, 1),
    ('company name', 'text', 'RonasIT', 2, 1, 1),
    ('welcome message', 'textarea', 'Welcome aboard! Can`t wait for you to start the process.', 3, 1, 1),
    ('bot profile image', 'photo', null, 1, 2, 1),
    ('bot name', 'text', 'Anna', 2, 2, 1);

