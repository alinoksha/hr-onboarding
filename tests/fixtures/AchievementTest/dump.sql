INSERT INTO companies(id, name) VALUES
    (1, 'Ronas IT'),
    (2, 'test company');

INSERT INTO users(id, first_name, last_name, email, password, remember_token, role_id, created_at, updated_at, company_id) VALUES
    (1, 'Alien South1',  'dffdfdfdssfd1', '1fidel.kutch@example.com', '$2a$12$b.zlOt1gxlQBy5c8LBV2B.r6x70he0721wBnegmAdKwP9pdZLkcCe', null, 1, '2016-10-20 11:05:00', '2016-10-20 11:05:00', 1),
    (2, 'Alien South2',  'dffdfdfdssfd2', '2fidel.kutch@example.com', '$2a$12$b.zlOt1gxlQBy5c8LBV2B.r6x70he0721wBnegmAdKwP9pdZLkcCe', null, 2, '2016-10-20 11:05:00', '2016-10-20 11:05:00', 1),
    (3, 'Alien South3',  'dffdfdfdssfd3', '3fidel.kutch@example.com', '$2a$12$b.zlOt1gxlQBy5c8LBV2B.r6x70he0721wBnegmAdKwP9pdZLkcCe', null, 3, '2016-10-20 11:05:00', '2016-10-20 11:05:00', 1),
    (4, 'Alien South4',  'dffdfdfdssfd4', '4fidel.kutch@example.com', '$2a$12$b.zlOt1gxlQBy5c8LBV2B.r6x70he0721iBnegmAdKwP9phZLkcCi', null, 4, '2016-10-20 11:05:00', '2016-10-20 11:05:00', null),
    (5, 'Alien South5',  'dffdfdfdssfd5', '5fidel.kutch@example.com', '$2a$12$b.zlOt1gzlQUy5c8LBV2B.r6x78he0721iBnetnAdRwP9phZLkcCi', null, 1, '2016-10-20 11:05:00', '2016-10-20 11:05:00', 2);

INSERT INTO media(name, user_id, link, created_at, updated_at, company_id) VALUES
    ('file.png', 1 , 'file.png', '2016-10-20 11:05:00', '2016-10-20 11:05:00', 1),
    ('Category Photo photo', 1, 'http://localhost/test1.jpg', '2016-10-20 11:05:00', '2016-10-20 11:05:00', 1),
    ('Deleted photo', 2, 'http://localhost/test3.jpg', '2016-10-20 11:05:00', '2016-10-20 11:05:00', 1),
    ('Photo', 2, 'http://localhost/test4.jpg', '2016-10-20 11:05:00', '2016-10-20 11:05:00', 2);

INSERT INTO scripts(id, title, description, cover_id, created_at, updated_at, company_id) VALUES
    (1, 'title', 'description', 1,  null, null, 1),
    (2, 'title1', 'description1', 1,  null, null, 1),
    (3, 'title2', 'description2', 1,  null, null, 1),
    (4, 'title3', 'description3', 4,  null, null, 2),
    (5, 'title4', 'description4', 1,  null, null, 1);

INSERT INTO achievements(script_id, title, incomplete_cover_id, complete_cover_id, incomplete_message, complete_message, created_at, updated_at) VALUES
    (1, 'title1', 1, 2, 'msg', 'msg',  null, null),
    (2, 'title2', 1, 1, 'msg', 'msg',  null, null),
    (4, 'title3', 4, 4, 'msg', 'msg',  null, null);
