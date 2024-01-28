import { useFranchiseRequest } from '../hooks/useFranchiseRequest.jsx';
import Typography from '@mui/material/Typography';
import { Button, CircularProgress } from '@mui/material';
import withToast from '../../../../../components/HOC/WithToastHOC.jsx';
import { Check } from '@mui/icons-material';
import Box from '@mui/material/Box';

const ConfirmRegistration = ({ setToast }) => {
  const {
    franchiseRequest,
    createFranchiseRequest,
    loading,
    error,
    requestSend,
  } = useFranchiseRequest();
  const handleSubmit = async () => {
    await createFranchiseRequest(franchiseRequest);

    if (error) {
      setToast({
        open: true,
        severity: 'error',
        message: 'An error occurred',
      });
    } else {
      setToast({
        open: true,
        severity: 'success',
        message: 'Your franchise request has been sent',
      });
    }
  };
  return (
    <>
      <Typography
        variant="h2"
        sx={{
          marginBottom: '20px',
        }}
      >
        Here is informations about your franchise and shops:
      </Typography>
      <Box
        sx={{
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          justifyContent: 'center',
          backgroundColor: '#f0f0f0',
          padding: '20px',
          borderRadius: '1em',
          width: '50%',
        }}
      >
        <Typography
          variant="h3"
          sx={{
            marginBottom: '20px',
          }}
        >
          Franchise name
        </Typography>
        <p>{franchiseRequest.franchiseLabel}</p>

        <>
          <Typography
            variant="h3"
            sx={{
              marginBottom: '20px',
            }}
          >
            Shop name
          </Typography>
          <p>{franchiseRequest.shopLabel}</p>
          <Typography
            variant="h3"
            sx={{
              marginBottom: '20px',
            }}
          >
            Shop address 📬
          </Typography>
          <p>{franchiseRequest.shopAddress}</p>

          {Object.keys(franchiseRequest.openingDays)
            .filter((day) => franchiseRequest.openingDays[day])
            .map((day) => (
              <>
                <Typography
                  variant="h3"
                  sx={{
                    marginBottom: '20px',
                  }}
                >
                  Opening day 📅
                </Typography>
                <p>{day}</p>
                <Typography
                  variant="h3"
                  sx={{
                    marginBottom: '20px',
                  }}
                >
                  Opening hours 🕰️
                </Typography>
                <p>
                  {new Date(
                    franchiseRequest?.openingHours[0]?.startTime
                  ).toLocaleTimeString()}{' '}
                  -{' '}
                  {new Date(
                    franchiseRequest?.openingHours[0]?.endTime
                  ).toLocaleTimeString()}
                </p>
              </>
            ))}
        </>

        {requestSend ? (
          <Typography
            variant="h3"
            sx={{
              marginBottom: '20px',
            }}
          >
            Your request has been successfully sent to our team !
          </Typography>
        ) : (
          <Button
            variant="outlined"
            color="black"
            sx={{
              marginTop: '2em',
              backgroundColor: '#ffffff',
            }}
            size={'large'}
            onClick={handleSubmit}
          >
            {loading ? (
              <CircularProgress color={'secondary'} className={'m-2'} />
            ) : (
              <>
                Confirm{' '}
                <Check
                  sx={{
                    marginLeft: '5px',
                  }}
                />{' '}
              </>
            )}
          </Button>
        )}
      </Box>
    </>
  );
};

const ConfirmRegistrationWithToast = withToast(ConfirmRegistration);

export { ConfirmRegistrationWithToast as ConfirmRegistration };
