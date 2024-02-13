import IconButton from '@mui/material/IconButton';
import { MoreVert } from '@mui/icons-material';
import Menu from '@mui/material/Menu';
import MenuItem from '@mui/material/MenuItem';
import { useUserContext } from '../../hooks/UserContext.jsx';
import { useState } from 'react';
import {
  Dialog,
  DialogActions,
  DialogContent,
  DialogContentText,
  DialogTitle,
} from '@mui/material';
import Button from '@mui/material/Button';

export const AdminCrudMolette = ({ entityName, handleEdit, handleRemove }) => {
  const [anchorEl, setAnchorEl] = useState(null);
  const open = Boolean(anchorEl);
  const [openDialog, setOpenDialog] = useState(false);
  const { isAdmin } = useUserContext();

  const handleOpenDialog = () => {
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setOpenDialog(false);
  };

  const handleConfirmRemove = () => {
    handleRemove();
    setOpenDialog(false);
  };
  const handleClick = (event) => {
    setAnchorEl(event.currentTarget);
  };

  const handleClose = () => {
    setAnchorEl(null);
  };

  return (
    <>
      {!isAdmin && (
        <div className="absolute top-0 right-0 p-2">
          <IconButton
            aria-label="more"
            aria-controls="long-menu"
            aria-haspopup="true"
            onClick={handleClick}
          >
            <MoreVert />
          </IconButton>
          <Menu
            id="long-menu"
            anchorEl={anchorEl}
            open={open}
            onClose={handleClose}
            MenuListProps={{
              'aria-labelledby': 'basic-button',
            }}
          >
            <MenuItem onClick={handleEdit}>Edit</MenuItem>
            <MenuItem onClick={handleOpenDialog}>Remove</MenuItem>
            <Dialog
              open={openDialog}
              onClose={handleCloseDialog}
              aria-labelledby="alert-dialog-title"
              aria-describedby="alert-dialog-description"
            >
              <DialogTitle id="alert-dialog-title">
                {'Confirm Removal'}
              </DialogTitle>
              <DialogContent>
                <DialogContentText id="alert-dialog-description">
                  Are you sure you want to remove this {entityName}?
                </DialogContentText>
              </DialogContent>
              <DialogActions>
                <Button onClick={handleCloseDialog} color="black">
                  Cancel
                </Button>
                <Button
                  onClick={handleConfirmRemove}
                  variant="contained"
                  color="red"
                  autoFocus
                >
                  Confirm
                </Button>
              </DialogActions>
            </Dialog>
          </Menu>
        </div>
      )}
    </>
  );
};
