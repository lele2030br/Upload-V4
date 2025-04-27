<?php
session_start();
require_once '../config.php';

// Verificar se é admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo json_encode(['success' => false, 'error' => 'Não autorizado']);
        exit();
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['report_id'])) {
            $stmt = $conn->prepare("UPDATE reports SET status = 'resolvido' WHERE id = ?");
                $stmt->bind_param("i", $data['report_id']);
                    
                        $success = $stmt->execute();
                            echo json_encode(['success' => $success]);
                            } else {
                                echo json_encode(['success' => false, 'error' => 'ID da denúncia não fornecido']);
                                }
                                ?>