import React, { useEffect } from 'react';
import { getApirUrl } from '../helpers/getApirUrl.js';

const BookingContext = React.createContext('');

const useBooking = () => {
  const [bookingDates, setBookingDates] = React.useState({});

  return {
    bookingDates,
    setBookingDates,
  };
};

const BookingProvider = ({ children }) => {
  const { bookingDates, setBookingDates } = useBooking();

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
