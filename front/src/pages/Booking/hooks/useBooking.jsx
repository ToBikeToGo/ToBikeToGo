import fetchApi from '../../../helpers/fetchApi.js';
import { getApirUrl } from '../../../helpers/getApirUrl.js';
import { useCallback, useState } from 'react';

export const useBooking = () => {
  const [bookings, setBookings] = useState([]);
  const [isLoading, setIsLoading] = useState(false);
  const [rating, setRating] = useState(2);

  const formatBookingsPlanning = (bookings) => {
    return bookings?.map((b) =>
      {
        const startDateDate = new Date(b.startDate);

        return {
        id: b.id,
        title: b.bike.label || 'Bike reservation',
        start: startDateDate,
        end:new Date(startDateDate.getTime() + 30 * 60000),
       bgColor: 'red'
      }
      }
      );
  };

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

  const getBookingByBike = useCallback(async (bikeId) => {
    const apiUrl = getApirUrl();
    try {
      setIsLoading(true);
      const response = await fetchApi(`${apiUrl}/bookings?bike=${bikeId}`);
      const data = await response.json();
      setIsLoading(false);
      return data['hydra:member'];
    } catch (error) {
      console.error('Error fetching bookings', error);
    }
  }, []);

  const getBookingsByShop = useCallback(async (shopId) => {
    const apiUrl = getApirUrl();
    try {
      setIsLoading(true);
      const response = await fetchApi(`${apiUrl}/shops/${shopId}/bookings`);
      const data = await response.json();
      setBookings(data);1
      setIsLoading(false);
    } catch (error) {
      console.error('Error fetching bookings', error);
    }
  }, []);

  const getBookingsByUser = useCallback(async (userId) => {
    const apiUrl = getApirUrl();
    try {
      setIsLoading(true);
      const response = await fetchApi(`${apiUrl}/bookings?customer=${userId}`);
      const data = await response.json();
      setIsLoading(false);
     setBookings(bookings);
    } catch (error) {
      console.error('Error fetching bookings', error);
    }
  }, []);

  return {
    formatBookingsPlanning,
    getBookingsByUser,
    getBookingsByShop,
    getBookingByBike,
    fetchBookingByUserId,
    bookings,
    isLoading,
    rateBooking,
    rating,
    setRating,
  };
};
