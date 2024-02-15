import { useRegistrationContext } from '../hooks/RegistationContext.jsx';
import Typography from '@mui/material/Typography';
import { useState } from 'react';
import { TextField } from '@mui/material';
import Box from '@mui/material/Box';
import styled from 'styled-components';
import Button from '@mui/material/Button';
import { ArrowRight } from '@mui/icons-material';
import withToast from '../../../components/HOC/WithToastHOC.jsx';
import { checkEmail } from '../../../helpers/checkEmail.js';
import { useUserContext } from '../../../hooks/UserContext';
import {
  checkFieldNotEmpty,
  checkFieldsNotEmpty,
} from '../../../helpers/checkerForm.js';

const CustomTextField = styled(TextField)({
  '& .MuiOutlinedInput-root': {
    '&.Mui-focused fieldset': {
      borderColor: 'black',
      color: 'black',
    },
  },
});

const checkPassword = (password, confirmPassword) => {
  return password === confirmPassword;
};

const CredentialInformationsStep = ({ setToast }) => {
  const { handleNext } = useRegistrationContext();
  const { setUserToRegister } = useUserContext();

  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');

  const confirmHandleNext = () => {
    if (!checkFieldsNotEmpty([email, password, confirmPassword])) {
      console.log('all fields are required');
      setToast({
        open: true,
        severity: 'error',
        message: 'All fields are required',
      });
      return;
    }

    if (!checkPassword(password, confirmPassword)) {
      setToast({
        open: true,
        severity: 'error',
        message: 'Password and confirm password are not the same',
      });
      return;
    }

    if (!checkEmail(email)) {
      setToast({
        open: true,
        severity: 'error',
        message: 'Email is not valid',
      });
      return;
    }

    setUserToRegister({
      email,
      password,
    });

    handleNext();
  };

  return (
    <Box
      sx={{
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        justifyContent: 'center',
      }}
    >
      <CustomTextField
        id="outlined-basic"
        label="Email"
        variant="outlined"
        value={email}
        onChange={(event) => setEmail(event.target.value)}
        sx={{
          marginBottom: '20px',
        }}
        InputProps={{
          focused: {
            borderColor: 'black', // Changez la couleur de la bordure au focus ici
          },
        }}
      />
      <TextField
        id="outlined-basic"
        label="Password"
        variant="outlined"
        value={password}
        onChange={(event) => setPassword(event.target.value)}
        sx={{
          marginBottom: '20px',
        }}
        type={'password'}
      />
      <TextField
        id="outlined-basic"
        label="Confirm Password"
        variant="outlined"
        value={confirmPassword}
        onChange={(event) => setConfirmPassword(event.target.value)}
        type={'password'}
      />
      <Button
        variant="outlined"
        color="black"
        sx={{
          marginTop: '2em',
        }}
        size={'large'}
        onClick={confirmHandleNext}
      >
        NEXT
        <ArrowRight />
      </Button>
    </Box>
  );
};

export default withToast(CredentialInformationsStep);
