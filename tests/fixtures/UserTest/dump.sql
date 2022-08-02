ALTER TABLE users DISABLE TRIGGER ALL;

INSERT INTO companies(id, name) VALUES
    (1, 'Ronas IT'),
    (2, 'test company');

INSERT INTO users(id, first_name, last_name, email, password, remember_token, role_id, created_at, updated_at, date_of_birth, phone, position, starts_on, hr_id, manager_id, lead_id, avatar_id, deleted_at, company_id) VALUES
    (1, 'Billy', 'Coleman', 'billy.coleman@example.com', '$2a$12$b.zlOt1gxlQBy5c8LBV2B.r6x70he0721wBnegmAdKwP9pdZLkcCe', null, 1, null, null, '1986-05-20', '+79535482530', 'admin', '2022-04-16 00:00:00', null, null, null, null, null, 1),
    (2, 'Charlotte', 'Lyons', 'flavell@example.com', '$2a$12$h.zlOt1gxlQBy5c8LBV2F.r6x70he0721wBnegmAdKwP9pdZLkcCe', null, 2, null, null, '1992-12-04', '89255892221', 'manager', '2022-04-21 00:00:00', 1, 1, 1, 1, null, 1),
    (3, 'Andrew',  'Montgomery', 'retoh@example.com', '$2a$12$b.zlOt1gxlQBy5c8LBV2B.r6x70he0789wBnegmAdKwP9pdZLkmCe', null, 3, null, null, '2001-06-30', '89162002943', 'intern', '2022-04-26 00:00:00', 2, 2, 1, 2, null, 1),
    (4, 'Alexey',  'Petrov', 'alex@example.com', '$2a$34$b.zlOt1gxlQBy5c8LBV2B.r6x94he0789oFmigmAdKwP9pdZLkmCo', null, 3, null, null, '2001-06-30', '89182002900', 'hr', '2022-05-09 00:00:00', 2, 2, 1, null, '2018-11-11 11:11:11', 1),
    (5, 'Jason',  'Jackson', 'jason@example-admin.com', '$2a$34$b.zlOt1onlQBy5d8LBV2B.r6x94he0789oFmdgmAsKwP9pdZLkmCu', null, 1, null, null, '1995-09-23', '89482007900', 'admin', '2022-05-09 00:00:00', null, null, null, null, '2018-11-11 11:11:11', 2),
    (6, 'Andrey',  'Danilov', 'andr@example.com', '$2a$34$b.zlOt1gxlQBy5c8LBV2B.r6x94he0789oFmigmAdKwP9pdZLkmCo', null, 4, null, null, '2001-06-30', '89182002900', 'hr', '2022-04-26 00:00:00', 2, 2, 1, 2, null, 1),
    (7, 'Scally',  'Millano', 'scally@example.com', '$2a$34$b.zlOt1gxlQBy5c8LBV2B.r6x94he0789oFmigmAdKwP9pdZLkmCo', null, 1, null, null, '2001-06-30', '89182002900', 'hr', '2022-04-26 00:00:00', 2, 2, 1, 2, null, 1),
    (8, 'Seemee',  'Windows', 'seemee@example.com', '$2a$34$b.zlOt1gxlQBy5c8LBV2B.r6x94he0789oFmigmAdKwP9pdZLkmCo', null, 2, null, null, '2001-06-30', '89182002900', 'hr', '2022-04-26 00:00:00', 2, 2, 1, 2, null, 1);

INSERT INTO media(name, user_id, link, created_at, updated_at, company_id) VALUES
    ('Charlotte Avatar', 1 , 'avatar_1.png', '2016-10-20 11:05:00', '2016-10-20 11:05:00', 1),
    ('Andrew Avatar', 1, 'avatar_2.jpg', '2016-10-20 11:05:00', '2016-10-20 11:05:00', 1);

ALTER TABLE users ENABLE TRIGGER ALL;

INSERT INTO media(name, user_id, link, created_at, updated_at, company_id) VALUES
    ('file.png', 1 , 'file.png', '2016-10-20 11:05:00', '2016-10-20 11:05:00', 1),
    ('Category Photo photo', 1, 'http://localhost/test1.jpg', '2016-10-20 11:05:00', '2016-10-20 11:05:00', 1),
    ('Deleted photo', 2, 'http://localhost/test3.jpg', '2016-10-20 11:05:00', '2016-10-20 11:05:00', 1),
    ('Photo', 2, 'http://localhost/test4.jpg', '2016-10-20 11:05:00', '2016-10-20 11:05:00', 1);

INSERT INTO scripts(id, title, description, cover_id, created_at, updated_at, company_id) VALUES
    (1, 'title', 'description', 1,  null, null, 1),
    (2, 'title1', 'description', 1,  null, null, 1),
    (3, 'title2', 'description', 1,  null, null, 1);

INSERT INTO script_user(id, user_id, script_id, created_at, updated_at) VALUES
    (1, 1, 1, null, null),
    (2, 1, 2, null, null),
    (3, 2, 3, null, null);

INSERT INTO tasks(id, title, content, response_type, response_options, expected_response, script_id) VALUES
    (1, 'title1', 'content1', 'radio', '["slack", "telegram", "whatsapp"]', '["telegram"]', 3),
    (2, 'title2', 'content2', 'text', null, '"correct answer"', 3);

INSERT INTO answers(answer, task_id, user_id) VALUES ('["telegram"]', 1, 2);

INSERT INTO password_resets(email, token, created_at) VALUES
    ('retoh@example.com', '$2a$12$5JpS/asnRpmRYlDXgccXkeUd.Xb2T/uYYp1yk75DakrIUM.Oxe1py', '2018-11-11 11:11:11'),
    ('flavell@example.com', '$2a$12$5JpS/asnRpmRYlDXgccXkeUd.Xb2T/uYYp1yk75DakrIUM.Oxe1py', '2017-11-11 11:11:11');
