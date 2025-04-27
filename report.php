<?php
session_start();
require_once 'config.php';

// Verificar se a requisição é POST e o usuário está logado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    // Obter dados JSON
        $data = json_decode(file_get_contents('php://input'), true);
            
                if (isset($data['media_id']) && isset($data['reason'])) {
                        $stmt = $conn->prepare("INSERT INTO reports (media_id, user_id, reason) VALUES (?, ?, ?)");
                                $stmt->bind_param("iis", $data['media_id'], $_SESSION['user_id'], $data['reason']);
                                        
                                                if ($stmt->execute()) {
                                                            echo json_encode(['success' => true]);
                                                                    } else {
                                                                                echo json_encode(['success' => false, 'error' => 'Erro no banco de dados']);
                                                                                        }
                                                                                            } else {
                                                                                                    echo json_encode(['success' => false, 'error' => 'Dados inválidos']);
                                                                                                        }
                                                                                                        } else {
                                                                                                            echo json_encode(['success' => false, 'error' => 'Requisição inválida ou usuário não logado']);
                                                                                                            }
                                                                                                            ?>