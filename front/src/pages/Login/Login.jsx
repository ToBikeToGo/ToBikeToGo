import Typography from '@mui/material/Typography';
import Box from '@mui/material/Box';
import { CircularProgress, Input, TextField } from '@mui/material';
import { Google, Label } from '@mui/icons-material';
import Button from '@mui/material/Button';
import styled from 'styled-components';

import FemmeWithABike from '../../assets/images/WomenWithABike.png';
import { useState } from 'react';
import fetchApi from '../../helpers/fetchApi.js';
import withToast from '../../components/HOC/WithToastHOC.jsx';
import { getApirUrl } from '../../helpers/getApirUrl.js';
import { useNavigate } from 'react-router-dom';
import { useLoading } from '../../hooks/useLoading.jsx';
import { checkEmail } from '../../helpers/checkEmail.js';

const StyledInput = styled(TextField)`
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

const saveTokeInLocalStorage = (token) => {
  localStorage.setItem('token', token);
};

const Login = ({ setToast, Toast }) => {
  const [password, setPassword] = useState('');
  const [email, setEmail] = useState('');
  const { startLoading, stopLoading, isLoading } = useLoading();
  const navigate = useNavigate();

  const handleEmailChange = (event) => {
    setEmail(event.target.value);
  };

  const handlePasswordChange = (event) => {
    setPassword(event.target.value);
  };

  const handleSubmit = (event) => {
    const apiUrl = getApirUrl();

    // event.preventDefault();
    startLoading();
    if (checkEmail(email)) {
      fetchApi(
        `http://localhost:8888/auth
`,
        {
          method: 'POST',
          body: JSON.stringify({
            email: email,
            password: password,
          }),
          headers: {
            'Content-Type': 'application/json',
          },
        }
      )
        .then((response) => {
          if (!response.ok) {
            setToast({
              open: true,
              message: 'Email or password is not valid',
              severity: 'error',
            });
          }

          return response.json();
        })
        .then(async (data) => {
          stopLoading();
          setToast({
            open: true,
            message: 'Login successful',
            severity: 'success',
          });

          if (data.token) {
            saveTokeInLocalStorage(data.token);
          }

          setTimeout(() => {
            navigate('/shops');
          }, 2000);
        })
        .catch((error) => {
          console.error(error);
        });
    } else {
      setToast({
        open: true,
        message: 'Email is not valid',
        severity: 'error',
      });
    }
  };

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
        <form onSubmit={handleSubmit}>
          <Typography variant="h2" m={5}>
            Login
          </Typography>
          <Box
            sx={{
              display: 'flex',
              flexDirection: 'column',
              alignItems: 'center',
              justifyContent: 'center',
              width: '100%',
              margin: 'auto',
              border: '1px solid black',
            }}
            bgcolor={'white'}
          >
            <Typography variant="h6" m={2}>
              <Google /> Login with Google
            </Typography>
          </Box>
          <Typography variant="h6" m={5}>
            or
          </Typography>
          <StyledInput
            placeholder="Username"
            sx={{
              marginBottom: '20px',
            }}
            onChange={handleEmailChange}
          />
          <StyledInput
            placeholder="Password"
            sx={{
              marginBottom: '20px',
            }}
            type={'password'}
            onChange={handlePasswordChange}
          />
          <Button
            variant="contained"
            sx={{
              width: '100%',
              marginBottom: '20px',
              borderRadius: '20px',
              height: '4em',
            }}
            size={'large'}
            onClick={handleSubmit}
          >
            {isLoading ? (
              <CircularProgress
                color={'black'}
                sx={{
                  width: '20px',
                  height: '50%',
                }}
              />
            ) : (
              'Login'
            )}
          </Button>
        </form>
        <Typography variant="h6">Don't have an account ?</Typography>
        <a>Sign Up</a>
      </Box>
    </StyledWrapper>
  );
};

export default withToast(Login);
