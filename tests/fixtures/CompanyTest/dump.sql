ALTER TABLE users DISABLE TRIGGER ALL;

INSERT INTO companies(id, name) VALUES
    (1, 'Ronas IT');

INSERT INTO users(id, first_name, last_name, email, password, remember_token, role_id, created_at, updated_at, date_of_birth, phone, position, starts_on, hr_id, manager_id, lead_id, avatar_id, deleted_at, company_id) VALUES
    (1, 'Billy', 'Coleman', 'billy.coleman@example.com', '$2a$12$b.zlOt1gxlQBy5c8LBV2B.r6x70he0721wBnegmAdKwP9pdZLkcCe', null, 1, null, null, '1986-05-20', '+79535482530', 'admin', '2022-04-16 00:00:00', null, null, null, null, null, 1),
    (2, 'Charlotte', 'Lyons', 'flavell@example.com', '$2a$12$h.zlOt1gxlQBy5c8LBV2F.r6x70he0721wBnegmAdKwP9pdZLkcCe', null, 2, null, null, '1992-12-04', '89255892221', 'manager', '2022-04-21 00:00:00', 1, 1, 1, 1, null, 1),
    (3, 'Andrew',  'Montgomery', 'retoh@example.com', '$2a$12$b.zlOt1gxlQBy5c8LBV2B.r6x70he0789wBnegmAdKwP9pdZLkmCe', null, 3, null, null, '2001-06-30', '89162002943', 'intern', '2022-04-26 00:00:00', 2, 2, 1, 2, null, 1);

INSERT INTO media(name, user_id, link, created_at, updated_at, company_id) VALUES
    ('Charlotte Avatar', 1 , 'avatar_1.png', '2016-10-20 11:05:00', '2016-10-20 11:05:00', 1),
    ('Andrew Avatar', 1, 'avatar_2.jpg', '2016-10-20 11:05:00', '2016-10-20 11:05:00', 1);

ALTER TABLE users ENABLE TRIGGER ALL;


