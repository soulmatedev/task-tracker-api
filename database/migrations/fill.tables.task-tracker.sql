-- Заплнить таблицу "Role"
INSERT INTO "Role"(id, name)
VALUES (0, 'Исполнитель'),
       (1, 'Наблюдатель'),
       (2,'Администратор');

-- Заполнить таблицу "Account"
INSERT INTO "Account"(email, password, login, "profilePictureUrl", role)
VALUES
    ('soulmate@mail.ru', '123321', 'soulmate', null, 0),
    ('vasya@mail.ru', '123321', 'vasya', null, 1),
    ('michail@mail.ru', '123321', 'michail12345', null, 2);

-- Заплнить таблицу "Status"
INSERT INTO "Status"(id, name)
VALUES (0, 'Ожидает'),
       (1, 'В работе'),
       (2, 'Приостановлена'),
       (3,'Ожидает проверки'),
       (4,'Завершена'),
       (5,'Отменена');
