<?php

class SearchFilter {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Advanced search with multiple filter options
     */
    public function search($filters = []) {
        $query = "SELECT * FROM inquiries WHERE 1=1";
        $params = [];

        // Full name search
        if (!empty($filters['name'])) {
            $query .= " AND full_name LIKE ?";
            $params[] = "%{$filters['name']}%";
        }

        // Email search
        if (!empty($filters['email'])) {
            $query .= " AND email LIKE ?";
            $params[] = "%{$filters['email']}%";
        }

        // Mobile search
        if (!empty($filters['mobile'])) {
            $query .= " AND mobile LIKE ?";
            $params[] = "%{$filters['mobile']}%";
        }

        // Status filter
        if (!empty($filters['status']) && in_array($filters['status'], ['new', 'contacted', 'closed'])) {
            $query .= " AND status = ?";
            $params[] = $filters['status'];
        }

        // Date range filter
        if (!empty($filters['from_date'])) {
            $query .= " AND created_at >= ?";
            $params[] = $filters['from_date'] . " 00:00:00";
        }

        if (!empty($filters['to_date'])) {
            $query .= " AND created_at <= ?";
            $params[] = $filters['to_date'] . " 23:59:59";
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = strtoupper($filters['sort_order'] ?? 'DESC');
        $allowedFields = ['id', 'full_name', 'email', 'status', 'created_at'];
        
        if (in_array($sortBy, $allowedFields) && in_array($sortOrder, ['ASC', 'DESC'])) {
            $query .= " ORDER BY $sortBy $sortOrder";
        }

        // Pagination
        $limit = (int)($filters['limit'] ?? 10);
        $offset = (int)($filters['offset'] ?? 0);
        $query .= " LIMIT $limit OFFSET $offset";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get total count for pagination
     */
    public function getCount($filters = []) {
        $query = "SELECT COUNT(*) as total FROM inquiries WHERE 1=1";
        $params = [];

        if (!empty($filters['name'])) {
            $query .= " AND full_name LIKE ?";
            $params[] = "%{$filters['name']}%";
        }

        if (!empty($filters['status']) && in_array($filters['status'], ['new', 'contacted', 'closed'])) {
            $query .= " AND status = ?";
            $params[] = $filters['status'];
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    /**
     * Get unique statuses for filter dropdown
     */
    public function getAvailableStatuses() {
        $stmt = $this->pdo->query("SELECT DISTINCT status FROM inquiries ORDER BY status");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
