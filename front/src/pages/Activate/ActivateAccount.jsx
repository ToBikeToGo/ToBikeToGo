import { useNavigate, useParams } from 'react-router-dom';
import { TextField } from '@mui/material';
import Button from '@mui/material/Button';
import Typography from '@mui/material/Typography';
import fetchApi from '../../helpers/fetchApi.js';
import { getApirUrl } from '../../helpers/getApirUrl.js';
import { useState } from 'react';
import withToast from '../../components/HOC/WithToastHOC.jsx';

const ActivateAccountForm = ({ setToast, token, userId }) => {
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [error, setError] = useState(null);

  const navigate = useNavigate();

  const handleSubmit = (e) => {
    e.preventDefault();
    const apiUrl = getApirUrl();

    if (password !== confirmPassword) {
      setError('Passwords do not match');
    } else {
      setError('Votre mot de passe est en cours de validation');
      fetchApi(`${apiUrl}/activate/${token}/${userId}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          token,
          password: password,
          user: userId,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          console.log('Success:', data);
          setToast({
            isOpen: true,
            message: 'Account activated',
            severity: 'success',
          });
          navigate('/login');
        })
        .catch((error) => {
          console.error('Error:', error);
          setToast({
            isOpen: true,
            message: 'Error activating account',
            severity: 'error',
          });
        });
    }
  };

  return (
    <form className={'flex flex-col bg-white  p-5 rounded-xl m-5'}>
      <h1>Activate your account</h1>
      <p className={'text-black'}>{error}</p>

      <TextField
        label={'Password'}
        type={'password'}
        className={'m-2'}
        style={{
          margin: '1em',
        }}
        onChange={(e) => setPassword(e.target.value)}
      />
      <TextField
        label={'Confirm password'}
        type={'password'}
        className={'m-2'}
        style={{
          margin: '1em',
        }}
        onChange={(e) => setConfirmPassword(e.target.value)}
      />
      <Button
        variant="outlined"
        color="black"
        sx={{
          marginTop: '2em',
        }}
        type={'submit'}
        onClick={handleSubmit}
      >
        Activate
      </Button>

      <Button
        variant="outlined"
        color="black"
        sx={{
          marginTop: '2em',
        }}
        type={'submit'}
        onClick={handleSubmit}
      >
        Je connais déjà mon mot de passe
      </Button>
    </form>
  );
};
export const ActivateAccount = ({ setToast }) => {
  const { token, userId } = useParams();

  return (
    <div>
      {token && userId ? (
        <ActivateAccountForm
          setToast={setToast}
          token={token}
          userId={userId}
        />
      ) : (
        <p>Invalid token or userId</p>
      )}
    </div>
  );
};

export default withToast(ActivateAccount);
