<?php

class ReportController extends BaseController
{
    private Rental $rentalModel;
    private Tool $toolModel;

    public function __construct()
    {
        $this->rentalModel = new Rental();
        $this->toolModel = new Tool();
    }

    public function index(): void
    {
        $period = $_GET['period'] ?? 'month';
        $startDate = $_GET['startDate'] ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $_GET['endDate'] ?? date('Y-m-d');

        switch ($period) {
            case 'week':
                $startDate = date('Y-m-d', strtotime('-7 days'));
                break;
            case 'month':
                $startDate = date('Y-m-d', strtotime('-30 days'));
                break;
            case 'quarter':
                $startDate = date('Y-m-d', strtotime('-3 months'));
                break;
            case 'year':
                $startDate = date('Y-m-d', strtotime('-12 months'));
                break;
        }

        $stmt = \BaseModel::db()->prepare(
            "SELECT r.*, u.name as userName, t.toolName, c.categoryName
             FROM rentals r
             JOIN users u ON r.userId = u.userId
             JOIN tools t ON r.toolId = t.toolId
             JOIN toolCategories c ON t.categoryId = c.categoryId
             WHERE r.createdAt >= :startDate::date AND r.createdAt <= :endDate::date + INTERVAL '1 day'
             ORDER BY r.createdAt DESC"
        );
        $stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
        $rentals = $stmt->fetchAll();

        $totalRevenue = 0;
        $totalFines = 0;
        $totalRentals = count($rentals);
        $statusCount = ['pending' => 0, 'active' => 0, 'returned' => 0, 'overdue' => 0, 'cancelled' => 0];

        foreach ($rentals as $r) {
            $totalRevenue += (float) $r['totalamount'];
            $totalFines += (float) $r['fineamount'];
            if (isset($statusCount[$r['status']])) {
                $statusCount[$r['status']]++;
            }
        }

        $stmt = \BaseModel::db()->prepare(
            "SELECT t.toolName, COUNT(r.rentalId) as totalLocs, COALESCE(SUM(r.totalAmount),0) as receita
             FROM rentals r
             JOIN tools t ON r.toolId = t.toolId
             WHERE r.createdAt >= :startDate::date AND r.createdAt <= :endDate::date + INTERVAL '1 day'
             AND r.status IN ('returned', 'active', 'overdue')
             GROUP BY t.toolName
             ORDER BY totalLocs DESC
             LIMIT 10"
        );
        $stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
        $topTools = $stmt->fetchAll();

        $this->view('admin/relatorios/index', [
            'title' => 'Relatórios - Administrador',
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'rentals' => $rentals,
            'totalRevenue' => $totalRevenue,
            'totalFines' => $totalFines,
            'totalRentals' => $totalRentals,
            'statusCount' => $statusCount,
            'topTools' => $topTools,
        ]);
    }
}
