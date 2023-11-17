import Typography from '@mui/material/Typography';
import Box from '@mui/material/Box';
import { Input } from '@mui/material';
import { Google, Label } from '@mui/icons-material';
import Button from '@mui/material/Button';
import styled from 'styled-components';

import FemmeWithABike from '../../assets/images/WomenWithABike.png';

const StyledInput = styled(Input)`
  width: 100%;
  margin-bottom: 20px;
  border-radius: 20px;
`;

const StyledWrapper = styled(Box)`
  display: flex;
  flex-direction: row;
  width: 80%;
  margin: auto;
  border-radius: 12px;
  border: 1px solid #e7d9d9;
  box-shadow: 0 4px 4px rgba(0, 0, 0, 0.25);
`;

const StyleBigImage = styled.img`
  width: 50%;
  height: 100%;
  object-fit: cover;
`;

const Login = () => {
  return (
    <StyledWrapper>
      <StyleBigImage src={FemmeWithABike} />
      <Box
        sx={{
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          justifyContent: 'center',
          backgroundColor: 'background.default',
          borderRadius: '12px',
          width: '100%',
          margin: 'auto',
          padding: '50px',
          borderLeft: '1px solid #e7d9d9',
        }}
        bgColor={'primary'}
      >
        <Typography variant="h2" m={5}>
          Login
        </Typography>
        <Box
          sx={{
            display: 'flex',
            flexDirection: 'column',
            alignItems: 'center',
            justifyContent: 'center',
            backgroundColor: 'background.default',
            width: '100%',
            margin: 'auto',
            border: '1px solid black',
          }}
        >
          <Typography variant="h6" m={2}>
            <Google /> Login with Google
          </Typography>
        </Box>
        <Typography variant="h6" m={5}>
          or
        </Typography>
        <Label>Username</Label>
        <StyledInput
          placeholder="Username"
          sx={{
            marginBottom: '20px',
          }}
        />
        <Label>Password</Label>
        <StyledInput
          placeholder="Password"
          sx={{
            marginBottom: '20px',
          }}
        />
        <Button
          variant="contained"
          sx={{
            width: '100%',
            marginBottom: '20px',
            borderRadius: '20px',
          }}
        >
          Login
        </Button>
        <Typography variant="h6">Don't have an account ?</Typography>
        <a>Sign Up</a>
      </Box>
    </StyledWrapper>
  );
};

export default Login;
