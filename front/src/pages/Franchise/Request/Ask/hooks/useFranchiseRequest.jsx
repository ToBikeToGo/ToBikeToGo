import { getApirUrl } from '../../../../../helpers/getApirUrl.js';
import { createContext, useContext, useState } from 'react';
import fetchApi from '../../../../../helpers/fetchApi.js';

const franchiseRequestContext = createContext('');

const FranchiseRequestProvider = ({ children }) => {
  const [franchiseRequest, setFranchiseRequest] = useState({
    franchiseLabel: '',
  });
  const [label, setLabel] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [step, setStep] = useState(0);
  const [requestSend, setRequestSend] = useState(false);

  const getFranchiseRequest = async () => {
    setLoading(true);
    const apiUrl = getApirUrl();
    try {
      const response = await fetch(`${apiUrl}/franchise-request`);
      setFranchiseRequest(response.data);
    } catch (error) {
      setError(error);
    } finally {
      setLoading(false);
    }
  };

  const createFranchiseRequest = async (data) => {
    setLoading(true);
    const apiUrl = getApirUrl();

    try {
      // Create the franchise
      const franchiseResponse = await fetchApi(`${apiUrl}/franchises`, {
        method: 'POST',
        body: JSON.stringify({
          label: data.franchiseLabel,
          isActive: false,
        }),
        headers: {
          'Content-Type': 'application/json',
        },
      });

      console.log('response, franchise', franchiseResponse);
      const franchise = await franchiseResponse.json();

      // Create the shop
      const shopResponse = await fetchApi(`${apiUrl}/shops`, {
        method: 'POST',
        body: JSON.stringify({
          label: data.shopLabel,
          address: data.shopAddress,
          franchise: franchise['@id'],
          isOpened: true,
        }),
        headers: {
          'Content-Type': 'application/json',
        },
      });

      const shop = await shopResponse.json();

      for (const day of data.openingHours) {
        await fetchApi(`${apiUrl}/schedules`, {
          method: 'POST',
          body: JSON.stringify({
            dow: day.dow,
            startTime: day.startTime,
            endTime: day.endTime,
            shops: [shop['@id']],
            startValidity: '2024-01-27T15:43:31.250Z',
            endValidity: '2024-01-27T15:43:31.250Z',
          }),
          headers: {
            'Content-Type': 'application/json',
          },
        });
      }

      const franchiseRequestResponse = await fetchApi(`${apiUrl}/requests`, {
        method: 'POST',
        body: JSON.stringify({
          requestDate: new Date(),
          franchise: franchise['@id'],
        }),
        headers: {
          'Content-Type': 'application/json',
        },
      });
      const franchiseRequest = await franchiseRequestResponse.json();
      console.log('franchiseRequest', franchiseRequest);
    } catch (error) {
      console.error('Error while creating franchise request', error);
      setError(error);
    } finally {
      console.log('franchiseRequest', franchiseRequest);
      setLoading(false);
      setRequestSend(true);
    }
  };

  const checkLabel = (label) => {
    if (/^[a-zA-Z]+$/.test(label) && label.length > 0 && label.length < 55) {
      return null;
    } else {
      console.log('label is not valid');
      return 'Label is not valid';
    }
  };

  const checkAddress = (address) => {
    if (address.length > 0 && address.length < 55) {
      return null;
    } else {
      console.log('address is not valid');
      return 'Address is not valid';
    }
  };

  const handleAddLabel = (label) => {
    setLabel(label);
  };

  const handleNext = (data = {}) => {
    console.log('data', data);
    setStep((prevStep) => prevStep + 1);
  };

  const onSave = (data) => {
    setFranchiseRequest((prevFranchiseRequest) => ({
      ...prevFranchiseRequest,
      ...data,
    }));

    console.log('franchiseRequest', franchiseRequest);
  };

  return (
    <franchiseRequestContext.Provider
      value={{
        franchiseRequest,
        loading,
        error,
        getFranchiseRequest,
        handleAddLabel,
        label,
        handleNext,
        step,
        checkLabel,
        checkAddress,
        onSave,
        createFranchiseRequest,
        requestSend,
      }}
    >
      {children}
    </franchiseRequestContext.Provider>
  );
};

const useFranchiseRequest = () => {
  const context = useContext(franchiseRequestContext);
  if (context === undefined) {
    throw new Error(
      'useFranchiseRequest must be used within a FranchiseRequestProvider'
    );
  }
  return context;
};

export { useFranchiseRequest, FranchiseRequestProvider };
