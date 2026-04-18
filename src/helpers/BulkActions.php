<?php

class BulkActions {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Delete multiple inquiries by IDs
     */
    public function deleteMultiple($ids = []) {
        if (empty($ids)) {
            return ['success' => false, 'message' => 'No IDs provided'];
        }

        // Sanitize IDs
        $ids = array_map('intval', $ids);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        try {
            $stmt = $this->pdo->prepare("DELETE FROM inquiries WHERE id IN ($placeholders)");
            $stmt->execute($ids);
            
            return [
                'success' => true, 
                'message' => 'Deleted ' . $stmt->rowCount() . ' inquiries'
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error deleting inquiries: ' . $e->getMessage()];
        }
    }

    /**
     * Update status for multiple inquiries
     */
    public function updateStatusBulk($ids = [], $newStatus) {
        if (empty($ids) || empty($newStatus)) {
            return ['success' => false, 'message' => 'Invalid parameters'];
        }

        $validStatuses = ['new', 'contacted', 'closed'];
        if (!in_array($newStatus, $validStatuses)) {
            return ['success' => false, 'message' => 'Invalid status'];
        }

        $ids = array_map('intval', $ids);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        try {
            $query = "UPDATE inquiries SET status = ?, updated_at = NOW() WHERE id IN ($placeholders)";
            $params = array_merge([$newStatus], $ids);
            
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            
            return [
                'success' => true, 
                'message' => 'Updated ' . $stmt->rowCount() . ' inquiries to ' . $newStatus
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error updating status: ' . $e->getMessage()];
        }
    }

    /**
     * Add note/comment to multiple inquiries
     */
    public function addNoteToMultiple($ids = [], $note) {
        if (empty($ids) || empty($note)) {
            return ['success' => false, 'message' => 'Invalid parameters'];
        }

        $ids = array_map('intval', $ids);
        $updatedCount = 0;

        foreach ($ids as $id) {
            try {
                $stmt = $this->pdo->prepare(
                    "UPDATE inquiries SET notes = CONCAT(IFNULL(notes, ''), '\n', ?) WHERE id = ?"
                );
                $stmt->execute([$note, $id]);
                $updatedCount += $stmt->rowCount();
            } catch (Exception $e) {
                continue;
            }
        }

        return [
            'success' => true, 
            'message' => 'Added note to ' . $updatedCount . ' inquiries'
        ];
    }

    /**
     * Export multiple inquiries data
     */
    public function exportData($ids = []) {
        if (empty($ids)) {
            return ['success' => false, 'message' => 'No IDs provided'];
        }

        $ids = array_map('intval', $ids);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        try {
            $stmt = $this->pdo->prepare("SELECT * FROM inquiries WHERE id IN ($placeholders)");
            $stmt->execute($ids);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'success' => true,
                'data' => $data,
                'count' => count($data)
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error exporting data: ' . $e->getMessage()];
        }
    }
}
