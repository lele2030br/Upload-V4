<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Não autorizado']);
        exit();
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            echo json_encode(['success' => false, 'error' => 'Dados inválidos']);
                exit();
                }

                switch ($data['action']) {
                    case 'update_title':
                            $stmt = $conn->prepare("UPDATE media SET title = ? WHERE id = ? AND user_id = ?");
                                    $stmt->bind_param("sii", $data['title'], $data['id'], $_SESSION['user_id']);
                                            $success = $stmt->execute();
                                                    echo json_encode(['success' => $success]);
                                                            break;

                                                                case 'delete':
                                                                        // Primeiro, pegue o nome do arquivo
                                                                                $stmt = $conn->prepare("SELECT filename FROM media WHERE id = ? AND user_id = ?");
                                                                                        $stmt->bind_param("ii", $data['id'], $_SESSION['user_id']);
                                                                                                $stmt->execute();
                                                                                                        $result = $stmt->get_result();
                                                                                                                
                                                                                                                        if ($file = $result->fetch_assoc()) {
                                                                                                                                    // Exclua o arquivo físico
                                                                                                                                                $filepath = "uploads/" . $file['filename'];
                                                                                                                                                            if (file_exists($filepath)) {
                                                                                                                                                                            unlink($filepath);
                                                                                                                                                                                        }
                                                                                                                                                                                                    
                                                                                                                                                                                                                // Exclua o registro do banco de dados
                                                                                                                                                                                                                            $stmt = $conn->prepare("DELETE FROM media WHERE id = ? AND user_id = ?");
                                                                                                                                                                                                                                        $stmt->bind_param("ii", $data['id'], $_SESSION['user_id']);
                                                                                                                                                                                                                                                    $success = $stmt->execute();
                                                                                                                                                                                                                                                                echo json_encode(['success' => $success]);
                                                                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                                                                                    echo json_encode(['success' => false, 'error' => 'Arquivo não encontrado']);
                                                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                                                                    break;

                                                                                                                                                                                                                                                                                                        default:
                                                                                                                                                                                                                                                                                                                echo json_encode(['success' => false, 'error' => 'Ação inválida']);
                                                                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                                                                ?>