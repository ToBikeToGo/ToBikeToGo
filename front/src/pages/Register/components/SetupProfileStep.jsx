import { useRegistrationContext } from '../hooks/RegistationContext.jsx';
import { useState } from 'react';
import Box from '@mui/material/Box';
import { InputAdornment, TextField } from '@mui/material';
import Button from '@mui/material/Button';
import { ArrowRight, Person, Person2, Phone } from '@mui/icons-material';
import withToast from '../../../components/HOC/WithToastHOC.jsx';
import { useUserContext } from '../../../hooks/UserContext.jsx';
import { checkInputText, checkPhone } from '../../../helpers/checkerForm.js';
const SetupProfileStep = ({ setToast }) => {
  const { handleNext } = useRegistrationContext();
  const { setUserToRegister } = useUserContext();

  const [firstname, setFirstname] = useState('');
  const [lastname, setLastname] = useState('');
  const [phone, setPhone] = useState('');

  const confirmHandleNext = () => {
    if (!checkPhone(phone)) {
      setToast({
        open: true,
        severity: 'error',
        message: 'Phone is not valid',
      });
      return;
    }

    if (!checkInputText(firstname)) {
      setToast({
        open: true,
        severity: 'error',
        message: 'Firstname is not valid',
      });
      return;
    }

    if (!checkInputText(lastname)) {
      setToast({
        open: true,
        severity: 'error',
        message: 'Lastname is not valid',
      });
      return;
    }

    setUserToRegister((prevState) => ({
      ...prevState,
      firstname,
      phone,
    }));

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
      <TextField
        id="outlined-basic"
        label="fistname"
        variant="outlined"
        value={firstname}
        onChange={(event) => setFirstname(event.target.value)}
        sx={{
          marginBottom: '20px',
        }}
        fullWidth={true}
        InputProps={{
          startAdornment: (
            <InputAdornment position="start">
              <Person />
            </InputAdornment>
          ),
        }}
      />{' '}
      <TextField
        id="outlined-basic"
        label="lastname"
        variant="outlined"
        value={lastname}
        onChange={(event) => setLastname(event.target.value)}
        sx={{
          marginBottom: '20px',
        }}
        fullWidth={true}
        InputProps={{
          startAdornment: (
            <InputAdornment position="start">
              <Person2 />
            </InputAdornment>
          ),
        }}
      />
      <TextField
        id="outlined-basic"
        label="phone"
        variant="outlined"
        value={phone}
        onChange={(event) => setPhone(event.target.value)}
        sx={{
          marginBottom: '20px',
        }}
        fullWidth={true}
        InputProps={{
          startAdornment: (
            <InputAdornment position="start">
              <Phone />
            </InputAdornment>
          ),
        }}
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

const SetuProfilStepWithToast = withToast(SetupProfileStep);

export { SetuProfilStepWithToast as SetupProfileStep };
