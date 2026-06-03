<?php

class SettingsController extends BaseController
{
    public function index(): void
    {
        $groups = ['company', 'rental', 'system'];
        $settings = [];

        foreach ($groups as $group) {
            $stmt = \BaseModel::db()->prepare(
                "SELECT * FROM systemSettings WHERE settingGroup = :group ORDER BY settingId ASC"
            );
            $stmt->execute([':group' => $group]);
            $settings[$group] = $stmt->fetchAll();
        }

        $this->view('admin/configuracoes/index', [
            'title' => 'Configurações - Administrador',
            'settings' => $settings,
        ]);
    }

    public function update(): void
    {
        $keys = $_POST['settings'] ?? [];

        foreach ($keys as $key => $value) {
            $stmt = \BaseModel::db()->prepare(
                "UPDATE systemSettings SET settingValue = :value, updatedAt = NOW() WHERE settingKey = :key"
            );
            $stmt->execute([':key' => $key, ':value' => $value]);
        }

        $_SESSION['_flash']['success'] = 'Configurações atualizadas com sucesso!';
        $this->redirect('/admin/configuracoes');
    }
}
