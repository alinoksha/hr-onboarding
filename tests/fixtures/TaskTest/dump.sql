INSERT INTO companies(id, name) VALUES
    (1, 'Ronas IT'),
    (2, 'test company');

INSERT INTO users(id, first_name, last_name, email, password, role_id, date_of_birth, phone, position, starts_on, hr_id, manager_id, lead_id, avatar_id, company_id) VALUES
    (1, 'Billy', 'Coleman', 'billy.coleman@example.com', '$2a$12$b.zlOt1gxlQBy5c8LBV2B.r6x70he0721wBnegmAdKwP9pdZLkcCe', 1, '1986-05-20', '+79535482530', 'admin', '2022-04-16 00:00:00', null, null, null, null, 1),
    (2, 'Charlotte', 'Lyons', 'flavell@example.com', '$2a$12$h.zlOt1gxlQBy5c8LBV2F.r6x70he0721wBnegmAdKwP9pdZLkcCe', 2, '1992-12-04', '89255892221', 'manager', '2022-04-21 00:00:00', 1, 1, 1, null, 1),
    (3, 'Andrew',  'Montgomery', 'retoh@example.com', '$2a$12$b.zlOt1gxlQBy5c8LBV2B.r6x70he0789wBnegmAdKwP9pdZLkmCe', 3, '2001-06-30', '89162002943', 'intern', '2022-04-26 00:00:00', 2, 2, 1, null, 1),
    (4, 'Patricia',  'Sanders', 'patricia@example.com', '$2a$12$b.zlOt1gxlQBy5c8LBV2B.r8x70he0787wBnegmAdKwP9udZLlmCi', 1, '2001-06-30', '89162002940', 'intern', '2022-04-26 00:00:00', 2, 2, 1, null, 2);

INSERT INTO media(name, user_id, link, company_id) VALUES
    ('file.png', 1 , 'file.png', null),
    ('Category Photo photo', 1, 'http://localhost/test1.jpg', 1),
    ('Deleted photo', 2, 'http://localhost/test3.jpg', 1),
    ('Photo', 2, 'http://localhost/test4.jpg', 1);

INSERT INTO scripts(id, title, description, cover_id, company_id) VALUES
    (1, 'title', 'description', 1, 1),
    (2, 'title1', 'description', 1, 1),
    (3, 'title2', 'description', 1, 1),
    (4, 'title3', 'description', 1, 2);

INSERT INTO script_user(id, user_id, script_id) VALUES
    (1, 1, 1),
    (2, 2, 2),
    (3, 3, 3);

INSERT INTO tasks(id, title, content, response_type, response_options, expected_response, script_id) VALUES
    (1, 'title1', 'content1', 'radio', '["slack", "telegram", "whatsapp"]', '["telegram"]', 1),
    (2, 'title2', 'content2', 'text', null, '"correct answer"', 1),
    (3, 'title3', 'content3', 'media', null, null, 3),
    (4, 'title4', 'content4', 'media', null, null, 4);
