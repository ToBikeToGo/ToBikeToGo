import { useEffect, useState } from 'react';
import fetchApi from '../../../helpers/fetchApi.js';
import { getApirUrl } from '../../../helpers/getApirUrl.js';

export const useShopStats = (franchiseId) => {
  const [shopStats, setShopStats] = useState(null);
  const [loading, setLoading] = useState(true);
  const [dateRange, setDateRange] = useState({
    startDate: '2021-01-01',
    endDate: '2025-01-01',
  });

  useEffect(() => {
    const fetchShopStats = async () => {
      try {
        const response = await fetchApi(
          `${getApirUrl()}/shops/${franchiseId}/stats`,
          {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify({
              startDate: dateRange.startDate,
              endDate: dateRange.endDate,
            }),
          }
        );
        const data = await response.json();
        setShopStats(data);
        setLoading(false);
      } catch (error) {
        console.error(error);
      }
    };

    fetchShopStats().then((r) => r);
  }, [dateRange.endDate, dateRange.startDate, franchiseId]);

  return { shopStats, loading, dateRange, setDateRange };
};
