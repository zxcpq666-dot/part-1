<?php

session_start();

require_once 'config.php';

require_once 'src/Database.php';

require_once 'src/Repositories/ClientRepository.php';

$pdo = Database::getConnection();

$clientRepository = new ClientRepository($pdo);

$action = $_GET['action'] ?? 'list';

$id = (int)($_GET['id'] ?? 0);

if (empty($_SESSION['csrf_token'])) {

    $_SESSION['csrf_token'] =
        bin2hex(random_bytes(32));
}

switch ($action) {

    case 'create':

        $errors = [];

        $old = [
            'full_name' => '',
            'phone' => '',
            'email' => '',
            'age' => ''
        ];

        if (
            $_SERVER['REQUEST_METHOD']
            === 'POST'
        ) {

            if (
                $_POST['csrf_token']
                !== $_SESSION['csrf_token']
            ) {

                die('CSRF error');
            }

            $fullName = trim(
                $_POST['full_name']
            );

            $phone = trim(
                $_POST['phone']
            );

            $email = trim(
                $_POST['email']
            );

            $age = (int)$_POST['age'];

            $old = [
                'full_name' => $fullName,
                'phone' => $phone,
                'email' => $email,
                'age' => $age
            ];

            if ($fullName === '') {

                $errors['full_name'] =
                    'Введите имя';
            }

            if (
                !preg_match(
                    '/^\+?[0-9]{10,15}$/',
                    $phone
                )
            ) {

                $errors['phone'] =
                    'Некорректный телефон';
            }

            if (
                !filter_var(
                    $email,
                    FILTER_VALIDATE_EMAIL
                )
            ) {

                $errors['email'] =
                    'Некорректный email';
            }

            if ($age < 14) {

                $errors['age'] =
                    'Возраст должен быть больше 14';
            }

            if (empty($errors)) {

                $clientRepository->create([
                    'full_name' => $fullName,
                    'phone' => $phone,
                    'email' => $email,
                    'age' => $age
                ]);

                $_SESSION['success'] =
                    'Клиент успешно создан';

                header(
                    'Location: index.php'
                );

                exit;
            }
        }

?>

<!DOCTYPE html>

<html lang="ru">

<head>

    <meta charset="UTF-8">

    <title>
        Создание клиента
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body class="container mt-5">

<h1 class="mb-4">
    Создание клиента
</h1>

<form method="POST" novalidate>

    <input
        type="hidden"
        name="csrf_token"
        value="<?= $_SESSION['csrf_token'] ?>"
    >

    <div class="mb-3">

        <label class="form-label">
            Имя *
        </label>

        <input
            type="text"
            name="full_name"
            required
            class="form-control
            <?= isset($errors['full_name'])
                ? 'is-invalid'
                : '' ?>"
            value="<?= htmlspecialchars(
                $old['full_name']
            ) ?>"
        >

        <?php if (
            isset($errors['full_name'])
        ): ?>

            <div class="invalid-feedback">

                <?= htmlspecialchars(
                    $errors['full_name']
                ) ?>

            </div>

        <?php endif; ?>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Телефон *
        </label>

        <input
            type="tel"
            name="phone"
            required
            pattern="^\+?[0-9]{10,15}$"
            class="form-control
            <?= isset($errors['phone'])
                ? 'is-invalid'
                : '' ?>"
            value="<?= htmlspecialchars(
                $old['phone']
            ) ?>"
        >

        <?php if (
            isset($errors['phone'])
        ): ?>

            <div class="invalid-feedback">

                <?= htmlspecialchars(
                    $errors['phone']
                ) ?>

            </div>

        <?php endif; ?>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Email *
        </label>

        <input
            type="email"
            name="email"
            required
            class="form-control
            <?= isset($errors['email'])
                ? 'is-invalid'
                : '' ?>"
            value="<?= htmlspecialchars(
                $old['email']
            ) ?>"
        >

        <?php if (
            isset($errors['email'])
        ): ?>

            <div class="invalid-feedback">

                <?= htmlspecialchars(
                    $errors['email']
                ) ?>

            </div>

        <?php endif; ?>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Возраст *
        </label>

        <input
            type="number"
            name="age"
            required
            min="14"
            class="form-control
            <?= isset($errors['age'])
                ? 'is-invalid'
                : '' ?>"
            value="<?= htmlspecialchars(
                $old['age']
            ) ?>"
        >

        <?php if (
            isset($errors['age'])
        ): ?>

            <div class="invalid-feedback">

                <?= htmlspecialchars(
                    $errors['age']
                ) ?>

            </div>

        <?php endif; ?>

    </div>

    <button
        type="submit"
        class="btn btn-success"
    >
        Создать
    </button>

    <a
        href="index.php"
        class="btn btn-secondary"
    >
        Назад
    </a>

</form>

</body>
</html>

<?php

break;

