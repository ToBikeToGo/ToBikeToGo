import React, { useState } from 'react';
import Snackbar from '@mui/material/Snackbar';
import Alert from '@mui/material/Alert';

const withToast = (Component) => {
  return (props) => {
    const [toast, setToast] = useState({
      open: false,
      message: '',
      severity: 'success',
    });

    const handleClose = (event, reason) => {
      if (reason === 'clickaway') {
        return;
      }
      setToast({ ...toast, open: false });
    };

    return (
      <>
        <Component {...props} setToast={setToast} />
        <Snackbar
          open={toast.open}
          autoHideDuration={6000}
          onClose={handleClose}
        >
          <Alert
            onClose={handleClose}
            severity={toast.severity}
            sx={{ width: '100%' }}
          >
            {toast.message}
          </Alert>
        </Snackbar>
      </>
    );
  };
};

export default withToast;
