import { getApirUrl } from '../helpers/getApirUrl.js';
import fetchApi from '../helpers/fetchApi.js';
import { useCallback, useState } from 'react';

export const useSlots = () => {
  const apiUrl = getApirUrl();
  const [isLoading, setIsLoading] = useState(false);

  const [slots, setSlots] = useState([]);
  const [dates, setDates] = useState({
    startDate: new Date(),
    endDate: new Date(),
  });
  const [unavailableDates, setUnavailableDates] = useState([
    {
      startDate: new Date(),
      endDate: new Date(),
    },
  ]);

  const getAvailableSlotsForDateAndShop = useCallback(
    async ({ shopId, dates }) => {
      try {
        const response = await fetchApi(`${apiUrl}/shops/${shopId}/slots`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            date: dates.startDate.toISOString().split('T')[0],
          }),
        });
        const data = await response.json();
        setSlots(data.slots);
      } catch (error) {
        console.error('Error fetching slots', error);
      }
    },
    [apiUrl]
  );

  const getUnavailableDatesForTheBike = useCallback(
    async ({ bikeId }) => {
      setIsLoading(true);
      try {
        const response = await fetchApi(
          `${apiUrl}/bikes/${bikeId}/unavailable`
        );
        const data = await response.json();
        setUnavailableDates(data);
        setIsLoading(false);
      } catch (error) {
        console.error('Error fetching slots', error);
      }
    },
    [apiUrl]
  );

  return {
    getUnavailableDatesForTheBike,
    getAvailableSlotsForDateAndShop,
    slots,
    isLoading,
    setDates,
    unavailableDates,
  };
};
