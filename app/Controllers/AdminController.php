<?php

class AdminController extends BaseController
{
    private Tool $toolModel;
    private User $userModel;
    private Rental $rentalModel;

    public function __construct()
    {
        $this->toolModel = new Tool();
        $this->userModel = new User();
        $this->rentalModel = new Rental();
    }

    public function dashboard(): void
    {
        $this->rentalModel->markOverdue();

        $clientCount = $this->userModel->clientCount();
        $toolCount = count($this->toolModel->all());
        $activeRentals = $this->rentalModel->getActiveRentalsCount();
        $monthlyRevenue = $this->rentalModel->getMonthlyRevenue();
        $pendingRentals = $this->rentalModel->getPendingCount();
        $overdueRentals = $this->rentalModel->getOverdueCount();
        $monthlyData = $this->rentalModel->getMonthlyData();
        $topTools = $this->rentalModel->getTopTools(5);

        $this->view('admin/dashboard', [
            'title' => 'Dashboard - Administrador',
            'clientCount' => $clientCount,
            'toolCount' => $toolCount,
            'activeRentals' => $activeRentals,
            'monthlyRevenue' => $monthlyRevenue,
            'pendingRentals' => $pendingRentals,
            'overdueRentals' => $overdueRentals,
            'monthlyData' => $monthlyData,
            'topTools' => $topTools,
        ]);
    }
}
