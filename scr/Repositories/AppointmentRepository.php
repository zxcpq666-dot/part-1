<?php

class AppointmentRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query(
            "SELECT
                appointments.*,
                clients.full_name AS client_name,
                specialists.full_name AS specialist_name,
                services.title AS service_title
             FROM appointments
             JOIN clients
                ON appointments.client_id = clients.id
             JOIN specialists
                ON appointments.specialist_id = specialists.id
             JOIN services
                ON appointments.service_id = services.id
             ORDER BY appointment_datetime DESC"
        );

        return $stmt->fetchAll();
    }

    public function findById(
        int $id
    ): ?array {

        $stmt = $this->pdo->prepare(
            "SELECT * FROM appointments
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
            "INSERT INTO appointments
            (
                client_id,
                specialist_id,
                service_id,
                appointment_datetime,
                status
            )
            VALUES
            (
                :client_id,
                :specialist_id,
                :service_id,
                :appointment_datetime,
                :status
            )"
        );

        return $stmt->execute([
            'client_id' =>
                $data['client_id'],

            'specialist_id' =>
                $data['specialist_id'],

            'service_id' =>
                $data['service_id'],

            'appointment_datetime' =>
                $data['appointment_datetime'],

            'status' =>
                $data['status']
        ]);
    }

    public function update(
        int $id,
        array $data
    ): bool {

        $stmt = $this->pdo->prepare(
            "UPDATE appointments
            SET
                client_id = :client_id,
                specialist_id = :specialist_id,
                service_id = :service_id,
                appointment_datetime =
                    :appointment_datetime,
                status = :status
            WHERE id = :id"
        );

        return $stmt->execute([
            'client_id' =>
                $data['client_id'],

            'specialist_id' =>
                $data['specialist_id'],

            'service_id' =>
                $data['service_id'],

            'appointment_datetime' =>
                $data['appointment_datetime'],

            'status' =>
                $data['status'],

            'id' => $id
        ]);
    }

    public function delete(
        int $id
    ): bool {

        $stmt = $this->pdo->prepare(
            "DELETE FROM appointments
             WHERE id = :id"
        );

        return $stmt->execute([
            'id' => $id
        ]);
    }
}
