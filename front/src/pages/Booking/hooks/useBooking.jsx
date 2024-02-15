import fetchApi from '../../../helpers/fetchApi.js';
import { getApirUrl } from '../../../helpers/getApirUrl.js';
import { useCallback, useState } from 'react';
import { usePagination } from '../../../hooks/usePagination.jsx';

export const useBooking = () => {
  const [bookings, setBookings] = useState([]);
  const [isLoading, setIsLoading] = useState(false);
  const [rating, setRating] = useState(2);

  const [bookingData, setBookingData] = useState({
    startDate: new Date(),
    endDate: new Date(),
    bike: null,
    user: null,
  });

  const { page, setPage, onChangePage, setTotalPage, totalPage } =
    usePagination(0);

  const apiUrl = getApirUrl();

  const formatBookingsPlanning = (bookings) => {
    return bookings?.map((b) => {
      const startDateDate = new Date(b.startDate);

      return {
        id: b.id,
        title: b.bike.label || 'Bike reservation',
        start: startDateDate,
        end: new Date(startDateDate.getTime() + 30 * 60000),
        bg: 'rgba(79,255,20,0.25)',
      };
    });
  };

  const postBooking = useCallback(async () => {
    console.log('bookingData', bookingData);
    const response = await fetchApi(`${apiUrl}/bookings/${bookingData.id}`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/merge-patch+json',
      },
      body: JSON.stringify({
        startDate: bookingData.startDate,
        endDate: bookingData.endDate,
        rating: 0,
        status: true,
        user: bookingData.user,
        bike: bookingData.bike,
        payment: '/api/payments/1',
      }),
    });
    const data = await response.json();
    return data;
  }, [apiUrl, bookingData]);

  const cancelBooking = useCallback(async (bookingId) => {
    const response = await fetchApi(`${apiUrl}/bookings/${bookingId}`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/merge-patch+json',
      },
      body: JSON.stringify({
        status: false,
      }),
    });
    const data = await response.json();
    return data;
  }, []);

  const fetchBookingByUserId = useCallback(async (userId) => {
    setIsLoading(true);
    const response = await fetchApi(`${apiUrl}/bookings?user=${userId}`);
    const data = await response.json();
    setBookings(data['hydra:member']);
    setIsLoading(false);
  }, []);

  const rateBooking = useCallback(
    async (bookingId) => {
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
    [apiUrl, rating]
  );

  const getBookingByBike = useCallback(async (bikeId) => {
    try {
      setIsLoading(true);
      const response = await fetchApi(`${apiUrl}/bookings?bike=${bikeId}`);
      const data = await response.json();
      setIsLoading(false);
      setTotalPage(Math.ceil(data['hydra:totalItems'] / 10));
      return data['hydra:member'];
    } catch (error) {
      console.error('Error fetching bookings', error);
    }
  }, []);

  const getBookingsByShop = useCallback(async (shopId) => {
    try {
      setIsLoading(true);
      const response = await fetchApi(`${apiUrl}/shops/${shopId}/bookings`);
      const data = await response.json();
      setBookings(data);
      setTotalPage(Math.ceil(data['hydra:totalItems'] / 10));

      setIsLoading(false);
    } catch (error) {
      console.error('Error fetching bookings', error);
    }
  }, []);

  const getBookingsByUser = useCallback(
    async (userId) => {
      try {
        setIsLoading(true);
        const response = await fetchApi(`${apiUrl}/bookings?user=${userId}`);
        const data = await response.json();
        setIsLoading(false);
        setBookings(data['hydra:member']);
      } catch (error) {
        console.error('Error fetching bookings', error);
      }
    },
    [apiUrl]
  );

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
    bookingData,
    totalPage,
    setBookingData,
    page,
    setPage,
    onChangePage,
    postBooking,
  };
};
