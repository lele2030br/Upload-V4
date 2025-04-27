<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
        $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

                try {
                        // Validar dados
                                if (strlen($username) < 3) {
                                            throw new Exception('O nome de usuário deve ter pelo menos 3 caracteres');
                                                    }

                                                            if (strlen($password) < 6) {
                                                                        throw new Exception('A senha deve ter pelo menos 6 caracteres');
                                                                                }

                                                                                        if ($password !== $confirm_password) {
                                                                                                    throw new Exception('As senhas não coincidem');
                                                                                                            }

                                                                                                                    // Verificar se usuário já existe
                                                                                                                            $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
                                                                                                                                    $stmt->bind_param("s", $username);
                                                                                                                                            $stmt->execute();
                                                                                                                                                    
                                                                                                                                                            if ($stmt->get_result()->num_rows > 0) {
                                                                                                                                                                        throw new Exception('Este nome de usuário já está em uso');
                                                                                                                                                                                }

                                                                                                                                                                                        // Criar o usuário
                                                                                                                                                                                                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                                                                                                                                                                                                        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                                                                                                                                                                                                                $stmt->bind_param("ss", $username, $hashed_password);
                                                                                                                                                                                                                        
                                                                                                                                                                                                                                if ($stmt->execute()) {
                                                                                                                                                                                                                                            $_SESSION['success'] = 'Conta criada com sucesso! Faça login para continuar.';
                                                                                                                                                                                                                                                        header('Location: login.php');
                                                                                                                                                                                                                                                                    exit();
                                                                                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                                                                                        throw new Exception('Erro ao criar conta');
                                                                                                                                                                                                                                                                                                }

                                                                                                                                                                                                                                                                                                    } catch (Exception $e) {
                                                                                                                                                                                                                                                                                                            $_SESSION['error'] = $e->getMessage();
                                                                                                                                                                                                                                                                                                                    header('Location: register.php');
                                                                                                                                                                                                                                                                                                                            exit();
                                                                                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                                                                                ?>