<?php
session_start();
require_once '../config.php';

// Verificar se é admin
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo json_encode(['success' => false, 'error' => 'Não autorizado']);
        exit();
        }

        $data = json_decode(file_get_contents('php://input'), true);

        switch ($data['action']) {
            case 'delete_media':
                    deleteMedia($conn, $data['media_id']);
                            break;
                                    
                                        case 'update_media':
                                                updateMedia($conn, $data['media_id'], $data['title']);
                                                        break;
                                                                
                                                                    case 'resolve_report':
                                                                            resolveReport($conn, $data['report_id']);
                                                                                    break;
                                                                                            
                                                                                                default:
                                                                                                        echo json_encode(['success' => false, 'error' => 'Ação inválida']);
                                                                                                        }

                                                                                                        function deleteMedia($conn, $media_id) {
                                                                                                            // Primeiro, pegar o nome do arquivo
                                                                                                                $stmt = $conn->prepare("SELECT filename FROM media WHERE id = ?");
                                                                                                                    $stmt->bind_param("i", $media_id);
                                                                                                                        $stmt->execute();
                                                                                                                            $result = $stmt->get_result();
                                                                                                                                
                                                                                                                                    if ($file = $result->fetch_assoc()) {
                                                                                                                                            // Excluir o arquivo físico
                                                                                                                                                    $filepath = "../uploads/" . $file['filename'];
                                                                                                                                                            if (file_exists($filepath)) {
                                                                                                                                                                        unlink($filepath);
                                                                                                                                                                                }
                                                                                                                                                                                        
                                                                                                                                                                                                // Excluir registros relacionados
                                                                                                                                                                                                        $conn->query("DELETE FROM reports WHERE media_id = " . $media_id);
                                                                                                                                                                                                                
                                                                                                                                                                                                                        // Excluir a mídia
                                                                                                                                                                                                                                $stmt = $conn->prepare("DELETE FROM media WHERE id = ?");
                                                                                                                                                                                                                                        $stmt->bind_param("i", $media_id);
                                                                                                                                                                                                                                                $success = $stmt->execute();
                                                                                                                                                                                                                                                        
                                                                                                                                                                                                                                                                echo json_encode(['success' => $success]);
                                                                                                                                                                                                                                                                    } else {
                                                                                                                                                                                                                                                                            echo json_encode(['success' => false, 'error' => 'Mídia não encontrada']);
                                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                                }

                                                                                                                                                                                                                                                                                function updateMedia($conn, $media_id, $title) {
                                                                                                                                                                                                                                                                                    $stmt = $conn->prepare("UPDATE media SET title = ? WHERE id = ?");
                                                                                                                                                                                                                                                                                        $stmt->bind_param("si", $title, $media_id);
                                                                                                                                                                                                                                                                                            $success = $stmt->execute();
                                                                                                                                                                                                                                                                                                
                                                                                                                                                                                                                                                                                                    echo json_encode(['success' => $success]);
                                                                                                                                                                                                                                                                                                    }

                                                                                                                                                                                                                                                                                                    function resolveReport($conn, $report_id) {
                                                                                                                                                                                                                                                                                                        $stmt = $conn->prepare("UPDATE reports SET status = 'resolvido' WHERE id = ?");
                                                                                                                                                                                                                                                                                                            $stmt->bind_param("i", $report_id);
                                                                                                                                                                                                                                                                                                                $success = $stmt->execute();
                                                                                                                                                                                                                                                                                                                    
                                                                                                                                                                                                                                                                                                                        echo json_encode(['success' => $success]);
                                                                                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                                                                                        ?>