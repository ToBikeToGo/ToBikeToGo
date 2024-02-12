import withToast from '../../../../components/HOC/WithToastHOC.jsx';
import { FranchiseRequestProvider } from './hooks/useFranchiseRequest.jsx';
import Box from '@mui/material/Box';
import { YouWantToJoinUs } from './components/YouWantTOJoinUs.jsx';
import { CreateNameAndLocal } from './components/CreateNameAndLocal.jsx';
import { CreateShops } from './components/AddShop.jsx';
import { useState } from 'react';
import { ConfirmRegistration } from './components/ConfirmRegistration.jsx';

const FranchiseRequestStepper = () => {
  const [step, setStep] = useState(0);
  const handleNext = () => {
    setStep(step + 1);
  };
  return (
    <Box
      style={{
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        justifyContent: 'center',
        backgroundColor: 'white',
        padding: '20px',
        borderRadius: '3em',
        width: '50%',
        margin: '8em',
        minHeight: '50vh',
        '@media (min-width:600px)': {
          width: '60%',
        },
      }}
    >
      <FranchiseRequestProvider>
        {step === 0 && <YouWantToJoinUs handleNext={handleNext} />}
        {step === 1 && <CreateNameAndLocal handleNext={handleNext} />}
        {step === 2 && <CreateShops handleNext={handleNext} />}
        {step === 3 && <ConfirmRegistration />}
      </FranchiseRequestProvider>
    </Box>
  );
};

const FranchiseRequestWithToast = withToast(FranchiseRequestStepper);

export { FranchiseRequestWithToast as FranchiseRequest };
