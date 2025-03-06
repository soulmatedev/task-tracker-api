-- Заплнить таблицу "Role"
INSERT INTO "Role"(id, name)
VALUES (0, 'Исполнитель'),
       (1, 'Наблюдатель'),
       (2,'Администратор');

-- Заполнить таблицу "Account"
INSERT INTO "User"(id, email, password, login, profile_picture_url, role)
VALUES
    (0, 'user1@mail.ru', '123', 'user123', null, 0),
    (1, 'user2@mail.ru', '1234', 'user1234', null, 1),
    (2, 'admin@mail.ru', '12345', 'user12345', null, 2);

-- Заплнить таблицу "Status"
INSERT INTO "Status"(id, name)
VALUES (0, 'Ожидает'),
       (1, 'В работе'),
       (2, 'Приостановлена'),
       (3,'Ожидает проверки'),
       (4,'Завершена'),
       (5,'Отменена');
