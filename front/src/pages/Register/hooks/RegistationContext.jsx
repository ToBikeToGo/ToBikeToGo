import React, { createContext, useCallback, useMemo, useState } from 'react';
import { getApirUrl } from '../../../helpers/getApirUrl.js';
import fetchApi from '../../../helpers/fetchApi.js';

const RegistationContext = createContext();

const useRegistrationContext = () => {
  const context = React.useContext(RegistationContext);
  if (context === undefined) {
    throw new Error(
      'useRegistrationContext must be used within a RegistationProvider'
    );
  }
  return context;
};

export const isStepOptional = (step) => {
  return false;
};

const RegistationProvider = ({ children }) => {
  const [activeStep, setActiveStep] = useState(0);
  const [skipped, setSkipped] = useState(new Set());

  const isStepSkipped = (step) => {
    return skipped.has(step);
  };
  const handleNext = () => {
    let newSkipped = skipped;

    if (isStepSkipped(activeStep)) {
      newSkipped = new Set(newSkipped.values());
      newSkipped.delete(activeStep);
    }

    setActiveStep((prevActiveStep) => prevActiveStep + 1);
    setSkipped(newSkipped);
  };

  const register = useCallback(
    async ({ email, password, firstname, phone }) => {
      const apiUrl = getApirUrl();
      fetchApi(`${apiUrl}/register`, {
        method: 'POST',
        body: JSON.stringify({
          email: email,
          password: password,
          roles: ['admin'],
          lastname: firstname,
          firstname: firstname,
          phone: phone,
          locale: 'EN',
        }),
        headers: {
          'Content-Type': 'application/json',
        },
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then((data) => {
          if (data && data.token) {
            localStorage.setItem('token', data.token);
          } else {
            throw new Error('Data is not in the expected format');
          }
        });
    },
    []
  );

  const registrationValues = useMemo(() => {
    return {
      activeStep,
      setActiveStep,
      skipped,
      setSkipped,
      handleNext,
      register,
    };
  }, [activeStep, setActiveStep, skipped, setSkipped, handleNext, register]);

  return (
    <RegistationContext.Provider value={registrationValues}>
      {children}
    </RegistationContext.Provider>
  );
};

export { RegistationProvider, useRegistrationContext };
