<?php
session_start();
require_once '../config.php';

// Verificar se é admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo json_encode(['success' => false, 'error' => 'Não autorizado']);
        exit();
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['media_id'])) {
            // Primeiro, pegar o nome do arquivo
                $stmt = $conn->prepare("SELECT filename FROM media WHERE id = ?");
                    $stmt->bind_param("i", $data['media_id']);
                        $stmt->execute();
                            $result = $stmt->get_result();
                                
                                    if ($file = $result->fetch_assoc()) {
                                            // Excluir o arquivo físico
                                                    $filepath = "../uploads/" . $file['filename'];
                                                            if (file_exists($filepath)) {
                                                                        unlink($filepath);
                                                                                }
                                                                                        
                                                                                                // Excluir registros relacionados
                                                                                                        $conn->query("DELETE FROM reports WHERE media_id = " . $data['media_id']);
                                                                                                                
                                                                                                                        // Excluir a mídia
                                                                                                                                $stmt = $conn->prepare("DELETE FROM media WHERE id = ?");
                                                                                                                                        $stmt->bind_param("i", $data['media_id']);
                                                                                                                                                $success = $stmt->execute();
                                                                                                                                                        
                                                                                                                                                                echo json_encode(['success' => $success]);
                                                                                                                                                                    } else {
                                                                                                                                                                            echo json_encode(['success' => false, 'error' => 'Mídia não encontrada']);
                                                                                                                                                                                }
                                                                                                                                                                                } else {
                                                                                                                                                                                    echo json_encode(['success' => false, 'error' => 'ID da mídia não fornecido']);
                                                                                                                                                                                    }
                                                                                                                                                                                    ?>