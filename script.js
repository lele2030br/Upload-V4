// Funcionalidade de compartilhamento
function share(mediaId) {
    // Obter a URL atual
        const url = window.location.origin + window.location.pathname + '?media=' + mediaId;
            
                // Verificar se a Web Share API está disponível
                    if (navigator.share) {
                            navigator.share({
                                        title: 'Confira esta mídia',
                                                    url: url
                                                            }).catch(console.error);
                                                                } else {
                                                                        // Alternativa: copiar para área de transferência
                                                                                navigator.clipboard.writeText(url).then(() => {
                                                                                            alert('Link copiado para a área de transferência!');
                                                                                                    }).catch(console.error);
                                                                                                        }
                                                                                                        }

                                                                                                        // Funcionalidade de denúncia
                                                                                                        function report(mediaId) {
                                                                                                            const reason = prompt('Por favor, digite o motivo da denúncia:');
                                                                                                                if (reason) {
                                                                                                                        fetch('report.php', {
                                                                                                                                    method: 'POST',
                                                                                                                                                headers: {
                                                                                                                                                                'Content-Type': 'application/json',
                                                                                                                                                                            },
                                                                                                                                                                                        body: JSON.stringify({
                                                                                                                                                                                                        media_id: mediaId,
                                                                                                                                                                                                                        reason: reason
                                                                                                                                                                                                                                    })
                                                                                                                                                                                                                                            })
                                                                                                                                                                                                                                                    .then(response => response.json())
                                                                                                                                                                                                                                                            .then(data => {
                                                                                                                                                                                                                                                                        if (data.success) {
                                                                                                                                                                                                                                                                                        alert('Obrigado pela sua denúncia. Iremos analisar em breve.');
                                                                                                                                                                                                                                                                                                    } else {
                                                                                                                                                                                                                                                                                                                    alert('Houve um erro ao enviar sua denúncia. Por favor, tente novamente.');
                                                                                                                                                                                                                                                                                                                                }
                                                                                                                                                                                                                                                                                                                                        })
                                                                                                                                                                                                                                                                                                                                                .catch(error => {
                                                                                                                                                                                                                                                                                                                                                            console.error('Erro:', error);
                                                                                                                                                                                                                                                                                                                                                                        alert('Houve um erro ao enviar sua denúncia. Por favor, tente novamente.');
                                                                                                                                                                                                                                                                                                                                                                                });
                                                                                                                                                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                                                                                                                                                    }