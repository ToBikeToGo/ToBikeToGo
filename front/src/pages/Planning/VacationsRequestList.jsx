import { CircularProgress, Pagination, useTheme } from '@mui/material';
import Typography from '@mui/material/Typography';
import { Container } from '@mui/material';
import Button from '@mui/material/Button';
import { useShopContext } from '../../hooks/UseShop.jsx';
import { useCallback, useEffect, useState } from 'react';
import Avatar from '@mui/material/Avatar';
import * as React from 'react';
import { useVacationContext } from './hooks/useVacation.jsx';
import withToast from '../../components/HOC/WithToastHOC.jsx';
import { Cancel, CheckCircle } from '@mui/icons-material';
import { useLoading } from '../../hooks/useLoading.jsx';
import { isVacationApproved } from '../../components/Planning/helpser/vacations.ts';

const VacationsRequestList = ({ setToast }) => {
  const { shop } = useShopContext();
  const { getShopVacations, acceptVacationRequest, rejectVacationRequest } =
    useVacationContext();
  const { isLoading, startLoading, stopLoading } = useLoading();

  const [vacations, setVacations] = useState([]);
  const theme = useTheme();

  const refreshVacations = useCallback(() => {
    startLoading();
    getShopVacations()
      .then((data) => {
        setVacations(data.vacations);
        stopLoading();
      })
      .catch((error) => {
        setToast({
          open: true,
          message: 'Request failed',
          severity: 'error',
        });
      });
  }, [getShopVacations, setToast, startLoading, stopLoading]);

  useEffect(() => {
    startLoading();
    refreshVacations();
  }, [refreshVacations, startLoading]);

  if (isLoading) {
    return <CircularProgress color={'secondary'} className={'m-5'} />;
  }

  const handleAcceptVacationRequest = (vacationId) => {
    startLoading();
    acceptVacationRequest(vacationId)
      .then((data) => {
        setToast({
          open: true,
          message: 'Demande acceptée',
          severity: 'success',
        });
        refreshVacations();
      })
      .catch((error) => {
        alert('errror');
        setToast({
          open: true,
          message: 'Request failed',
          severity: 'error',
        });
      });
  };

  const handleRejectVacationRequest = (vacationId) => {
    startLoading();
    rejectVacationRequest(vacationId)
      .then((data) => {
        refreshVacations();
        setToast({
          open: true,
          message: 'Request successful',
          severity: 'success',
        });
        getShopVacations();
      })
      .catch((error) => {
        setToast({
          open: true,
          message: 'Request failed',
          severity: 'error',
        });
      });
  };

  return (
    <div
      className={
        'flex flex-col h-100 w-full lg:w-2/3 justify-center items-center p-5'
      }
    >
      <Container>
        <Typography variant="h2" gutterBottom>
          Vacations request for {shop?.label}
        </Typography>
        {vacations?.map((vacation) => (
          <div
            className={'flex bg-white m-5 p-8 rounded-xl shadow-xl '}
            style={{
              backgroundColor: isVacationApproved(vacation)
                ? theme.palette.success.main
                : 'white',
            }}
            key={vacation.id}
          >
            <div className="flex w-full flex-col md:flex-row ">
              <Avatar
                alt={vacation.user.firstname}
                src="/static/images/avatar/2.jpg"
              />
              <div className={'sm:mb-5'}>
                <p className={'ml-3'}>{vacation.user.firstname}</p>
                <p className={'ml-3'}>{vacation.user.lastname}</p>
              </div>
              <div
                style={{
                  borderLeft: '1px solid #000',
                  height: 'auto',
                  margin: '0 20px',
                }}
              ></div>{' '}
              <p className={' mr-5 sm:mb-5'}>{vacation.description}</p>
              <div class="flex ml-auto  w-1/3 items-center">
                {!vacation.status && (
                  <>
                    <Button
                      style={{
                        backgroundColor: theme.palette.success.main,
                        marginLeft: '1em',
                      }}
                      onClick={() => handleAcceptVacationRequest(vacation.id)}
                    >
                      Approve <CheckCircle className={'ml-2'} />
                    </Button>
                    <Button
                      variant="contained"
                      color="red"
                      sx={{
                        marginLeft: '1em',
                        color: 'white',
                      }}
                      onClick={() => handleRejectVacationRequest(vacation.id)}
                    >
                      Reject
                      <Cancel className={'ml-2'} />
                    </Button>{' '}
                  </>
                )}
              </div>
            </div>
          </div>
        ))}{' '}
      </Container>
      <div className={'p-5 px-8 rounded-xl bg-white shadow-xl '}>
        <Pagination count={10} variant="outlined" />
      </div>
    </div>
  );
};
const VacationsRequestListWithToast = withToast(VacationsRequestList);

export { VacationsRequestListWithToast as VacationsRequestList };
