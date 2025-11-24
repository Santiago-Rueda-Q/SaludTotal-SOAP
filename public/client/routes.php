<?php

use App\Core\SoapClientHandler;
use App\Helpers\RouteHelper;
use App\Helpers\ValidationHelper;

/**
 * Mapa de rutas del cliente web.
 * Cada key es el "action" y el value es un closure que recibe el SoapClientHandler.
 */
return [

    'home' => function (SoapClientHandler $soapClient) {
        $view = view_path('index.php');
        require $view;
    },

    'listar_pacientes' => function (SoapClientHandler $soapClient) {
        $pacientes = $soapClient->getAllPatients();
        $view = view_path('listar_pacientes.php');
        require $view;
    },

    'crear_paciente' => function (SoapClientHandler $soapClient) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'cedula'           => ValidationHelper::sanitizeString($_POST['cedula'] ?? ''),
                'nombres'          => ValidationHelper::sanitizeString($_POST['nombres'] ?? ''),
                'apellidos'        => ValidationHelper::sanitizeString($_POST['apellidos'] ?? ''),
                'telefono'         => ValidationHelper::sanitizeString($_POST['telefono'] ?? ''),
                'fecha_nacimiento' => ValidationHelper::sanitizeString($_POST['fecha_nacimiento'] ?? ''),
            ];

            $ok = $soapClient->createPatient($data);

            header('Location: ' . RouteHelper::url('listar_pacientes', [
                'created' => $ok ? 1 : 0,
            ]));
            exit;
        }

        $view = view_path('crear_paciente.php');
        require $view;
    },

    'editar_paciente' => function (SoapClientHandler $soapClient) {
        // Puedes cambiar "cedula" por "id" si quieres que no se vea tan explícito
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

            header('Location: ' . RouteHelper::url('listar_pacientes', [
                'updated' => $ok ? 1 : 0,
            ]));
            exit;
        }

        $paciente = $soapClient->getPatientByCedula($cedula);

        if ($paciente === null) {
            $view = view_path('404.php');
        } else {
            $view = view_path('editar_paciente.php');
        }

        require $view;
    },

    'eliminar_paciente' => function (SoapClientHandler $soapClient) {
        $cedula = $_GET['cedula'] ?? '';

        if ($cedula !== '') {
            $ok = $soapClient->deletePatient($cedula);

            header('Location: ' . RouteHelper::url('listar_pacientes', [
                'deleted' => $ok ? 1 : 0,
            ]));
            exit;
        }

        header('Location: ' . RouteHelper::url('listar_pacientes'));
        exit;
    },

];
