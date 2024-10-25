import React, { useState } from 'react';
import theme from '../../theme/theme.js';
import { Typography, Modal, Box, Button } from '@mui/material';
import BikeAdviceChat from '../BikeAdviceChat/BikeAdviceChat';
import {useUserContext} from "../../hooks/UserContext.jsx";

const Footer = () => {
    const [open, setOpen] = useState(false);

    const handleOpen = () => setOpen(true);
    const handleClose = () => setOpen(false);
    const { isLogged } =
        useUserContext();

    let content;

    if (isLogged) {
        content = (
            <>
                <Button
                    onClick={handleOpen}
                    variant="contained"
                    style={{
                        backgroundColor: theme.palette.common.white,
                        color: theme.palette.secondary.main,
                    }}
                >
                    Conseils de Randonnée
                </Button>

                {/* Modale contenant le composant BikeAdviceChat */}
                <Modal open={open} onClose={handleClose}>
                    <Box
                        sx={{
                            position: 'absolute',
                            top: '50%',
                            left: '50%',
                            transform: 'translate(-50%, -50%)',
                            maxWidth: '600px',  // Ajuste la largeur de la modale
                            width: '100%',      // Permet de s'adapter aux petits écrans
                            bgcolor: 'background.paper',
                            borderRadius: 2,
                            boxShadow: 24,
                            p: 4,
                        }}
                    >
                        <BikeAdviceChat />
                    </Box>
                </Modal>
            </>
        );
    }

    return (
        <div
            style={{
                backgroundColor: theme.palette.secondary.main,
            }}
            className="flex justify-between items-center w-full h-20 p-4"
        >
            <Typography
                variant="h2"
                component="h4"
                gutterBottom
                style={{ color: theme.palette.common.white }}
            >
                BikeToGo
            </Typography>
            {content}
        </div>
    );
};

export { Footer };
