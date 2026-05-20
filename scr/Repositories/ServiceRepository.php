<?php

class ServiceRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM services
             ORDER BY id DESC"
        );

        return $stmt->fetchAll();
    }

    public function findById(
        int $id
    ): ?array {

        $stmt = $this->pdo->prepare(
            "SELECT * FROM services
             WHERE id = :id"
        );

        $stmt->execute([
            'id' => $id
        ]);

        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function create(
        array $data
    ): bool {

        $stmt = $this->pdo->prepare(
            "INSERT INTO services
            (
                title,
                price,
                duration_minutes
            )
            VALUES
            (
                :title,
                :price,
                :duration_minutes
            )"
        );

        return $stmt->execute([
            'title' =>
                $data['title'],

            'price' =>
                $data['price'],

            'duration_minutes' =>
                $data['duration_minutes']
        ]);
    }

    public function update(
        int $id,
        array $data
    ): bool {

        $stmt = $this->pdo->prepare(
            "UPDATE services
            SET
                title = :title,
                price = :price,
                duration_minutes = :duration_minutes
            WHERE id = :id"
        );

        return $stmt->execute([
            'title' =>
                $data['title'],

            'price' =>
                $data['price'],

            'duration_minutes' =>
                $data['duration_minutes'],

            'id' => $id
        ]);
    }

    public function delete(
        int $id
    ): bool {

        $stmt = $this->pdo->prepare(
            "DELETE FROM services
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }
}
