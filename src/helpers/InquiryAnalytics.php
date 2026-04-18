<?php

class InquiryAnalytics {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Get overall statistics
     */
    public function getOverallStats() {
        $stmt = $this->pdo->query("
            SELECT 
                COUNT(*) as total_inquiries,
                SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_inquiries,
                SUM(CASE WHEN status = 'contacted' THEN 1 ELSE 0 END) as contacted_inquiries,
                SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed_inquiries
            FROM inquiries
        ");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get inquiries by status breakdown
     */
    public function getStatusBreakdown() {
        $stmt = $this->pdo->query("
            SELECT status, COUNT(*) as count 
            FROM inquiries 
            GROUP BY status 
            ORDER BY count DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get inquiries per day (last 30 days)
     */
    public function getInquiriesPerDay($days = 30) {
        $stmt = $this->pdo->prepare("
            SELECT 
                DATE(created_at) as date,
                COUNT(*) as count
            FROM inquiries
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
            GROUP BY DATE(created_at)
            ORDER BY date ASC
        ");
        $stmt->execute([$days]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get average response time (if contacted_at exists)
     */
    public function getAverageResponseTime() {
        $stmt = $this->pdo->query("
            SELECT 
                AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours,
                MIN(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as min_hours,
                MAX(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as max_hours
            FROM inquiries
            WHERE updated_at IS NOT NULL
        ");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get top inquiry sources (if available in data)
     */
    public function getInquiryTrends($days = 30) {
        $stmt = $this->pdo->prepare("
            SELECT 
                DATE(created_at) as date,
                status,
                COUNT(*) as count
            FROM inquiries
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
            GROUP BY DATE(created_at), status
            ORDER BY date ASC
        ");
        $stmt->execute([$days]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get conversion rate (closed / total)
     */
    public function getConversionRate() {
        $stmt = $this->pdo->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed,
                ROUND(
                    (SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2
                ) as conversion_rate
            FROM inquiries
        ");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics() {
        $metrics = [
            'overall_stats' => $this->getOverallStats(),
            'status_breakdown' => $this->getStatusBreakdown(),
            'conversion_rate' => $this->getConversionRate(),
            'avg_response_time' => $this->getAverageResponseTime(),
            'inquiries_last_7_days' => count($this->getInquiriesPerDay(7))
        ];
        
        return $metrics;
    }

    /**
     * Get dashboard data (summary)
     */
    public function getDashboardData() {
        return [
            'summary' => $this->getOverallStats(),
            'status_distribution' => $this->getStatusBreakdown(),
            'daily_trend' => $this->getInquiriesPerDay(7),
            'conversion_rate' => $this->getConversionRate(),
            'performance' => $this->getPerformanceMetrics()
        ];
    }

    /**
     * Export analytics as JSON
     */
    public function exportAnalyticsJSON() {
        return json_encode($this->getDashboardData(), JSON_PRETTY_PRINT);
    }
}
