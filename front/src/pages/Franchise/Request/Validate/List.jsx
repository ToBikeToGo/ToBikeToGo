import withToast from '../../../../components/HOC/WithToastHOC.jsx';
import { useFranchiseRequestValidation } from './hooks/useFranchiseRequestValidation.jsx';
import Typography from '@mui/material/Typography';
import {
  CircularProgress,
  Container,
  Pagination,
  useTheme,
} from '@mui/material';
import Avatar from '@mui/material/Avatar';
import Button from '@mui/material/Button';
import { Cancel, CheckCircle } from '@mui/icons-material';
import * as React from 'react';

const ShopInfo = ({ shop }) => {
  return (
    <div className="shop-info m-auto">
      <Typography variant="h3" gutterBottom>
        Shop Info
      </Typography>
      <h3>{shop.label}</h3>
      <p>{shop.address}</p>
    </div>
  );
};

const ListRequest = ({ setToast }) => {
  const {
    franchiseRequestValidation,
    approveRequest,
    declineRequest,
    totalPage,
    page,
    onChangePage,
    isLoading,
  } = useFranchiseRequestValidation();
  const theme = useTheme();

  if (isLoading) {
    return (
      <CircularProgress
        sx={{
          margin: '20px',
        }}
      />
    );
  }

  return (
    <div
      className={
        'flex flex-col h-100 w-full lg:w-2/3 justify-center items-center p-5'
      }
    >
      <Container>
        <Typography variant="h2" gutterBottom>
          Franchise Request{' '}
        </Typography>
        {franchiseRequestValidation?.map((request) => (
          <div
            className={'flex bg-white m-5 p-8 rounded-xl shadow-xl '}
            style={{
              backgroundColor:
                request.status === undefined
                  ? 'white'
                  : request.status
                    ? theme.palette.success.main
                    : theme.palette.error.main,
            }}
            key={'Test'}
          >
            <div className="flex w-full flex-col md:flex-row ">
              <Avatar alt={'Test'} src="/static/images/avatar/2.jpg" />
              <div className={'sm:mb-5'}>
                <p className={'ml-3'}>{request?.createdBy?.firstname}</p>
                <p className={'ml-3'}>{request?.createdBy?.lastname}</p>
              </div>
              <div
                style={{
                  borderLeft: '1px solid #000',
                  height: 'auto',
                  margin: '0 20px',
                }}
              ></div>{' '}
              <p className={' mr-5 sm:mb-5'}>{request.franchise.label}</p>
              <ShopInfo shop={request?.franchise.shops[0]} />
              {request.status === undefined && (
                <div className="flex ml-auto  w-1/3 items-center">
                  <>
                    <Button
                      style={{
                        backgroundColor: theme.palette.success.main,
                        marginLeft: '1em',
                        color: 'white',
                      }}
                      onClick={() => approveRequest(request)}
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
                      onClick={() => declineRequest(request.id)}
                    >
                      Reject
                      <Cancel className={'ml-2'} />
                    </Button>{' '}
                  </>
                </div>
              )}
            </div>
          </div>
        ))}{' '}
      </Container>
      <div className={'p-5 px-8 rounded-xl bg-white shadow-xl '}>
        <Pagination
          count={totalPage}
          page={page}
          variant="outlined"
          onChange={onChangePage}
        />
      </div>
    </div>
  );
};

const ListWithToast = withToast(ListRequest);

export { ListWithToast as ListRequest };
