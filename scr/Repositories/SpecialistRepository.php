```php id="v9m4qx"
<?php

class SpecialistRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM specialists
             ORDER BY id DESC"
        );

        return $stmt->fetchAll();
    }

    public function findById(
        int $id
    ): ?array {

        $stmt = $this->pdo->prepare(
            "SELECT * FROM specialists
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
            "INSERT INTO specialists
            (
                full_name,
                specialization,
                experience_years
            )
            VALUES
            (
                :full_name,
                :specialization,
                :experience_years
            )"
        );

        return $stmt->execute([
            'full_name' =>
                $data['full_name'],

            'specialization' =>
                $data['specialization'],

            'experience_years' =>
                $data['experience_years']
        ]);
    }

    public function update(
        int $id,
        array $data
    ): bool {

        $stmt = $this->pdo->prepare(
            "UPDATE specialists
            SET
                full_name = :full_name,
                specialization = :specialization,
                experience_years = :experience_years
            WHERE id = :id"
        );

        return $stmt->execute([
            'full_name' =>
                $data['full_name'],

            'specialization' =>
                $data['specialization'],

            'experience_years' =>
                $data['experience_years'],

            'id' => $id
        ]);
    }

    public function delete(
        int $id
    ): bool {

        $stmt = $this->pdo->prepare(
            "DELETE FROM specialists
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }

    public function hasAppointments(
        int $specialistId
    ): bool {

        $stmt = $this->pdo->prepare(
            "SELECT COUNT(*)
             FROM appointments
             WHERE specialist_id = :id"
        );

        $stmt->execute([
            'id' => $specialistId
        ]);

        return $stmt->fetchColumn() > 0;
    }
}
```
