# Career Guidance Booking System

Система онлайн-записи для центра профориентации.

---

## Описание проекта

Данный проект представляет собой веб-приложение для управления клиентами центра профориентации.

Проект разработан на PHP с использованием:
- PDO
- MySQL
- Bootstrap 5
- паттерна Repository
- упрощённого MVC

---

## Функциональность

Реализованы следующие возможности:

- просмотр списка клиентов
- создание клиента
- редактирование клиента
- удаление клиента
- серверная валидация данных
- HTML5 validation
- flash-сообщения
- CSRF-защита
- защита от XSS
- работа через PDO prepared statements

---

## Используемые технологии

- PHP 8
- MySQL
- PDO
- Bootstrap 5
- HTML5
- CSS3

---

## Структура проекта

```text
career-guidance/
├── config.php
├── database.sql
├── index.php
├── README.md
├── .gitignore
│
└── src/
    ├── Database.php
    │
    └── Repositories/
        └── ClientRepository.php
```

---

## Установка проекта

### 1. Клонировать репозиторий

```bash
git clone YOUR_REPOSITORY_URL
```

---

### 2. Создать базу данных

Импортировать файл:

```text
database.sql
```

---

### 3. Настроить config.php

```php
define('DB_HOST', 'localhost');

define('DB_NAME', 'career_guidance');

define('DB_USER', 'root');

define('DB_PASS', '');
```

---

### 4. Запустить OpenServer/XAMPP

Открыть в браузере:

```text
http://localhost/career-guidance/
```

---

## Реализованные требования

- CRUD-интерфейс
- MVC-подход
- Repository pattern
- PDO prepared statements
- защита от SQL-инъекций
- серверная валидация
- HTML5 validation
- flash messages
- Bootstrap UI
- CSRF protection
- XSS protection

---

## Безопасность

В проекте используются:

- PDO prepared statements
- htmlspecialchars()
- CSRF token
- валидация пользовательского ввода

---

## Автор

Студент: Попиков Артём Евгеньевич
Группа: 454
Вариант: 22  
Тема: Центр профориентации
