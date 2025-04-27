<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Não autorizado']);
        exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
                                throw new Exception('Erro no upload do arquivo');
                                        }

                                                $file = $_FILES['file'];
                                                        $title = $_POST['title'] ?? '';
                                                                $description = $_POST['description'] ?? ''; // Pegando a descrição
                                                                        $user_id = $_SESSION['user_id'];

                                                                                // Verificar tamanho do arquivo (20MB)
                                                                                        if ($file['size'] > 20000000) {
                                                                                                    throw new Exception('Arquivo muito grande');
                                                                                                            }

                                                                                                                    // Verificar tipo do arquivo
                                                                                                                            $allowed_types = [
                                                                                                                                        'image/jpeg', 'image/png', 'image/gif',
                                                                                                                                                    'video/mp4', 'video/quicktime', 'video/x-msvideo'
                                                                                                                                                            ];

                                                                                                                                                                    if (!in_array($file['type'], $allowed_types)) {
                                                                                                                                                                                throw new Exception('Tipo de arquivo não permitido');
                                                                                                                                                                                        }

                                                                                                                                                                                                // Gerar nome único para o arquivo
                                                                                                                                                                                                        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                                                                                                                                                                                                                $new_filename = uniqid() . '.' . $extension;
                                                                                                                                                                                                                        $upload_path = 'uploads/' . $new_filename;

                                                                                                                                                                                                                                // Mover arquivo
                                                                                                                                                                                                                                        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
                                                                                                                                                                                                                                                    throw new Exception('Erro ao mover arquivo');
                                                                                                                                                                                                                                                            }

                                                                                                                                                                                                                                                                    // Determinar tipo de mídia
                                                                                                                                                                                                                                                                            $media_type = strpos($file['type'], 'image') !== false ? 'image' : 'video';

                                                                                                                                                                                                                                                                                    // Preparar e executar a query com a descrição
                                                                                                                                                                                                                                                                                            $stmt = $conn->prepare("INSERT INTO media (title, description, filename, type, user_id) VALUES (?, ?, ?, ?, ?)");
                                                                                                                                                                                                                                                                                                    $stmt->bind_param("ssssi", $title, $description, $new_filename, $media_type, $user_id);

                                                                                                                                                                                                                                                                                                            if (!$stmt->execute()) {
                                                                                                                                                                                                                                                                                                                        unlink($upload_path);
                                                                                                                                                                                                                                                                                                                                    throw new Exception('Erro ao salvar no banco de dados');
                                                                                                                                                                                                                                                                                                                                            }

                                                                                                                                                                                                                                                                                                                                                    echo json_encode(['success' => true]);

                                                                                                                                                                                                                                                                                                                                                        } catch (Exception $e) {
                                                                                                                                                                                                                                                                                                                                                                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                                                                                                                                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                                                                                                                                    } else {
                                                                                                                                                                                                                                                                                                                                                                        echo json_encode(['success' => false, 'error' => 'Método não permitido']);
                                                                                                                                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                                                                                                                                        ?>