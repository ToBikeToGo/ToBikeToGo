import { useState } from 'react';
import { Divider, Step, StepLabel, Stepper } from '@mui/material';
import Typography from '@mui/material/Typography';
import { useRegistrationContext } from './hooks/RegistationContext.jsx';
import Box from '@mui/material/Box';
import CredentialInformationsStep from './components/CredentialInformationsStep.jsx';
import { SetupProfileStep } from './components/SetupProfileStep.jsx';
import { SetupPreferenceStep } from './components/PreferencesStep.jsx';

const steps = [
  'Add your email and password',
  'Set up your profile',
  'Add your preferences',
];

export const RegistrationStepper = () => {
  const { activeStep, setActiveStep, skipped, setSkipped } =
    useRegistrationContext();

  const isStepOptional = (step) => {
    return false;
  };

  const isStepSkipped = (step) => {
    return skipped.has(step);
  };

  return (
    <Box
      sx={{
        width: '100%',
        display: 'flex',
        alignItems: 'center',
        '@media (max-width: 600px)': {
          flexDirection: 'column',
        },
      }}
    >
      <Stepper
        activeStep={activeStep}
        orientation={'vertical'}
        sx={{
          width: '50%',
        }}
      >
        {steps.map((label, index) => {
          const stepProps = {};
          const labelProps = {};
          if (isStepOptional(index)) {
            labelProps.optional = (
              <Typography variant="caption">Optional</Typography>
            );
          }
          if (isStepSkipped(index)) {
            stepProps.completed = false;
          }
          return (
            <Step key={label} {...stepProps}>
              <StepLabel {...labelProps}>{label}</StepLabel>
            </Step>
          );
        })}
      </Stepper>
      <Divider orientation="vertical" flexItem />
      <Box
        sx={{
          width: '50%',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
        }}
      >
        {activeStep === 0 && <CredentialInformationsStep />}
        {activeStep === 1 && <SetupProfileStep />}
        {activeStep === 2 && <SetupPreferenceStep />}
      </Box>
    </Box>
  );
};
