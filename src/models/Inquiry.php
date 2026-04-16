<?php

class Inquiry {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getStats(): array {
        $stmt = $this->pdo->query("SELECT 
            COUNT(*) as total_inquiries,
            SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_inquiries,
            SUM(CASE WHEN status = 'contacted' THEN 1 ELSE 0 END) as contacted_inquiries,
            SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed_inquiries
        FROM inquiries");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll(string $search = '', string $status = ''): array {
        $query = "SELECT * FROM inquiries WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $query .= " AND (full_name LIKE ? OR email LIKE ? OR mobile LIKE ?)";
            $searchParam = "%$search%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }

        if (!empty($status)) {
            $query .= " AND status = ?";
            $params[] = $status;
        }

        $query .= " ORDER BY created_at DESC";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id) {
        $stmt = $this->pdo->prepare("SELECT * FROM inquiries WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool {
        $stmt = $this->pdo->prepare("INSERT INTO inquiries (full_name, email, mobile, city, service, message, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['full_name'],
            $data['email'],
            $data['mobile'],
            $data['city'],
            $data['service'],
            $data['message'],
            $data['status'] ?? 'new'
        ]);
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->pdo->prepare("UPDATE inquiries SET full_name = ?, email = ?, mobile = ?, city = ?, service = ?, message = ?, status = ? WHERE id = ?");
        return $stmt->execute([
            $data['full_name'],
            $data['email'],
            $data['mobile'],
            $data['city'],
            $data['service'],
            $data['message'],
            $data['status'],
            $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM inquiries WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateStatus(int $id, string $status): bool {
        if (!in_array($status, ['new', 'contacted', 'closed'])) {
            return false;
        }
        $stmt = $this->pdo->prepare("UPDATE inquiries SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
