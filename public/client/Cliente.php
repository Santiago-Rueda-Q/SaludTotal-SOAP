<?php

use App\Core\SoapClientHandler;
use App\Helpers\RouteHelper;
use App\Helpers\ValidationHelper;

$soapClient = new SoapClientHandler();

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'crear_paciente':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'cedula'           => ValidationHelper::sanitizeString($_POST['cedula'] ?? ''),
                'nombres'          => ValidationHelper::sanitizeString($_POST['nombres'] ?? ''),
                'apellidos'        => ValidationHelper::sanitizeString($_POST['apellidos'] ?? ''),
                'telefono'         => ValidationHelper::sanitizeString($_POST['telefono'] ?? ''),
                'fecha_nacimiento' => ValidationHelper::sanitizeString($_POST['fecha_nacimiento'] ?? ''),
            ];

            $ok = $soapClient->createPatient($data);

            header('Location: ' . RouteHelper::url('listar_pacientes', ['created' => $ok ? 1 : 0]));
            exit;
        }

        $view = view_path('crear_paciente.php');
        require $view;
        break;

    case 'listar_pacientes':
        $pacientes = $soapClient->getAllPatients();
        $view = view_path('listar_pacientes.php');
        require $view;
        break;

    case 'editar_paciente':
        $cedula = $_GET['cedula'] ?? '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'cedula'           => ValidationHelper::sanitizeString($_POST['cedula'] ?? ''),
                'nombres'          => ValidationHelper::sanitizeString($_POST['nombres'] ?? ''),
                'apellidos'        => ValidationHelper::sanitizeString($_POST['apellidos'] ?? ''),
                'telefono'         => ValidationHelper::sanitizeString($_POST['telefono'] ?? ''),
                'fecha_nacimiento' => ValidationHelper::sanitizeString($_POST['fecha_nacimiento'] ?? ''),
            ];

            $ok = $soapClient->updatePatient($data);

            header('Location: ' . RouteHelper::url('listar_pacientes', ['updated' => $ok ? 1 : 0]));
            exit;
        }

        $paciente = $soapClient->getPatientByCedula($cedula);
        if ($paciente === null) {
            $view = view_path('404.php');
        } else {
            $view = view_path('editar_paciente.php');
        }

        require $view;
        break;

    case 'eliminar_paciente':
        $cedula = $_GET['cedula'] ?? '';
        if ($cedula !== '') {
            $ok = $soapClient->deletePatient($cedula);
            header('Location: ' . RouteHelper::url('listar_pacientes', ['deleted' => $ok ? 1 : 0]));
            exit;
        }

        header('Location: ' . RouteHelper::url('listar_pacientes'));
        exit;

    case 'home':
    default:
        $view = view_path('index.php');
        require $view;
        break;
}
