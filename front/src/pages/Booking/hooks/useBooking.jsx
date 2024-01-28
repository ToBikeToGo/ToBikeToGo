import fetchApi from '../../../helpers/fetchApi.js';
import { getApirUrl } from '../../../helpers/getApirUrl.js';
import { useCallback, useState } from 'react';

export const useBooking = () => {
  const [bookings, setBookings] = useState([]);
  const [isLoading, setIsLoading] = useState(false);
  const [rating, setRating] = useState(2);

  const fetchBookingByUserId = useCallback(async (userId) => {
    setIsLoading(true);
    const apiUrl = getApirUrl();
    const response = await fetchApi(`${apiUrl}/bookings?user=${userId}`);
    const data = await response.json();
    setBookings(data['hydra:member']);
    setIsLoading(false);
  }, []);

  const rateBooking = useCallback(
    async (bookingId) => {
      const apiUrl = getApirUrl();
      const response = await fetchApi(`${apiUrl}/bookings/${bookingId}`, {
        method: 'PATCH',
        body: JSON.stringify({ rating }),
        headers: {
          'Content-Type': 'application/merge-patch+json',
        },
      });
      const data = await response.json();
      setBookings((prevBookings) =>
        prevBookings.map((booking) =>
          booking.id === bookingId
            ? { ...booking, rating: data.rating }
            : booking
        )
      );
    },
    [rating]
  );

  return {
    fetchBookingByUserId,
    bookings,
    isLoading,
    rateBooking,
    rating,
    setRating,
  };
};
