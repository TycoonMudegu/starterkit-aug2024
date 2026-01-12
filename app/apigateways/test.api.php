<?php
namespace App\apigateways;

use App\Controllers\MigrationsController; 
use App\Controllers\EmailTestController;


header("Content-Type: application/json");


// Initialize response
$response = ['status' => 'error', 'message' => 'Invalid request method'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read raw input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // If JSON parsing failed and form data is present
    if ($data === null && !empty($_POST)) {
        $data = $_POST;
    }

    // Check if we got valid data
    if ($data === null || empty($data)) {
        $response = ['status' => 'error', 'message' => 'No input data provided'];
    } else {
        // Extract action and other data
        $action = $data['action'] ?? null;
        $email = $data['email'] ?? null;

        $Migrations = new MigrationsController();
        $Email = new EmailTestController();

        // Process actions
        switch ($action) {
            case 'runMigration':
                $response = $Migrations->TestMigration();
                break;

            case 'submitEmail':
                if (empty($email)) {
                    $response = ['status' => 'error', 'message' => 'Email address is required'];
                } else {
                    $response = $Email->sendEmail($email);
                }
                break;

            default:
                $response = ['status' => 'error', 'message' => 'Invalid action'];
                break;
        }
    }
}

// Ensure JSON response
echo json_encode($response);
exit;
