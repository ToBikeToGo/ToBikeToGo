import React from 'react';
import {
  Typography,
  Container,
  CircularProgress,
  Stack,
  Paper,
  useTheme,
} from '@mui/material';
import { useShopStats } from './hooks/useFranchiseStats';
import { useParams } from 'react-router-dom';
import Box from '@mui/material/Box';
import styled from 'styled-components';
import { Calendar } from '../../components/Calendar/Calendar.jsx';
import { useCalendar } from '../../components/Calendar/hooks/useCalendar.jsx';
import {
  Chart as ChartJS,
  ArcElement,
  BarElement,
  Tooltip,
  Legend,
  CategoryScale,
  LinearScale,
  Title,
} from 'chart.js';
import { Doughnut, Bar } from 'react-chartjs-2';
import { StarRate } from '@mui/icons-material';
import { isAfter, isEqual } from 'date-fns';
ChartJS.register(BarElement, Tooltip, Legend);
ChartJS.register(ArcElement, Tooltip, Legend);
ChartJS.register(
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend
);
const Item = styled(Paper)(({ theme }) => ({
  backgroundColor: '#fff',
  padding: '20px',
  textAlign: 'center',
  color: 'black',
  flexBasis: '30%',
}));

const ShopStats = () => {
  const { shopId } = useParams();
  const { shopStats, loading, setDateRange } = useShopStats(shopId);
  const theme = useTheme();
  const { calendarRef, dates, isOpen, onChangeDate, handleOpen } = useCalendar({
    onChangeDateCallback: (d) => {
      console.log(d);
      const { startDate, endDate } = d;
      if (isAfter(startDate, endDate) || isEqual(startDate, endDate)) {
        return;
      }

      setDateRange(d);
    },
  });

  if (loading || !shopStats) {
    return (
      <CircularProgress
        sx={{
          m: 5,
        }}
      />
    );
  }
  const {
    reservationCount,
    shopName,
    totalEarned,
    nbSatisfiedBookings,
    monthlyBookings,
    avgRatingInRange,
    mostBookedCategory,
  } = shopStats;

  const unsatisfiedBookings = reservationCount - nbSatisfiedBookings;
  const data = {
    labels: ['Satisfaits', 'Non satisfaits'],
    datasets: [
      {
        label: '# of Votes',
        data: [nbSatisfiedBookings, unsatisfiedBookings],
        backgroundColor: ['rgba(96,244,10,0.2)', 'rgba(222,3,83,0.2)'],
        borderColor: ['rgb(96,236,0)', 'rgb(158,17,72)'],
        borderWidth: 1,
      },
    ],
  };

  const monthlyBookingsLabels = monthlyBookings.map(
    (booking) => `${booking.month}/${booking.year}`
  );
  const monthlyBookingsData = monthlyBookings.map(
    (booking) => booking.nbBookings
  );

  const barChartData = {
    labels: monthlyBookingsLabels,
    datasets: [
      {
        label: 'Nombre de réservations',
        data: monthlyBookingsData,
        backgroundColor: ['rgba(75,192,192,0.2)', 'rgba(78,25,146,0.2)'],
        borderColor: 'rgba(75,192,192,1)',
        borderWidth: 1,
      },
    ],
    options: {
      scales: {
        x: {
          type: 'category',
        },
      },
    },
  };

  return (
    <Container
      sx={{
        margin: '2em',
        width: '100%',
      }}
    >
      <Typography variant="h2">
        Statistiques du shop{' '}
        <span
          className={'p-3'}
          style={{
            backgroundColor: theme.palette.secondary.main,
          }}
        >
          {shopName}
        </span>
      </Typography>
      <Box
        style={{
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          position: 'relative',
          width: '100%',
          flexDirection: 'column',
          marginBottom: '20px',
          padding: '20px',
          backgroundColor: '#fff',
          borderRadius: '5em',
        }}
      >
        <Calendar
          calendarRef={calendarRef}
          handleOpen={handleOpen}
          onChangeDate={onChangeDate}
          dates={dates}
          isOpen={isOpen}
        />
      </Box>
      <Box
        sx={{
          display: 'flex',
          justifyContent: 'center',
          alignItems: 'center',
          flexDirection: 'column',
          width: '100%',
        }}
      >
        <Stack
          spacing={{ xs: 1, sm: 2 }}
          direction="row"
          useFlexGap
          flexWrap="wrap"
        >
          <Item
            sx={{
              flexGrow: 5,
              flexBasis: '50%',
            }}
          >
            {<Typography variant="h3">Nombre de réservation total</Typography>}
            <Typography
              variant="p"
              sx={{
                color: 'black',
              }}
            >
              <Bar data={barChartData} />
            </Typography>
          </Item>
          <Item>
            <Typography variant="h3">Utilisateurs satisfaits</Typography>
            <Typography
              variant="p"
              sx={{
                color: 'black',
              }}
            >
              <Doughnut data={data} />
            </Typography>
          </Item>
          <Item>
            <Typography variant="h3">Total gagné</Typography>
            <Typography
              variant="p"
              sx={{
                color: 'black',
              }}
            >
              {totalEarned} €
            </Typography>
          </Item>
          <Item>
            <Typography variant="h3">Note moyenne</Typography>
            <Typography
              variant="p"
              sx={{
                color: 'black',
              }}
            >
              {avgRatingInRange} <StarRate />
            </Typography>
          </Item>
          <Item>
            <Typography variant="h3">Catégorie la plus réservée</Typography>
            <Typography
              variant="p"
              sx={{
                color: 'black',
              }}
            >
              {mostBookedCategory}
            </Typography>
          </Item>
        </Stack>
      </Box>
    </Container>
  );
};

export { ShopStats };
