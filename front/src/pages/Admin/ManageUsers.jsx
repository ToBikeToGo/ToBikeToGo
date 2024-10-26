import { UsersTable } from './components/UsersTable.jsx';
import React, { useEffect, useState } from 'react';
import { useUsers } from './hooks/useUsers.js';
import { CircularProgress, Box, TextField, Modal, Typography, Button } from '@mui/material';
import { useNavigate } from 'react-router-dom';
import { useUserContext } from '../../hooks/UserContext.jsx';
import {getApirUrl} from "../../helpers/getApirUrl.js";
import fetchApi from '../../helpers/fetchApi.js';

export const ManageUsers = () => {
    const {
        users,
        getUsers,
        page,
        onChangePage,
        totalPage,
        isLoading,
        search,
        setSearch,
    } = useUsers();

    const { user: currentUser } = useUserContext();
    const navigate = useNavigate();

    const [tempSearch, setTempSearch] = useState(search);
    const [open, setOpen] = useState(false);
    const [userToDelete, setUserToDelete] = useState(null);
    const apiUrl = getApirUrl();

    useEffect(() => {
        getUsers();
    }, [getUsers]);

    const handleSearchChange = (event) => {
        setTempSearch(event.target.value);
    };

    const handleSubmitSearch = async () => {
        await setSearch(tempSearch);
        getUsers();
    };

    const onMemberEdit = (id) => {
        navigate(`/user/edit-profile/${id}`);
    };

    const handleDeleteClick = (id) => {
        if (currentUser?.roles.includes('ROLE_ADMIN')) {
            setUserToDelete(id);
            setOpen(true);
        } else {
            alert('Vous n’avez pas les droits nécessaires pour supprimer un utilisateur.');
        }
    };

    const confirmDelete = async () => {
        try {
            const response = await fetchApi(`${apiUrl}/users/${userToDelete}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
            });

            if (response.ok) {
                getUsers(); // Refresh the users list
            } else {
            }
        } catch (error) {
            console.error("Erreur lors de l'appel de suppression :", error);
        } finally {
            setOpen(false);
            setUserToDelete(null);
        }
    };

    if (isLoading) return <CircularProgress sx={{ m: 5 }} />;

    return (
        <Box
            style={{
                display: 'flex',
                flexDirection: 'column',
                alignItems: 'center',
                justifyContent: 'center',
                width: '100%',
                padding: '2em',
            }}
        >
            <TextField
                label="Rechercher un utilisateur"
                variant="outlined"
                value={tempSearch}
                style={{
                    backgroundColor: 'white',
                    borderRadius: '5px',
                    margin: '20px',
                }}
                fullWidth
                onChange={handleSearchChange}
                onKeyPress={(event) => {
                    if (event.key === 'Enter') {
                        handleSubmitSearch();
                    }
                }}
            />
            {isLoading ? (
                <CircularProgress color={'secondary'} className={'m-5'} />
            ) : (
                <UsersTable
                    page={page}
                    totalPage={totalPage}
                    onChangePage={onChangePage}
                    users={users}
                    onMemberDelete={handleDeleteClick}
                    onMemberEdit={onMemberEdit}
                />
            )}

            {/* Confirmation Modal */}
            <Modal open={open} onClose={() => setOpen(false)}>
                <Box
                    sx={{
                        position: 'absolute',
                        top: '50%',
                        left: '50%',
                        transform: 'translate(-50%, -50%)',
                        width: 300,
                        bgcolor: 'background.paper',
                        borderRadius: 2,
                        boxShadow: 24,
                        p: 4,
                        textAlign: 'center'
                    }}
                >
                    <Typography variant="h6" gutterBottom>
                        Confirmer la suppression
                    </Typography>
                    <Typography variant="body1" sx={{ mb: 3 }}>
                        Voulez-vous vraiment supprimer cet utilisateur ?
                    </Typography>
                    <Box sx={{ display: 'flex', justifyContent: 'flex-end', mt: 2 }}>
                        <Button
                            variant="contained"
                            color="error"
                            onClick={confirmDelete}
                            sx={{ mr: 2 }}
                        >
                            Supprimer
                        </Button>
                        <Button variant="outlined" onClick={() => setOpen(false)}>
                            Annuler
                        </Button>
                    </Box>
                </Box>
            </Modal>
        </Box>
    );
};
