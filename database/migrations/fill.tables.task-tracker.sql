-- Заплнить таблицу "Role"
INSERT INTO "Role"(id, name)
VALUES (0, 'Исполнитель'),
       (1, 'Наблюдатель'),
       (2,'Администратор');

-- Заплнить таблицу "Status"
INSERT INTO "Status"(id, name)
VALUES (0, 'Ожидает'),
       (1, 'В работе'),
       (2, 'Приостановлена'),
       (3,'Ожидает проверки'),
       (4,'Завершена'),
       (5,'Отменена');