case 'edit':

    $client =
        $clientRepository
            ->findById($id);

    if (!$client) {

        die('Клиент не найден');
    }

    $errors = [];

    if (
        $_SERVER['REQUEST_METHOD']
        === 'POST'
    ) {

        if (
            $_POST['csrf_token']
            !== $_SESSION['csrf_token']
        ) {

            die('CSRF error');
        }

        $fullName = trim(
            $_POST['full_name']
        );

        $phone = trim(
            $_POST['phone']
        );

        $email = trim(
            $_POST['email']
        );

        $age = (int)$_POST['age'];

        if ($fullName === '') {

            $errors['full_name'] =
                'Введите имя';
        }

        if (
            !preg_match(
                '/^\+?[0-9]{10,15}$/',
                $phone
            )
        ) {

            $errors['phone'] =
                'Некорректный телефон';
        }

        if (
            !filter_var(
                $email,
                FILTER_VALIDATE_EMAIL
            )
        ) {

            $errors['email'] =
                'Некорректный email';
        }

        if ($age < 14) {

            $errors['age'] =
                'Возраст должен быть больше 14';
        }

        if (empty($errors)) {

            $clientRepository->update(
                $id,
                [
                    'full_name' =>
                        $fullName,

                    'phone' =>
                        $phone,

                    'email' =>
                        $email,

                    'age' =>
                        $age
                ]
            );

            $_SESSION['success'] =
                'Клиент обновлён';

            header(
                'Location: index.php'
            );

            exit;
        }
    }

?>

<!DOCTYPE html>

<html lang="ru">

<head>

    <meta charset="UTF-8">

    <title>
        Редактирование клиента
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body class="container mt-5">

<h1 class="mb-4">
    Редактирование клиента
</h1>

<form method="POST">

    <input
        type="hidden"
        name="csrf_token"
        value="<?= $_SESSION['csrf_token'] ?>"
    >

    <div class="mb-3">

        <label class="form-label">
            Имя
        </label>

        <input
            type="text"
            name="full_name"
            required
            class="form-control"
            value="<?= htmlspecialchars(
                $_POST['full_name']
                ?? $client['full_name']
            ) ?>"
        >

    </div>

    <div class="mb-3">

        <label class="form-label">
            Телефон
        </label>

        <input
            type="tel"
            name="phone"
            required
            class="form-control"
            value="<?= htmlspecialchars(
                $_POST['phone']
                ?? $client['phone']
            ) ?>"
        >

    </div>

    <div class="mb-3">

        <label class="form-label">
            Email
        </label>

        <input
            type="email"
            name="email"
            required
            class="form-control"
            value="<?= htmlspecialchars(
                $_POST['email']
                ?? $client['email']
            ) ?>"
        >

    </div>

    <div class="mb-3">

        <label class="form-label">
            Возраст
        </label>

        <input
            type="number"
            name="age"
            required
            min="14"
            class="form-control"
            value="<?= htmlspecialchars(
                $_POST['age']
                ?? $client['age']
            ) ?>"
        >

    </div>

    <button
        type="submit"
        class="btn btn-primary"
    >
        Сохранить
    </button>

</form>

</body>
</html>

<?php

break;

case 'delete':

    if (
        $clientRepository
            ->hasAppointments($id)
    ) {

        $_SESSION['error'] =
            'Нельзя удалить клиента,
             у которого есть записи';

    } else {

        $clientRepository->delete($id);

        $_SESSION['success'] =
            'Клиент удалён';
    }

    header('Location: index.php');

    exit;

default:

    $clients =
        $clientRepository->findAll();

?>

<!DOCTYPE html>

<html lang="ru">

<head>

    <meta charset="UTF-8">

    <title>
        Клиенты
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body class="container mt-5">

<h1 class="mb-4">
    Клиенты центра профориентации
</h1>

<?php if (
    !empty($_SESSION['success'])
): ?>

    <div class="alert alert-success">

        <?= htmlspecialchars(
            $_SESSION['success']
        ) ?>

    </div>

    <?php
    unset($_SESSION['success']);
    ?>

<?php endif; ?>

<?php if (
    !empty($_SESSION['error'])
): ?>

    <div class="alert alert-danger">

        <?= htmlspecialchars(
            $_SESSION['error']
        ) ?>

    </div>

    <?php
    unset($_SESSION['error']);
    ?>

<?php endif; ?>

<a
    href="index.php?action=create"
    class="btn btn-success mb-3"
>
    Добавить клиента
</a>

<table class="table table-bordered">

    <tr>

        <th>ID</th>

        <th>Имя</th>

        <th>Телефон</th>

        <th>Email</th>

        <th>Возраст</th>

        <th>Действия</th>

    </tr>

    <?php foreach (
        $clients as $client
    ): ?>

        <tr>

            <td>
                <?= $client['id'] ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $client['full_name']
                ) ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $client['phone']
                ) ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $client['email']
                ) ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $client['age']
                ) ?>
            </td>

            <td>

                <a
                    href="index.php?action=edit&id=<?= $client['id'] ?>"
                    class="btn btn-primary btn-sm"
                >
                    Редактировать
                </a>

                <a
                    href="index.php?action=delete&id=<?= $client['id'] ?>"
                    class="btn btn-danger btn-sm"
                    onclick="return confirm('Удалить клиента?')"
                >
                    Удалить
                </a>

            </td>

        </tr>

    <?php endforeach; ?>

</table>

</body>
</html>

<?php
}
