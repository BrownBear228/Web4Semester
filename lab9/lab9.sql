-- Создание базы данных и таблицы friends
CREATE DATABASE IF NOT EXISTS lab9;
USE lab9;

DROP TABLE IF EXISTS friends;
CREATE TABLE friends (
    id INT(11) NOT NULL AUTO_INCREMENT,
    surname VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    patronymic VARCHAR(100) DEFAULT NULL,
    gender ENUM('М','Ж') DEFAULT NULL,
    birthdate DATE DEFAULT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    comment TEXT DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Начальные тестовые записи
INSERT INTO friends (surname, name, patronymic, gender, birthdate, phone, address, email, comment) VALUES
('Иванов', 'Иван', 'Иванович', 'М', '1990-05-15', '+7-999-123-45-67', 'Москва, ул. Ленина, д.1', 'ivan@example.com', 'Коллега'),
('Петрова', 'Анна', 'Сергеевна', 'Ж', '1985-08-22', '+7-999-234-56-78', 'Санкт-Петербург, Невский пр., д.10', 'anna@example.com', 'Подруга'),
('Сидоров', 'Петр', 'Алексеевич', 'М', '2000-12-01', '+7-999-345-67-89', 'Казань, ул. Пушкина, д.5', 'petr@example.com', 'Студент'),
('Кузнецова', 'Екатерина', 'Дмитриевна', 'Ж', '1995-03-10', '+7-999-456-78-90', 'Новосибирск, Красный пр., д.12', 'katya@example.com', 'Знакомая');
