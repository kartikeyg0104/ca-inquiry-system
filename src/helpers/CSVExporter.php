<?php

class CSVExporter {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Export all inquiries to CSV
     */
    public function exportAll($filters = []) {
        try {
            $query = "SELECT * FROM inquiries WHERE 1=1";
            $params = [];

            // Apply filters if provided
            if (!empty($filters['status'])) {
                $query .= " AND status = ?";
                $params[] = $filters['status'];
            }

            if (!empty($filters['from_date'])) {
                $query .= " AND created_at >= ?";
                $params[] = $filters['from_date'] . " 00:00:00";
            }

            if (!empty($filters['to_date'])) {
                $query .= " AND created_at <= ?";
                $params[] = $filters['to_date'] . " 23:59:59";
            }

            $query .= " ORDER BY created_at DESC";

            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $this->generateCSV($data);
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error exporting data: ' . $e->getMessage()];
        }
    }

    /**
     * Export specific inquiries by IDs
     */
    public function exportByIds($ids = []) {
        if (empty($ids)) {
            return ['success' => false, 'message' => 'No IDs provided'];
        }

        try {
            $ids = array_map('intval', $ids);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            
            $stmt = $this->pdo->prepare("SELECT * FROM inquiries WHERE id IN ($placeholders) ORDER BY created_at DESC");
            $stmt->execute($ids);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $this->generateCSV($data, 'inquiries_' . date('Y-m-d_H-i-s'));
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error exporting data: ' . $e->getMessage()];
        }
    }

    /**
     * Generate CSV content from data
     */
    private function generateCSV($data, $filename = null) {
        if (empty($data)) {
            return ['success' => false, 'message' => 'No data to export'];
        }

        $filename = $filename ?? 'inquiries_' . date('Y-m-d_H-i-s');
        $csv = fopen('php://temp', 'r+');

        // Add headers
        $headers = array_keys($data[0]);
        fputcsv($csv, $headers);

        // Add rows
        foreach ($data as $row) {
            fputcsv($csv, $row);
        }

        rewind($csv);
        $csvContent = stream_get_contents($csv);
        fclose($csv);

        return [
            'success' => true,
            'filename' => $filename . '.csv',
            'content' => $csvContent,
            'rows' => count($data)
        ];
    }

    /**
     * Download CSV file
     */
    public function download($data, $filename) {
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        echo $data;
        exit;
    }

    /**
     * Generate statistics CSV
     */
    public function exportStatistics() {
        try {
            $stats = $this->pdo->query("
                SELECT 
                    COUNT(*) as total_inquiries,
                    SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_inquiries,
                    SUM(CASE WHEN status = 'contacted' THEN 1 ELSE 0 END) as contacted_inquiries,
                    SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed_inquiries,
                    DATE_FORMAT(MIN(created_at), '%Y-%m-%d') as earliest_inquiry,
                    DATE_FORMAT(MAX(created_at), '%Y-%m-%d') as latest_inquiry
                FROM inquiries
            ")->fetch(PDO::FETCH_ASSOC);

            $csv = fopen('php://temp', 'r+');
            fputcsv($csv, ['Metric', 'Value']);
            foreach ($stats as $key => $value) {
                fputcsv($csv, [ucfirst(str_replace('_', ' ', $key)), $value]);
            }

            rewind($csv);
            $csvContent = stream_get_contents($csv);
            fclose($csv);

            return [
                'success' => true,
                'filename' => 'inquiry_statistics_' . date('Y-m-d_H-i-s') . '.csv',
                'content' => $csvContent
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error generating statistics: ' . $e->getMessage()];
        }
    }
}
