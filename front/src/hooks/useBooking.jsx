import React, { useCallback, useEffect } from 'react';
import { getApirUrl } from '../helpers/getApirUrl.js';
import fetchApi from '../helpers/fetchApi.js';

const BookingContext = React.createContext('');

const useBooking = () => {
  const [isLoading, setIsLoading] = React.useState(false);
  const [bookingDates, setBookingDates] = React.useState({
    startDate: new Date(),
    endDate: new Date(new Date().setDate(new Date().getDate() + 1)),
  });
  const [availableBikes, setAvailableBikes] = React.useState([]);
  const [searchParams, setSearchParams] = React.useState({
    brand: '',
    category: '',
    maxPrice: 1000,
    city: '',
    label: '',
  });

  const getAvailableBikes = async () => {
    const { startDate, endDate } = bookingDates;
    const { brand, category, maxPrice, city, label } = searchParams;
    const apiUrl = getApirUrl();
    // Create a URLSearchParams instance
    const params = new URLSearchParams({
      startAt: startDate?.toLocaleDateString('fr-FR'),
      endAt: endDate?.toLocaleDateString('fr-FR'),
      brand,
      category,
      'price[lte]': maxPrice,
      'shop.city': city,
      label,
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
