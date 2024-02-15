import { useRegistrationContext } from '../hooks/RegistationContext.jsx';
import { useState } from 'react';
import Box from '@mui/material/Box';
import { InputAdornment, Select, TextField } from '@mui/material';
import Button from '@mui/material/Button';
import { ArrowRight, Check, Person, Phone } from '@mui/icons-material';
import withToast from '../../../components/HOC/WithToastHOC.jsx';
import { useUserContext } from '../../../hooks/UserContext.jsx';
import MenuItem from '@mui/material/MenuItem';
import Typography from '@mui/material/Typography';
const SetupPreferenceStep = ({ setToast }) => {
  const { register } = useRegistrationContext();
  const { userToRegister, setUserToRegister } = useUserContext();

  const [preferences, setPreferences] = useState({
    locale: 'EN',
    type_account: 'franchisee',
  });

  const handleLocaleChange = (event) => {
    setPreferences((prevPreferences) => ({
      ...prevPreferences,
      locale: event.target.value,
    }));
  };

  const handleAccountTypeChange = (event) => {
    setPreferences((prevPreferences) => ({
      ...prevPreferences,
      type_account: event.target.value,
    }));
  };

  const confirmHandleNext = () => {
    setUserToRegister((prevState) => ({
      ...prevState,
      ...preferences,
    }));

    register({
      ...userToRegister,
    }).then((res) => {
      if (!res.ok) {
        setToast({
          open: true,
          severity: 'error',
          message: 'Error while registering',
        });
      } else {
        setToast({
          open: true,
          severity: 'success',
          message: 'Account created',
        });
      }
    });
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
      <Typography
        variant="h6"
        sx={{
          marginBottom: '20px',
        }}
      >
        Choose your language preference
      </Typography>
      <Select
        label={'Locale'}
        value={preferences.locale}
        onChange={handleLocaleChange}
        sx={{ marginBottom: '20px' }}
        fullWidth={true}
      >
        <MenuItem value={'EN'}>English</MenuItem>
        <MenuItem value={'FR'}>French</MenuItem>
        {/* Add more options as needed */}
      </Select>

      <Typography
        variant="h6"
        sx={{
          marginBottom: '20px',
        }}
      >
        Why are you creating an account?
      </Typography>

      <Select
        label={'Account Type'}
        value={preferences.type_account}
        onChange={handleAccountTypeChange}
        sx={{ marginBottom: '20px' }}
        fullWidth={true}
      >
        <MenuItem value={'franchisee'}>to join bikeToGo business</MenuItem>
        <MenuItem value={'user'}>to rent bikes</MenuItem>
      </Select>
      <Button
        variant="outlined"
        color="black"
        sx={{
          marginTop: '2em',
        }}
        size={'large'}
        onClick={confirmHandleNext}
      >
        Confirm
        <Check sx={{ marginLeft: '10px' }} />
      </Button>
    </Box>
  );
};

const SetuPrefStepWithToast = withToast(SetupPreferenceStep);

export { SetuPrefStepWithToast as SetupPreferenceStep };
