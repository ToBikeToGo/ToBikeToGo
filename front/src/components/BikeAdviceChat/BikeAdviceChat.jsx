import React, { useState } from 'react';
import { Box, Button, TextField, Typography, CircularProgress } from '@mui/material';

const BikeAdviceChat = () => {
    const [message, setMessage] = useState('');
    const [advice, setAdvice] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');

    const sendMessage = async () => {
        if (!message.trim()) return; // Empêche l'envoi de messages vides
        setLoading(true);
        setError(''); // Réinitialise l'erreur à chaque envoi
        const env = import.meta.env;
        try {
            const res = await fetch('https://api.openai.com/v1/chat/completions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${env.VITE_APP_OPENAI_API_KEY}`, // Utiliser REACT_APP_ ici
                },
                body: JSON.stringify({
                    model: 'gpt-3.5-turbo', // ou 'gpt-4' si disponible
                    messages: [
                        { role: 'system', content: 'You are a bike trip advisor, Format your responses with proper spacing and line breaks between each list item or paragraph.' },
                        { role: 'user', content: message },
                    ],
                }),
            });

            if (!res.ok) {
                throw new Error('Erreur lors de la requête');
            }

            const data = await res.json();
            setAdvice(data.choices[0].message.content); // Affiche la réponse
            setMessage(''); // Réinitialise le champ de saisie après l'envoi
        } catch (err) {
            console.error('Erreur lors de l’envoi du message:', err);
            setError("Erreur lors de l'envoi. Veuillez réessayer.");
        } finally {
            setLoading(false);
        }
    };

    return (
        <Box
            sx={{
                display: 'flex',
                flexDirection: 'column',
                alignItems: 'center',
                p: 4,
                backgroundColor: 'background.paper',
                borderRadius: 2,
                boxShadow: 2,
                minWidth: '400px',
            }}
        >
            <Typography variant="h4" gutterBottom>
                Bike Trip Advisor
            </Typography>
            <TextField
                label="Posez une question sur votre randonnée"
                multiline
                rows={4}
                variant="outlined"
                fullWidth
                value={message}
                onChange={(e) => setMessage(e.target.value)}
                sx={{ marginBottom: 2 }}
            />
            <Button
                variant="contained"
                color="primary"
                onClick={sendMessage}
                disabled={loading}
                fullWidth
            >
                {loading ? <CircularProgress size={24} /> : 'Envoyer'}
            </Button>

            {error && (
                <Typography color="error" sx={{ marginTop: 2 }}>
                    {error}
                </Typography>
            )}

            {advice && (
                <Box
                    sx={{
                        marginTop: 2,
                        padding: 2,
                        backgroundColor: '#f1f1f1',
                        borderRadius: 1,
                        maxHeight: '200px', // Limite la hauteur
                        overflowY: 'auto', // Ajoute un défilement vertical
                    }}
                >
                    <Typography variant="h6">Conseil :</Typography>
                    <Typography>{advice}</Typography>
                </Box>
            )}
        </Box>
    );
};

export default BikeAdviceChat;
