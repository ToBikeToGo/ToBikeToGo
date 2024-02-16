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
import { getApirUrl, getMediaUrl } from '../../helpers/getApirUrl.js';
import { useNavigate, Link } from 'react-router-dom';
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
    startLoading();
    fetchApi(
      `${getMediaUrl()}auth
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
          return Promise.reject(); // Add this line to stop the promise chain
        }
        stopLoading();
        return response;
      })
      .then(async (response) => {
        const data = await response.json();
        stopLoading();
        if (data.token) {
          saveTokeInLocalStorage(data.token);
        }
        stopLoading();
        if (response.status === 200) {
          setToast({
            open: true,
            message: 'Login successful',
            severity: 'success',
          });

          console.log('here');

          const redirectAfterLogin = localStorage.getItem('redirectAfterLogin');
          if (redirectAfterLogin) {
            localStorage.removeItem('redirectAfterLogin');
            window.location.href = redirectAfterLogin;
            setTimeout(() => {
              navigate(redirectAfterLogin);
            }, 2000);
          } else {
            navigate('/shops');
          }
        } else {
          // If the status code is not 200, display an error message
          setToast({
            open: true,
            message: 'Something went wrong',
            severity: 'error',
          });
        }
      })
      .catch((error) => {
        console.error(error);
      });
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
            onKeyDown={(e) => e.key === 'Enter' && handleSubmit()}
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
            onKeyDown={(e) => e.key === 'Enter' && handleSubmit()}
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
        <Link to="/register">Sign up</Link>
      </Box>
    </StyledWrapper>
  );
};

export default withToast(Login);
