import React, { useState } from 'react';
import { TextField } from '@mui/material';
import { format, isEqual, isBefore } from 'date-fns';
import { DateRange } from 'react-date-range';
import theme from '../../theme/theme.js';
import { InfoRounded } from '@mui/icons-material';
import withToast from '../HOC/WithToastHOC.jsx';
const Calendar = ({
  calendarRef,
  onChangeDate,
  handleOpen,
  dates,
  isOpen,
  disabledDateCallback,
  minDays,
  maxDays,
  disableBefore = true,
  setToast,
}) => {
  const [error, setError] = useState(null);
  const handleDateChange = (item) => {
    const { startDate, endDate } = item.selection;

    if (startDate && endDate) {
      const daysDiff = (endDate - startDate) / (1000 * 60 * 60 * 24) + 1; // Calcul de la durée

      if (
        (daysDiff < minDays || daysDiff > maxDays) &&
        !isEqual(startDate, endDate)
      ) {
        setToast({
          message: `You must select under ${maxDays} days because your booking is for ${maxDays} days`,
          severity: 'error',
          open: true,
        });
        return;
      }

      onChangeDate(item);
    }
  };

  return (
    <>
      <div className={'flex justify-center mt-4 ba w-full'}>
        <TextField
          InputProps={{
            color: 'primary',
          }}
          label={'Start date'}
          size={'small'}
          color={'primary'}
          value={format(dates?.[0]?.startDate || new Date(), 'dd/MM/yyyy')}
          mb={2}
          onClick={handleOpen}
          sx={{
            marginRight: '2em',
          }}
        />
        <TextField
          size={'small'}
          label={'End date'}
          variant="outlined"
          value={format(dates?.[0]?.endDate || new Date(), 'dd/MM/yyyy')}
          mb={2}
          onClick={handleOpen}
        />
      </div>

      {isOpen && (
        <DateRange
          disabledDay={(date) => {
            if (disableBefore && isBefore(date, new Date())) {
              return true;
            }

            return disabledDateCallback?.(date) || false;
            return date.getDay() === 0 || date.getDay() === 6;
          }}
          onChange={handleDateChange}
          showSelectionPreview={true}
          moveRangeOnFirstSelection={false}
          months={2}
          ranges={dates}
          direction="horizontal"
          rangeColors={[theme.palette.secondary.main, '#FF8E53']}
        />
      )}
    </>
  );
};
const CalendarWithToast = withToast(Calendar);

export { CalendarWithToast as Calendar };
