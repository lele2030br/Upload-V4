<?php
function formatarDataHora($data) {
    $datetime = new DateTime($data);
        $agora = new DateTime();
            $diferenca = $agora->diff($datetime);
                
                    if ($diferenca->days == 0) {
                            if ($diferenca->h == 0) {
                                        if ($diferenca->i == 0) {
                                                        return "Agora mesmo";
                                                                    }
                                                                                return "Há " . $diferenca->i . " minuto" . ($diferenca->i > 1 ? "s" : "");
                                                                                        }
                                                                                                return "Há " . $diferenca->h . " hora" . ($diferenca->h > 1 ? "s" : "");
                                                                                                    } elseif ($diferenca->days < 7) {
                                                                                                            return "Há " . $diferenca->days . " dia" . ($diferenca->days > 1 ? "s" : "");
                                                                                                                } else {
                                                                                                                        return $datetime->format('d/m/Y H:i');
                                                                                                                            }
                                                                                                                            }

                                                                                                                            function formatarTamanhoArquivo($bytes) {
                                                                                                                                $units = ['B', 'KB', 'MB', 'GB'];
                                                                                                                                    $bytes = max($bytes, 0);
                                                                                                                                        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
                                                                                                                                            $pow = min($pow, count($units) - 1);
                                                                                                                                                $bytes /= pow(1024, $pow);
                                                                                                                                                    
                                                                                                                                                        return round($bytes, 2) . ' ' . $units[$pow];
                                                                                                                                                        }
                                                                                                                                                        ?>