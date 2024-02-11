import React, { useCallback, useEffect } from 'react';
import { getApirUrl } from '../helpers/getApirUrl.js';
import fetchApi from '../helpers/fetchApi.js';

const BookingContext = React.createContext('');

const useBooking = () => {
  const [isLoading, setIsLoading] = React.useState(false);
  const [bookingDates, setBookingDates] = React.useState({});
  const [availableBikes, setAvailableBikes] = React.useState([]);
  const [searchParams, setSearchParams] = React.useState({
    brand: '',
    category: '',
    maxPrice: 1000,
  });

  const getAvailableBikes = async () => {
    const { startDate, endDate } = bookingDates;
    const { brand, category, maxPrice } = searchParams;
    const apiUrl = getApirUrl();
    // Create a URLSearchParams instance
    const params = new URLSearchParams({
      startDate,
      endDate,
      brand,
      category,
      'price[lte]': maxPrice,
    });
    try {
      setIsLoading(true);
      const response = await fetchApi(`${apiUrl}/bikes?${params.toString()}`);

      const data = await response.json();
      setAvailableBikes(data['hydra:member']);
      setIsLoading(false);
    } catch (error) {
      console.error('Error fetching available bikes', error);
    }
  };

  return {
    bookingDates,
    setBookingDates,
    searchParams,
    setSearchParams,
    availableBikes,
    getAvailableBikes,
    isLoading,
  };
};

const BookingProvider = ({ children }) => {
  const {
    bookingDates,
    setBookingDates,
    searchParams,
    setSearchParams,
    availableBikes,
    getAvailableBikes,
    isLoading,
  } = useBooking();

  const changeBookingDates = (startDate, endDate) => {
    setBookingDates({
      startDate: startDate,
      endDate: endDate,
    });
  };
  return (
    <BookingContext.Provider
      value={{
        bookingDates,
        changeBookingDates,
        searchParams,
        setSearchParams,
        availableBikes,
        getAvailableBikes,
        isLoading,
      }}
    >
      {children}
    </BookingContext.Provider>
  );
};

const useBookingContext = () => {
  const context = React.useContext(BookingContext);
  if (context === undefined) {
    throw new Error('useBookingContext must be used within a UserProvider');
  }
  return context;
};

export { BookingProvider, useBookingContext };
